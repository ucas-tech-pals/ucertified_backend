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
//        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institutions = Institution::paginate();
        return response()->json(['message' => 'Institutions returned successfully.', 'data' => $institutions]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Institution $institution)
    {
        if (auth('institution')->id() === $institution->id) {
            $documents = $institution->documents()->paginate();
            return response()->json(['message' => 'Institution returned successfully.', 'data' => $institution, 'documents' => $documents]);
        }
        return response()->json(['message' => 'Institution returned successfully.', 'data' => $institution]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstitutionRequest $request, Institution $institution)
    {
        $institution->update($request->validated());
        return response()->json(['message' => 'Institution updated successfully.', 'data' => $institution]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $institution)
    {
        if (auth($institution)->user()->id !== $institution->id) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }
        $institution->delete();
        return response()->json(['message' => 'Institution deleted successfully.']);
    }

    public function documents()
    {

        $documents = auth('institution')->documents()->paginate();
        return response()->json(['message' => 'Documents returned successfully.', 'data' => $documents]);
    }
}
