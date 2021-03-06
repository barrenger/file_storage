<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLinks extends Model
{
    use HasFactory;

    protected $fillable = ['file_id', 'link_code'];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
