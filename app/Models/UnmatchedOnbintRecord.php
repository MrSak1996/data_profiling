<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UnmatchedOnbintRecord extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $table = 'dp_onbint_unmatched';

    protected $fillable = [
        'ID', 
        'RSBSASYSTEMGENERATEDNUMBER', 
        'FIRSTNAME', 
        'MIDDLENAME', 
        'LASTNAME', 
        'EXTENSIONNAME', 
        'IDNUMBER', 
        'GOVTIDTYPE', 
        'STREETNO_PUROKNO', 
        'BARANGAY', 
        'CITYMUNICIPALITY', 
        'DISTRICT', 
        'PROVINCE', 
        'REGION', 
        'BIRTHDATE', 
        'PLACEOFBIRTH', 
        'MOBILENO', 
        'SEX', 
        'NATIONALITY', 
        'PROFESSION', 
        'SOURCEOFFUNDS', 
        'MOTHERMAIDENNAME', 
        'NOOFFARMPARCEL', 
        'TFA', 
        'remarks', 
        'updated_at', 
        'created_at' 
    ];
  





}

