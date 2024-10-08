<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUploadModel extends Model
{
    use HasFactory;
    protected $table = 'file_uploaded';

    protected $fillable = [
        'id',
        'file_name',
        'uploaded_by',
        'updated_by',
        'created_by'
    ];
}
