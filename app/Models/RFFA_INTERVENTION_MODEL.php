<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFFA_INTERVENTION_MODEL extends Model
{
    use HasFactory;
    protected $connection = 'second_db';
    protected $table = 'vw_fims_rffa_intervention';
    protected $fillable = [
        'rsbsa_no',
        'control_no',
        'first_name',
        'middle_name',
        'surname',
        'ext_name',
        'mother_maiden_name',
        'sex',
        'birthday'
    ];

    

    
}
