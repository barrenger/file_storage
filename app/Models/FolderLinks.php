<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolderLinks extends Model
{
    use HasFactory;

    protected $fillable = ['folder_id', 'link_code'];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
