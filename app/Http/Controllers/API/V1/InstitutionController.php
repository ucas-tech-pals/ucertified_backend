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
        return response()->json(['message' => 'Institutions returend successfully.', 'data' => $institutions], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $institution = Institution::findOrFail($id);
        return response()->json(['message' => 'Institution returned successfully.', 'data' => $institution], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $institution = Institution::findOrFail($id);
        $deletedInstitution = $institution->delete();
        return response()->json(['message' => 'Institution deleted successfully.', 'deleted' => $deletedInstitution], 200);
    }
}
