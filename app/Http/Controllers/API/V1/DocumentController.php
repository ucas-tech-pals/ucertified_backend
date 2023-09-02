<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Document\StoreDocumentRequest;
use App\Http\Requests\V1\Document\UpdateDocumentRequest;
use App\Http\Requests\V1\Document\VerifyDocumentRequest;
use App\Models\Document;
use App\Models\Institution;
use App\Models\User;
use App\PDFCryptoSigner\PDFCryptoSigner;
use Illuminate\Support\Facades\Hash;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user')->only('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = auth('user')->user()->documents()->paginate();
        $documents->getCollection()->transform(function ($document) {
            $document->url = asset('storage/' . $document->path);
            return $document;
        });
        return response()->json(['message' => 'Documents returned successfully.', 'data' => $documents]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        $file = $request->file('file');
        $document = Document::create([
            'name' => $request->name,
            'institution_id' => auth('institution')->id(),
        ]);

        $headers = [
            'name' => $request->name,
            'document_id' => $document->id,
        ];

        if ($request->has('password')) {
            $headers['password'] = Hash::make($request->password);
        }

        PDFCryptoSigner::load($file->path())
            ->sign(auth('institution')->user()->private_key, $headers);

        if ($request->user_email) {
            $user = User::where('email', $request->user_email)->firstOrFail();
            $path = $request->file('file')->store('documents', [
                'disk' => 'public',
                'visibility' => 'public'
            ]);
            $document->user_id = $user->id;
            $document->path = $path;
            $document->save();
        }
        return response()->download($file->path(), 'signed.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend();

    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $document->url = asset('storage/' . $document->path);
        return response()->json(['message' => 'Document returned successfully.', 'data' => $document]);
    }

    public function verify(VerifyDocumentRequest $request)
    {
        $path = $request->file('file')->path();

        $headers = PDFCryptoSigner::load($path)->getHeaders();
        if ($headers && !isset($headers['document_id'])) {
            return response()->json(['message' => 'Document not verified.']);
        }

        $institution = Institution::whereHas('documents', function ($query) use ($headers) {
            $query->where('id', $headers['document_id']);
        })->first();

        if (!$institution) {
            return response()->json(['message' => 'Document not verified.']);
        }

        $headers = PDFCryptoSigner::load($path)->verify($institution->public_key);

        if (!$headers['verified']) {
            return response()->json(['message' => 'Document not verified.']);
        }

        if (!auth('user')->check()){
            return response()->json(['message' => 'Document verified successfully.', 'data' => $headers]);
        }

        if (isset($headers['password']) && !Hash::check($request->password, $headers['password'])) {
            return response()->json(['message' => 'Invalid password.']);
        }

        $document = Document::findOrFail($headers['document_id']);
        if ($document->user_id != null) {
            return response()->json(['message' => 'Document already verified.', 'data' => $headers]);
        }

        $path = $request->file('file')->store('documents', [
            'disk' => 'public',
            'visibility' => 'public'
        ]);
        $document->user_id = auth('user')->id();
        $document->path = $path;
        $document->save();

        $headers['url'] = asset('storage/' . $path);
        return response()->json(['message' => 'Document has been added to your profile.', 'data' => $headers]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }
}
