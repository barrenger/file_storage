<!-- index.blade.php -->

@extends('layout')

@section('header')
@if(isset($myfolder))
<h1>{{$myfolder->name}}</h1>
@else
<h1>Files & Folders</h1>
@endif
<!-- Create File Modal -->
<button class="btn btn-primary" data-toggle="modal" data-target="#NewFile" type="submit">Upload File</button>
<div class="modal fade" id="NewFile" tabindex="20" role="dialog" aria-labelledby="NewFile" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="NewFile">Upload File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form enctype="multipart/form-data" id="upload-file" action="{{ url('store') }}"  method="post" >
        <div class="modal-body">
          <div class="form-group">
            @csrf
            <input type="file" name="file" placeholder="Choose file" id="file">
              @error('file')
            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
              @enderror     
            @if(isset($myfolder))
            <input type="hidden" name="folder" value="{{$myfolder->id}}">
            @else
            <input type="hidden" name="folder" value="null">
            @endif
          </div>     
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@if(isset($myfolder))
<!-- Rename Folder Modal -->
<button class="btn btn-primary" data-toggle="modal" data-target="#RenameFolder" type="submit">Rename Folder</button>
<div class="modal fade" id="RenameFolder" tabindex="20" role="dialog" aria-labelledby="RenameFolder" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="RenameFolder">Rename Folder</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('folders.update',$myfolder->id)}}" method="post">
        <div class="modal-body">
          <div class="form-group">
          @csrf
          @method('PATCH')
            <label>Folder name: </label>
            <input type="text" name="name" value="{{$myfolder->name}}" style="min-width:300px;">
          </div>     
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@else
<!-- Create Folder Modal -->
<button class="btn btn-primary" data-toggle="modal" data-target="#NewFolder" type="submit">Create Folder</button>
<div class="modal fade" id="NewFolder" tabindex="20" role="dialog" aria-labelledby="NewFolder" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="NewFolder">Create Folder</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('folders.store')}}" method="post">
        <div class="modal-body">
          <div class="form-group">
          @csrf
            <label>Folder name: </label>
            <input type="text" name="name">
          </div>     
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endsection

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="uper">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif

  <!-- List Folders -->
  <div class="row">
    @if(!isset($myfolder))
    @foreach($folders as $folder)
    <div class="col-sm-4 col-md-3 col-lg-2 col-xl-1 mb-2">
      <article class="card" style="background-color: #cc9c24; border: 1px solid #ad851f;">
        <div style="display:flex; justify-content: flex-end;">
          <button data-toggle="modal" data-target="#ModalFileLink{{$folder->id}}" style="background-color: #e0b852; border:none; padding: 4px 8px 4px 16px; margin: 0; border-left: 1px solid #ad851f;" type="submit"><i class="fa fa-link"></i></button>
          <!-- Folder Link Modal -->
          <div class="modal fade" id="ModalFileLink{{$folder->id}}" tabindex="20" role="dialog" aria-labelledby="ModalLabel{{$folder->id}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel{{$folder->id}}">Public Link to {{$folder->name}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                <!-- Text to be displayed -->
                {{ route('folders.show', $folder->link->link_code) }}
                <button type="button" class="btn btn-primary ml-2" style="float: right;" onclick="copyStringToClipboard('folderLink{{$folder->id}}')">Copy</button>
                <!-- Text to be copied -->
                <input id="folderLink{{$folder->id}}" style="height: 0; color:white; border:none;" type="text" value="{{ route('folders.show', $folder->link->link_code) }}">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- End Modal -->
          <!-- Folder Rename Modal -->
          <button style="color:black; padding: 4px 8px; background-color: #e0b852; border:none;" data-toggle="modal" data-target="#ModalFolderUpdate{{$folder->id}}" type="submit"><i class="fa fa-pencil-square-o"></i></button>
          <div class="modal fade" id="ModalFolderUpdate{{$folder->id}}" tabindex="20" role="dialog" aria-labelledby="ModalFolderUpdate{{$folder->id}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalFolderUpdate{{$folder->id}}">Rename Folder</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('folders.update', $folder->id)}}" method="post">
                  <div class="modal-body">
                    <div class="form-group">
                    @csrf
                    @method('PATCH')
                      <label>Folder name: </label>
                      <input type="text" name="name" value="{{$folder->name}}" style="min-width:300px;">
                    </div>     
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End Modal -->
          <!-- Delete Folder Form-->
          <form action="{{ route('folders.destroy', $folder->id)}}" method="post">
            @csrf
            @method('DELETE')
            <button style="background-color: #e0b852; border:none; padding: 4px 16px 4px 8px; margin: 0;" type="submit"><i class="fa fa-trash"></i></button>
          </form>
          <!-- End Form -->
        </div>
        <!-- Folder Name -->
        <div class="card-body p-0" style="margin-bottom: -12px;"><a style="color:black; text-decoration:none;" href="{{route('folders.show', $folder->link->link_code)}}">
          <h5 class="card-title" style="min-height: 120px; padding: 8px; background-color: #e0b852;">{{$folder->name}}</h5>
        </a></div>
      </article>
    </div>
    @endforeach
    @endif
  </div>
  
  <!-- List Files -->
  <div class="row">
    @foreach($files as $file)
    @if(!isset($myfolder))
    @if($file->folder_id == "")
    <div class="col-sm-4 col-md-3 col-lg-2 col-xl-1 mb-2">
      <article class="card" style="border: 1px solid #527ae0;">
        <div style="display:flex; justify-content: space-between; background-color:  #a8bdf0;">
          <!-- File Icon-->
          @if($file->extension == "docx" or $file->extension == "doc" or $file->extension == "dom" or $file->extension == "dotx" or $file->extension == "dotm" or $file->extension == "dot")
            <p style="color:black; padding: 4px 8px 4px 16px; margin:0;"><i class="fa fa-file-word-o"></i></p>
          @elseif($file->extension == "xlsx" or $file->extension == "xlsm" or $file->extension == "xlsb" or $file->extension == "xls" or $file->extension == "xlm")
            <p style="color:black; padding: 4px 8px 4px 16px; margin:0;"><i class="fa fa-file-excel-o"></i></p>
          @else
            <p style="color:black; padding: 4px 8px 4px 16px; margin:0;"><i class="fa fa-file"></i></p>
          @endif
          <!-- File Extension-->
          <p style="color:black; padding: 4px 32px 4px 8px; margin:0;"><b>{{$file->extension}}</b></p>
        </div>
        <!-- File Name-->
        <div class="card-body p-0">
          <h5 class="card-title" style="min-height: 120px; padding: 8px; margin:0; background-color: #527ae0;">{{$file->name}}</h5>
        </div>
        <div style="display:flex; justify-content: space-between; background-color:white;">
          <button data-toggle="modal" data-target="#ModalFileLink{{$file->id}}" style="background-color: RGB(0,0,0,0); border:none; padding: 4px 16px 4px 8px; margin: 0;" type="submit"><i class="fa fa-link"></i></button>
          <!-- File Link Modal -->
          <div class="modal fade" id="ModalFileLink{{$file->id}}" tabindex="20" role="dialog" aria-labelledby="ModalLabel{{$file->id}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel{{$file->id}}">Public Link to {{$file->name}}.{{$file->extension}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                <!-- Text to be displayed -->
                {{ route('files.show', $file->link->link_code) }}
                <button type="button" class="btn btn-primary ml-2" style="float: right;" onclick="copyStringToClipboard('fileLink{{$file->id}}')">Copy</button>
                <!-- Text to be copied -->
                <input id="fileLink{{$file->id}}"  style="height: 0; color:white; border:none;" type="text" value="{{ route('files.show', $file->link->link_code) }}">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- End Modal -->
          <!-- File Rename Modal -->
          <button style="color:black; padding: 4px 8px; background-color: RGB(0,0,0,0); border:none;" data-toggle="modal" data-target="#ModalFileUpdate{{$file->id}}" type="submit"><i class="fa fa-pencil-square-o"></i></button>
          <div class="modal fade" id="ModalFileUpdate{{$file->id}}" tabindex="20" role="dialog" aria-labelledby="ModalFileUpdate{{$file->id}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalFileUpdate{{$file->id}}">Rename File</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('files.update', $file->id)}}" method="post">
                  <div class="modal-body">
                    <div class="form-group">
                    @csrf
                    @method('PATCH')
                      <label>File name: </label>
                      <input type="text" name="name" value="{{$file->name}}" style="min-width:300px;">
                      .
                      <input type="text" name="extension" value="{{$file->extension}}" style="max-width:70px;">
                      <input type="hidden" name="folder_id" value="{{$file->folder_id}}">
                    </div>     
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End Modal -->
          <!-- File Move Folder Modal -->
          <button style="color:black; padding: 4px 8px; background-color: RGB(0,0,0,0); border:none;" data-toggle="modal" data-target="#ModalFileMove{{$file->id}}" type="submit"><i class="fa fa-folder-o"></i></button>
          <div class="modal fade" id="ModalFileMove{{$file->id}}" tabindex="20" role="dialog" aria-labelledby="ModalFileMove{{$file->id}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalFileMove{{$file->id}}">Move File</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('files.update', $file->id)}}" method="post">
                  <div class="modal-body">
                    <div class="form-group">
                    @csrf
                    @method('PATCH')
                      <label>Folder: </label>
                      <input type="hidden" name="name" value="{{$file->name}}">
                      <input type="hidden" name="extension" value="{{$file->extension}}">
                      <select name="folder_id" id="folder_id" style="min-width: 300px;">
                        @if(is_null($file->folder_id))
                          <option value="null" style="color:grey;" selected>No Folder Selected...</option>
                        @else
                          <option value="null" style="color:grey;">No Folder Selected...</option>
                        @endif
                        @foreach($folders as $folder)
                          @if($folder->id == $file->folder_id)
                          <option value="{{$folder->id}}" selected>{{$folder->name}}</option>
                          @else
                          <option value="{{$folder->id}}">{{$folder->name}}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>     
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End Modal -->
          <!-- File Download Link -->
          <a href="{{ route('files.show', $file->link->link_code) }}" style="color:black; padding: 4px 8px;"><i class="fa fa-cloud-download"></i></a>
          <!-- Delete Folder Form-->
          <form action="{{ route('files.destroy', $file->id)}}" method="post">
            @csrf
            @method('DELETE')
            <button style="background-color: RGB(0,0,0,0); border:none; padding: 4px 16px 4px 8px; margin: 0;" type="submit"><i class="fa fa-trash"></i></button>
          </form>
          <!-- End Form-->
        </div>
      </article>
    </div>
    @endif
    @else
    @if($file->folder_id == $myfolder->id)
    <div class="col-sm-4 col-md-3 col-lg-2 col-xl-1 mb-2">
      <article class="card" style="border: 1px solid #527ae0;">
        <div style="display:flex; justify-content: space-between; background-color:  #a8bdf0;">
          <!-- File Icon-->
          @if($file->extension == "docx" or $file->extension == "doc" or $file->extension == "dom" or $file->extension == "dotx" or $file->extension == "dotm" or $file->extension == "dot")
            <p style="color:black; padding: 4px 8px 4px 16px; margin:0;"><i class="fa fa-file-word-o"></i></p>
          @elseif($file->extension == "xlsx" or $file->extension == "xlsm" or $file->extension == "xlsb" or $file->extension == "xls" or $file->extension == "xlm")
            <p style="color:black; padding: 4px 8px 4px 16px; margin:0;"><i class="fa fa-file-excel-o"></i></p>
          @else
            <p style="color:black; padding: 4px 8px 4px 16px; margin:0;"><i class="fa fa-file"></i></p>
          @endif
          <!-- File Extension-->
          <p style="color:black; padding: 4px 32px 4px 8px; margin:0;"><b>{{$file->extension}}</b></p>
        </div>
        <!-- File Name-->
        <div class="card-body p-0">
          <h5 class="card-title" style="min-height: 120px; padding: 8px; margin:0; background-color: white;">{{$file->name}}</h5>
        </div>
        <div style="display:flex; justify-content: space-between; background-color:#a8bdf0;">
          <button data-toggle="modal" data-target="#ModalFileLink{{$file->id}}" style="background-color: RGB(0,0,0,0); border:none; padding: 4px 16px 4px 8px; margin: 0;" type="submit"><i class="fa fa-link"></i></button>
          <!-- File Link Modal -->
          <div class="modal fade" id="ModalFileLink{{$file->id}}" tabindex="20" role="dialog" aria-labelledby="ModalLabel{{$file->id}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel{{$file->id}}">Public Link to {{$file->name}}.{{$file->extension}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                <!-- Text to be displayed -->
                {{ route('files.show', $file->link->link_code) }}
                <button type="button" class="btn btn-primary ml-2" style="float: right;" onclick="copyStringToClipboard('fileLink{{$file->id}}')">Copy</button>
                <!-- Text to be copied -->
                <input id="fileLink{{$file->id}}"  style="height: 0; color:white; border:none;" type="text" value="{{ route('files.show', $file->link->link_code) }}">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- End Modal -->
          <!-- File Rename Modal -->
          <button style="color:black; padding: 4px 8px; background-color: RGB(0,0,0,0); border:none;" data-toggle="modal" data-target="#ModalFileUpdate{{$file->id}}" type="submit"><i class="fa fa-pencil-square-o"></i></button>
          <div class="modal fade" id="ModalFileUpdate{{$file->id}}" tabindex="20" role="dialog" aria-labelledby="ModalFileUpdate{{$file->id}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalFileUpdate{{$file->id}}">Rename File</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('files.update', $file->id)}}" method="post">
                  <div class="modal-body">
                    <div class="form-group">
                    @csrf
                    @method('PATCH')
                      <label>File name: </label>
                      <input type="text" name="name" value="{{$file->name}}" style="min-width:300px;">
                      .
                      <input type="text" name="extension" value="{{$file->extension}}" style="max-width:70px;">
                      <input type="hidden" name="folder_id" value="{{$file->folder_id}}">
                    </div>     
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End Modal -->
          <!-- File Move Folder Modal -->
          <button style="color:black; padding: 4px 8px; background-color: RGB(0,0,0,0); border:none;" data-toggle="modal" data-target="#ModalFileMove{{$file->id}}" type="submit"><i class="fa fa-folder-o"></i></button>
          <div class="modal fade" id="ModalFileMove{{$file->id}}" tabindex="20" role="dialog" aria-labelledby="ModalFileMove{{$file->id}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalFileMove{{$file->id}}">Move File</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('files.update', $file->id)}}" method="post">
                  <div class="modal-body">
                    <div class="form-group">
                    @csrf
                    @method('PATCH')
                      <label>Folder: </label>
                      <input type="hidden" name="name" value="{{$file->name}}">
                      <input type="hidden" name="extension" value="{{$file->extension}}">
                      <select name="folder_id" id="folder_id" style="min-width: 300px;">
                        @if(is_null($file->folder_id))
                          <option value="null" style="color:grey;" selected>No Folder Selected...</option>
                        @else
                          <option value="null" style="color:grey;">No Folder Selected...</option>
                        @endif
                        @foreach($folders as $folder)
                          @if($folder->id == $file->folder_id)
                          <option value="{{$folder->id}}" selected>{{$folder->name}}</option>
                          @else
                          <option value="{{$folder->id}}">{{$folder->name}}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>     
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End Modal -->
          <!-- File Download Link -->
          <a href="{{ route('files.show', $file->link->link_code) }}" style="color:black; padding: 4px 8px;"><i class="fa fa-cloud-download"></i></a>
          <!-- Delete Folder Form-->
          <form action="{{ route('files.destroy', $file->id)}}" method="post">
            @csrf
            @method('DELETE')
            <button style="background-color: RGB(0,0,0,0); border:none; padding: 4px 16px 4px 8px; margin: 0;" type="submit"><i class="fa fa-trash"></i></button>
          </form>
          <!-- End Form-->
        </div>
      </article>
    </div>
    @endif
    @endif
    @endforeach
  </div>
  <div id="deselect"></div>
<div>
<script>
  function copyStringToClipboard(id){
  /* Get the text field */
  copyText = document.getElementById(id);
  copyText.style.display = "block";
  
  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* hide */
  copyText.style.display = "none";
}
</script>
@endsection