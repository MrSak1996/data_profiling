<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvalidDataModel extends Model
{
    use HasFactory;
    protected $table = 'dp_onbint_invalid';

    protected $fillable = [
        'id',
        'filename',
        'column_name',
        'invalid_data'
    ];
}
