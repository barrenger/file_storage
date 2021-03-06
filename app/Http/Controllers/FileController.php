<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;
use App\Models\FileLinks;

use App\Http\Controllers\Form;
use Illuminate\Support\Facades\Storage;
//use App\Http\Controllers\Storage;
//use Illuminate\Support\Facades\Input;
use Barryvdh\Debugbar\Facade as DebugBar;

//use \Debugbar;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        
        $validatedData = $request->validate([
           'file' => 'required',
        ]);

        // Get file name.
        $name = $request->file('file')->getClientOriginalName();
        
        // Create File in the database
        if($request->folder == "null"){
            $file = File::create([
                'name' => pathinfo($name, PATHINFO_FILENAME),
                'extension' => pathinfo($name, PATHINFO_EXTENSION),
                'folder_id' => null
            ]);
        } else {
            $file = File::create([
                'name' => pathinfo($name, PATHINFO_FILENAME),
                'extension' => pathinfo($name, PATHINFO_EXTENSION),
                'folder_id' => $request->folder
            ]);
        }

        // Create the public link for the file
        $linkCode = md5(uniqid());
        $link = FileLinks::create([
            'file_id' => $file->id,
            'link_code' => $linkCode
        ]);

        // Store the file on the server.
        $path = $request->file('file')->storeAs('public/files/' . $file->id, $name);


        if($request->folder == "null"){
            return redirect('/')->with('success', 'File Has been uploaded successfully in laravel 8');
        } else {
            // Get folder object.
            $myfolder = Folder::findOrFail($request->folder);

            return redirect('folders/' . $myfolder->link->link_code);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {;
        // Get Link object.
        $fileLink = FileLinks::where('link_code', $id)->firstOrFail();

        // Get file object.
        $file = $fileLink->file;

        // Get file path.
        $pathToFile = storage_path('app/public/files/' . $file->id . '/' . $file->name . '.' . $file->extension);

        return response()->download($pathToFile);
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
        
        $file = File::findOrFail($id);
        $originalName = $file->name;
        $folder = $file->folder;

        // Handle the request if the folder id is null.
        if($request->folder_id == "null"){
            
            // Validate the data.
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'extension' => 'required|max:10'
            ]);
            
            // Update the file model.
            File::whereId($id)->update($validatedData);
            
            // Rename the file if the name has changed.
            if($originalName <> $request->name){
                Storage::rename('public/files/' . $file->id . '/' . $originalName . '.' . $file->extension,'public/files/' . $file->id . '/' . $file->name . '.' . $file->extension);
            }

            // Set the folder id to null.
            $file = File::findOrFail($id);
            $file->folder_id = null;

            // Save the updated model to the database.
            $file->save();

        //Handle the request if the folder id is not null.
        } else {
            // Validate the data.
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'extension' => 'required|max:10',
                'folder_id' => 'required'
            ]);

            // Update the file model.
            File::whereId($id)->update($validatedData);
            
            // Rename the file if the name has changed.
            if($originalName <> $request->name){
                Storage::rename('public/files/' . $file->id . '/' . $originalName . '.' . $file->extension,'public/files/' . $file->id . '/' . $request->name . '.' . $file->extension);
            }
        }

        // Return to the correct folder.
        if(is_null($folder)){
            return redirect('/')->with('success', 'File is successfully updated');
        } else {
            return redirect('folders/' . $folder->link->link_code)->with('success', 'File is successfully updated');
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $folder = $file->folder;

        //Delete Link.
        $link = $file->link();
        $link->delete();

        // Delete the file on the server.
        Storage::delete('public/files/' . $file->id . '/' . $file->name . '.' . $file->extension);

        // Delete File Object.
        $file->delete();

        // Return to the correct folder.
        if(is_null($folder)){
            return redirect('/')->with('success', 'File is successfully deleted');
        } else {
            return redirect('folders/' . $folder->link->link_code);
        }
        
    }
}
