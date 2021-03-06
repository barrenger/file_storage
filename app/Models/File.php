<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'extension', 'folder_id'];

    public function link()
    {
        return $this->hasOne(FileLinks::class);
    }
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }
}
