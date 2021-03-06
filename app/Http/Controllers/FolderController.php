<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Folder;
use App\Models\FolderLinks;
use App\Models\File;

use \Debugbar;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folders = Folder::all();
        $files = File::all();

        return view('index',compact('folders','files'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate thd data.
        $validatedData = $request->validate([
            'name' => 'required|max:255'
        ]);

        // Create the folder model.
        $folder = Folder::create($validatedData);

        // Create the public link for the folder.
        $linkCode = md5(uniqid());
        $link = FolderLinks::create([
            'folder_id' => $folder->id,
            'link_code' => $linkCode
        ]);

        // Return to the index page.
        return redirect('/')->with('success', 'Folder successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Link object.
        $folderLink = FolderLinks::where('link_code', $id)->firstOrFail();

        // Get folder object.
        $myfolder = $folderLink->folder;

        // Retrieve data for the view.
        $folders = Folder::all();
        $files = File::all();

        // Display the folder.
        return view('index',compact('folders','myfolder','files'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the data.
        $validatedData = $request->validate([
            'name' => 'required|max:255'
        ]);

        // Update the folder.
        Folder::whereId($id)->update($validatedData);

        // Retrieve data for the view.
        $folders = Folder::all();
        $files = File::all();
        $myfolder = Folder::find($id);

        // View the folder.
        return view('index',compact('folders','myfolder','files'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the folder.
        $folder = Folder::findOrFail($id);
        
        // Delete Files in Folder.
        $files = $folder->files;
        foreach ($files as $file) {
            // Delete File Link.
            $link = $file->link;
            $link->delete();

            // Delete the file on the server.
            Storage::delete('public/files/' . $file->id . '/' . $file->name . '.' . $file->extension);

            // Delete File.
            $file->delete(); 
        }
        
        // Delete Folder Links.
        $link = $folder->link;
        $link->delete();

        //Delete Folder
        $folder->delete();

        // Return to the index page.
        return redirect('/')->with('success', 'Folder and files successfully deleted');
    }
}
