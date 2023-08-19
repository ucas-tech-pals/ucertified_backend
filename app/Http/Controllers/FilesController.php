<?php

namespace App\Http\Controllers;

use App\Services\IPFSStorageService;
use Illuminate\Http\Request;

class FilesController extends Controller
{
    
    public function upload(Request $request, IPFSStorageService $ipfsStorageService)
    {
        $uploaded_file = $request->uploaded_file;
        $originalName = $uploaded_file->getClientOriginalName();
        $name = str_replace(' ', '', $originalName);
        $content = $uploaded_file->getContent();

        $ipfs_link = $ipfsStorageService->upload($content, $name);

        return redirect()->route("dashboard");
    }
// ___________________________________________________________________________

    public function get(IPFSStorageService $ipfsStorageService)
    {
      $stored_files =  $ipfsStorageService->get();

      return view('view', compact('stored_files'));
    }
}