<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function link()
    {
        return $this->hasOne(FolderLinks::class);
    }
    public function files()
    {
        return $this->hasMany(File::class);
    }
}
