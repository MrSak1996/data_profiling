<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RFFA_INTERVENTION_MODEL;


class OnbintModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $table = 'dp_onbint_staging';

    protected $fillable = [
        'FILE_ID',
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
        'user_id',
        'date_uploaded',
        'action'
    ];
    public function farmers()
    {
        return $this->hasMany(RFFA_INTERVENTION_MODEL::class, 'rsbsa_no', 'RSBSASYSTEMGENERATEDNUMBER');
        }
    


    
    
}

