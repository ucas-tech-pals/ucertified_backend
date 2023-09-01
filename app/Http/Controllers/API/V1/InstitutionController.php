<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstitutionRequest;
use App\Http\Requests\UpdateInstitutionRequest;
use App\Models\Institution;

class InstitutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution')->only(['update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institutions = Institution::paginate();
        return response()->json(['message' => 'Universities returned successfully.', 'data' => $institutions]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Institution $institution)
    {
        if (auth('institution')->id() === $institution->id) {
            $documents = $institution->documents()->paginate();
            return response()->json(['message' => 'University returned successfully.', 'data' => $institution, 'documents' => $documents]);
        }
        return response()->json(['message' => 'University returned successfully.', 'data' => $institution]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstitutionRequest $request, Institution $institution)
    {
        $institution->update($request->validated());
        return response()->json(['message' => 'University updated successfully.', 'data' => $institution]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $institution)
    {
        if (auth('institution')->id() !== $institution->id) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }
        $institution->delete();
        return response()->json(['message' => 'University deleted successfully.']);
    }
}
