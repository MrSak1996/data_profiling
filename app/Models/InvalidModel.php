<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvalidModel extends Model
{
    use HasFactory;
    protected $table = 'dp_onbint_invalid_sum';

    protected $fillable = [
        'id',
        'filename',
        'specialchar',
        'null_values',
        'below_2letters',
        'unwanted_char',
        'date_format',
        'unwanted_spaces',
        'invalid_address'
    ];
}
