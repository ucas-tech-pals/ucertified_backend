<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstitutionRequest;
use App\Http\Requests\UpdateInstitutionRequest;
use App\Models\Institution;

class InstitutionController extends Controller
{
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
        return response()->json(['message' => 'Institution returned successfully.', 'data' => $institution]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstitutionRequest $request, Institution $institution)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $institution)
    {
        $institution->delete();
        return response()->json(['message' => 'Institution deleted successfully.']);
    }
}
