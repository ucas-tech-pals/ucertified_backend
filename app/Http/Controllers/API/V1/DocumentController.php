<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Document\StoreDocumentRequest;
use App\Http\Requests\V1\Document\UpdateDocumentRequest;
use App\Http\Requests\V1\Document\VerifyDocumentRequest;
use App\Models\Document;
use App\Models\Institution;
use App\PDFCryptoSigner\PDFCryptoSigner;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = auth('user')->user()->documents()->paginate();
        $documents->getCollection()->transform(function ($document) {
            $document->file = asset('storage/' . $document->file);
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
        $signature = PDFCryptoSigner::load($file->path())
            ->sign(auth('institution')->user()->private_key,
            [
                'name' => $request->name,
                'document_id' => $document->id,
            ]);
        $document->signature = $signature;
        $document->save();
        return response()->file($file->path())->deleteFileAfterSend();

    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        // get the url of the file
        $document->file = asset('storage/' . $document->file);
        return response()->json(['message' => 'Document returned successfully.', 'data' => $document]);
    }

    public function verify(VerifyDocumentRequest $request)
    {
        $path = $request->file('file')->path();

        $response = PDFCryptoSigner::load($path)->getHeaders();
        if (!isset($response['document_id'])) {
            return response()->json(['message' => 'Document not verified.', 'data' => $response]);
        }
        $document_id = $response['document_id'];

        $institution = Institution::whereHas('documents', function ($query) use ($document_id) {
            $query->where('id', $document_id);
        })->first();

        $response = PDFCryptoSigner::load($path)->verify($institution->public_key);

        if (!$response['verified']) {
            return response()->json(['message' => 'Document not verified.', 'data' => $response]);
        }

        if (!auth('user')->check()){
            return response()->json(['message' => 'Document verified successfully.', 'data' => $response]);
        }
        $path = $request->file('file')->store('documents');

        $document = Document::findOrFail($response['document_id']);
        $document->path = $path;
        $document->user_id = auth('user')->id();
        $document->save();
        return response()->json(['message' => 'Document verified successfully.', 'data' => $response]);
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
