<?php

namespace App\Http\Controllers;

use App\Http\Controllers\StringComparisonController;


use Facade\FlareClient\Http\Exceptions\InvalidData;
use Illuminate\Http\Request;
use App\Models\OnbintModel;
use App\Models\FileUploadModel;
use App\Models\RFFA_INTERVENTION_MODEL;
use App\Models\InvalidDataModel;
use App\Models\InvalidModel;
use App\Models\UnmatchedOnbintRecord;
use App\Models\MatchedOnbintRecord;
use App\Models\RFFAInteventionsModel;
use App\Models\User;
use Illuminate\Http\File;

use App\Exports\OnbintExport;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill; // Import the Fill class for styling



use App\Imports\UsersImport;
use App\Imports\OnbintImport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Psy\Command\WhereamiCommand;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataProfilingController extends Controller
{
    

    public function getOnbintStaging(Request $request)
    {
        $id = $request->query('id');

        $query = OnbintModel::select(OnbintModel::raw(
            'FILE_ID,
            RSBSASYSTEMGENERATEDNUMBER, 
            FIRSTNAME, 
            MIDDLENAME, 
            LASTNAME, 
            EXTENSIONNAME, 
            IDNUMBER, 
            GOVTIDTYPE, 
            STREETNO_PUROKNO, 
            BARANGAY, 
            CITYMUNICIPALITY, 
            DISTRICT, 
            PROVINCE, 
            REGION, 
            BIRTHDATE, 
            PLACEOFBIRTH, 
            MOBILENO, 
            SEX, 
            NATIONALITY, 
            PROFESSION, 
            SOURCEOFFUNDS, 
            MOTHERMAIDENNAME, 
            NOOFFARMPARCEL, 
            TFA
            '
        ))
            ->where('FILE_ID', $id)
            ->get();
        return response()->json($query);
    }

    public function onbint_countnull($id, $filename)
    {
        // Fetch the aggregated results
        $results = InvalidDataModel::selectRaw("
                SUM(CASE WHEN invalid_data REGEXP '[^a-zA-Z0-9 ]' THEN 1 ELSE 0 END) AS special_char_count,
                SUM(CASE WHEN LENGTH(invalid_data) < 2 THEN 1 ELSE 0 END) AS less_than_2_chars_count,
                SUM(CASE WHEN invalid_data REGEXP '[a-zA-Z]' AND invalid_data REGEXP '[0-9]' THEN 1 ELSE 0 END) AS alphanumeric_count,
                COUNT(CASE WHEN invalid_data IS NULL THEN 1 ELSE NULL END) AS null_count,
                SUM(CASE WHEN column_name = 'BIRTHDATE' AND invalid_data NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$' THEN 1 ELSE 0 END) AS wrong_date_format_count
            ")
            ->where('file_id', $id)
            ->first(); // Use first() to get a single result

        // Check if results are found
        if (!$results) {
            return response()->json(['error' => 'No results found'], 404);
        }

        // Insert the results into the dp_onbint_invalid table
        InvalidModel::create([
            'filename' => $filename, // Set the filename appropriately
            'specialchar' => $results->special_char_count,
            'null_values' => $results->null_count,
            'below_2letters' => $results->less_than_2_chars_count,
            'date_format' => $results->wrong_date_format_count,
            'unwanted_char' => $results->alphanumeric_count,
        ]);

        // Return the results as JSON
        return response()->json($results);
    }

    public function saveExcelData(Request $request)
    {
        $data = (new OnbintImport)->toArray($request->file('file'));
        $filename = $request->input('filename');
        $uploaded_by = $request->input('userId'); // Assuming userId is passed in the request

        // Insert file data and get the ID
        $fileUpload = FileUploadModel::create([
            'file_name' => $filename,
            'uploaded_by' => $uploaded_by,
            'updated_at' => now(),
            'created_at' => now()
        ]);


        $fileUploadId = $fileUpload->id; // Capture the inserted file's ID
        $columnMapping = [
            'RSBSASYSTEMGENERATEDNUMBER' => 0,
            'FIRSTNAME' => 1,
            'MIDDLENAME' => 2,
            'LASTNAME' => 3,            // Fixed numbering
            'EXTENSIONNAME' => 4,       // Fixed numbering
            'IDNUMBER' => 5,
            'GOVTIDTYPE' => 6,
            'STREETNO_PUROKNO' => 7,
            'BARANGAY' => 8,
            'CITYMUNICIPALITY' => 9,
            'DISTRICT' => 10,
            'PROVINCE' => 11,
            'REGION' => 12,
            'BIRTHDATE' => 13,
            'PLACEOFBIRTH' => 14,
            'MOBILENO' => 15,
            'SEX' => 16,
            'NATIONALITY' => 17,
            'PROFESSION' => 18,
            'SOURCEOFFUNDS' => 19,
            'MOTHERMAIDENNAME' => 20,
            'NOOFFARMPARCEL' => 21,
            'TFA' => 22,
        ];
        $batchData = [];
        $invalidData = [];
        $batchSize = 1000; // Adjust this based on your requirements

        // Loop through the data rows
        foreach ($data as $key => $value) {
            foreach ($value as $row) {
                $insert_data = [];
                $isValid = true; // Flag to check if data is valid

                foreach ($columnMapping as $column => $index) {
                    $cellValue = isset($row[$index]) ? trim($row[$index]) : null;

                    // Validation logic
                    if (in_array($column, ['FIRSTNAME', 'MIDDLENAME', 'LASTNAME'])) {
                        if (!preg_match('/^(?!.*\s{2,})([A-Za-z]+\s?)+$/', $cellValue) || strlen(preg_replace('/\s+/', '', $cellValue)) < 2) {
                            $isValid = false;
                            $invalidData[] = [
                                'file_upload_id' => $fileUploadId,
                                'filename' => $filename,
                                'column_name' => $column,
                                'invalid_data' => $cellValue,
                            ];
                        }
                    }

                    if ($column === 'EXTENSIONNAME' || !preg_match('/^[A-Za-z]*$/', $cellValue)) {
                        $isValid = false;
                        $invalidData[] = [
                            'file_upload_id' => $fileUploadId,
                            'filename' => $filename,
                            'column_name' => $column,
                            'invalid_data' => $cellValue,
                        ];
                    }

                    if ($column === 'BIRTHDATE') {
                        $dateFormat = '/^\d{4}-\d{2}-\d{2}$/';
                        if (!preg_match($dateFormat, $cellValue) || !strtotime($cellValue)) {
                            $isValid = false;
                            $invalidData[] = [
                                'file_upload_id' => $fileUploadId,
                                'filename' => $filename,
                                'column_name' => $column,
                                'invalid_data' => $cellValue,
                            ];
                        }
                    }

                    if (($column === 'BIRTHDATE' || $column === 'PROVINCE' || $column === 'NATIONALITY') || (is_null($cellValue) || empty($cellValue))) {
                        $isValid = false;
                        $invalidData[] = [
                            'file_upload_id' => $fileUploadId,
                            'filename' => $filename,
                            'column_name' => $column,
                            'invalid_data' => $cellValue,
                        ];
                    }

                    if ($column === 'MOBILENO') {
                        if (!preg_match('/^\d{11}$/', $cellValue)) {
                            $isValid = false;
                            $invalidData[] = [
                                'file_upload_id' => $fileUploadId,
                                'filename' => $filename,
                                'column_name' => $column,
                                'invalid_data' => $cellValue,
                            ];
                        }
                    }

                    if ($column === 'SEX') {
                        if (!in_array(strtoupper($cellValue), ['FEMALE', 'MALE'])) {
                            $isValid = false;
                            $invalidData[] = [
                                'file_upload_id' => $fileUploadId,
                                'filename' => $filename,
                                'column_name' => $column,
                                'invalid_data' => $cellValue,
                            ];
                        }
                    }

                    $insert_data[$column] = $cellValue;
                }

                // Add the file_upload_id to the row data
                $insert_data['FILE_ID'] = $fileUploadId;

                // Add the row to the batchData
                $batchData[] = $insert_data;

                // Insert the batch when the size limit is reached
                if (count($batchData) >= $batchSize) {
                    OnbintModel::insert($batchData);
                    $batchData = [];
                }
            }
        }

        // Insert any remaining data in batch
        if (!empty($batchData)) {
            OnbintModel::insert($batchData);
        }

        // Insert invalid data into the `InvalidDataModel` (or another table)
        if (!empty($invalidData)) {
            InvalidDataModel::insert($invalidData);
        }


        return response()->json([
            'message' => 'Data processing complete',
            'fileUploadId' => $fileUploadId
        ]);
    }

    public function insertExcelFileData($filename, $uploaded_by)
    {
        $fileUpload = FileUploadModel::create([
            'file_name' => $filename,
            'uploaded_by' => $uploaded_by,
            'updated_at' => now(),
            'created_at' => now()
        ]);

        // Return the ID of the newly inserted file
        return $fileUpload->id;
    }

    public function uploadedFiles(Request $request)
    {
        $id = $request->query('id');

        $uploadedFiles = OnbintModel::where('FILE_ID', $id)
            ->selectRaw('count(*) as uploaded_files')
            ->first();

        return response()->json($uploadedFiles);
    }


    public function getFiles()
    {
        $results = FileUploadModel::select(DB::raw('
         id,
         file_name,
         uploaded_by,
         updated_at,
         created_at'))
            ->get();
        $recordCount = $results->count();
        if ($recordCount === 0) {
            return response()->json([
                'count' => $recordCount
            ], 404);
        } else {
            // Return the records and the count
            return response()->json([
                'count' => $recordCount,
                'data' => $results
            ]);
        }
    }

    public function countUploadedFiles()
    {
        // In your Laravel controller

        $results = InvalidModel::select(DB::raw('
                id,
                filename,
                specialchar,
                null_values,
                below_2letters,
                unwanted_char,
                date_format,
                unwanted_spaces,
                invalid_address,
                updated_at,
                created_at'))
            ->get();
        $recordCount = $results->count();
        if ($recordCount === 0) {
            return response()->json([
                'count' => $recordCount
            ], 404);
        } else {
            // Return the records and the count
            return response()->json([
                'count' => $recordCount,
                'data' => $results
            ]);
        }
    }


    public function getOnbintInvalid()
    {
        $query = InvalidDataModel::select(InvalidDataModel::raw('id, filename, column_name, invalid_data, updated_at'))
            ->get();

        return response()->json($query);
    }

    public function getInvalidData(Request $request)
    {
        $id = $request->query('id');

        $data = InvalidModel::select(
            'id',
            'filename',
            'specialchar',
            'null_values',
            'below_2letters',
            'unwanted_char',
            'date_format',
            'updated_at',
            'created_at'
        )
            ->get();

        // Check if no data is returned
        if ($data->isEmpty()) {
            // Return a default structure with zero values
            return response()->json([
                'id'             => 0,
                'filename'       => '',
                'specialchar'    => 0,
                'null_values'    => 0,
                'below_2letters' => 0,
                'unwanted_char'  => 0,
                'date_format'    => 0,
                'updated_at'     => null,
                'created_at'     => null,
            ]);
        }

        // Modify the data to replace null with 0
        $modifiedData = $data->map(function ($item) {
            return [
                'id'             => $item->id,
                'filename'       => $item->filename,
                'specialchar'    => $item->specialchar ?? 0,
                'null_values'    => $item->null_values ?? 0,
                'below_2letters' => $item->below_2letters ?? 0,
                'unwanted_char'  => $item->unwanted_char ?? 0,
                'date_format'    => $item->date_format ?? 0,
                'updated_at'     => $item->updated_at,
                'created_at'     => $item->created_at,
            ];
        });

        // Return the modified data as a JSON response
        return response()->json($modifiedData);
    }

    public function checkDataMatches()
    {
        $chunkSize = 100;
        $nonMatchingRecords = [];
        $matchingRecords = [];

        // Chunk through the OnbintModel
        OnbintModel::chunk($chunkSize, function ($onbintModels) use (&$nonMatchingRecords, &$matchingRecords) {
            foreach ($onbintModels as $onbintModel) {
                // Convert relevant fields to uppercase for comparison
                $onbintFirstName = strtoupper($onbintModel->FIRSTNAME);
                $onbintLastName = strtoupper($onbintModel->LASTNAME);
                $onbintMiddleName = strtoupper($onbintModel->MIDDLENAME);
                $onbintBirthdate = strtoupper($onbintModel->BIRTHDATE);
                $onbintSex = strtoupper($onbintModel->SEX);

                // Check against vw_fims_rffa_interventions
                $farmer = RFFAInteventionsModel::where('rsbsa_no', $onbintModel->RSBSASYSTEMGENERATEDNUMBER)
                    ->whereRaw(
                        'UPPER(first_name) = ? AND UPPER(surname) = ? AND UPPER(middle_name) = ? AND UPPER(birthday) = ? AND UPPER(sex) = ?',
                        [$onbintFirstName, $onbintLastName, $onbintMiddleName, $onbintBirthdate, $onbintSex]
                    )
                    ->first();


                // if ($farmer) {
                //     // If a matching farmer is found, add to matching records
                //     $matchingRecords[] = [
                //         'onbint_model' => $onbintModel,
                //     ];
                // } else {
                //     // If no matching farmer is found, add to non-matching records
                //     $nonMatchingRecords[] = [
                //         'onbint_model' => $onbintModel,
                //     ];
                //     UnmatchedOnbintRecord::create([
                //         'RSBSASYSTEMGENERATEDNUMBER' => $onbintModel->RSBSASYSTEMGENERATEDNUMBER,
                //         'FIRSTNAME' => $onbintModel->FIRSTNAME,
                //         'MIDDLENAME' => $onbintModel->MIDDLENAME,
                //         'LASTNAME' => $onbintModel->LASTNAME,
                //         'EXTENSIONNAME' => $onbintModel->EXTENSIONNAME, // Assuming it could be null
                //         'IDNUMBER' => $onbintModel->IDNUMBER,
                //         'GOVTIDTYPE' => $onbintModel->GOVTIDTYPE,
                //         'STREETNO_PUROKNO' => $onbintModel->STREETNO_PUROKNO,
                //         'BARANGAY' => $onbintModel->BARANGAY,
                //         'CITYMUNICIPALITY' => $onbintModel->CITYMUNICIPALITY,
                //         'DISTRICT' => $onbintModel->DISTRICT,
                //         'PROVINCE' => $onbintModel->PROVINCE,
                //         'REGION' => $onbintModel->REGION,
                //         'BIRTHDATE' => $onbintModel->BIRTHDATE,
                //         'PLACEOFBIRTH' => $onbintModel->PLACEOFBIRTH,
                //         'MOBILENO' => $onbintModel->MOBILENO,
                //         'SEX' => $onbintModel->SEX,
                //         'NATIONALITY' => $onbintModel->NATIONALITY,
                //         'PROFESSION' => $onbintModel->PROFESSION,
                //         'SOURCEOFFUNDS' => $onbintModel->SOURCEOFFUNDS,
                //         'MOTHERMAIDENNAME' => $onbintModel->MOTHERMAIDENNAME,
                //         'NOOFFARMPARCEL' => $onbintModel->NOOFFARMPARCEL,
                //         'TFA' => $onbintModel->TFA,
                //         'remarks' => null,
                //         'created_at' => now(),
                //         'updated_at' => now(),
                //     ]);
                // }
            }
        });

        return response()->json([
            'matched_records' => $matchingRecords,
            'unmatched_records' => $nonMatchingRecords,
        ]);
    }

    public function getMatchData(Request $request)
    {
        $id = $request->query('id');
        // Step 1: Fetch matched records
        $matched = DB::table('vw_fims_rffa_interventions as v')
            ->select(
                'v.rsbsa_no',
                's.RSBSASYSTEMGENERATEDNUMBER',
                's.FIRSTNAME',
                's.MIDDLENAME',
                's.LASTNAME',
                's.EXTENSIONNAME',
                's.IDNUMBER',
                's.GOVTIDTYPE',
                's.STREETNO_PUROKNO',
                's.BARANGAY',
                's.CITYMUNICIPALITY',
                's.DISTRICT',
                's.PROVINCE',
                's.REGION',
                's.BIRTHDATE',
                's.PLACEOFBIRTH',
                's.MOBILENO',
                's.SEX',
                's.NATIONALITY',
                's.PROFESSION',
                's.SOURCEOFFUNDS',
                's.MOTHERMAIDENNAME',
                's.NOOFFARMPARCEL',
                's.TFA'
            )
            ->join('dp_onbint_staging as s', 's.RSBSASYSTEMGENERATEDNUMBER', '=', 'v.rsbsa_no')
            ->where('s.FILE_ID', $id)
            ->whereRaw('UPPER(s.FIRSTNAME) = UPPER(v.first_name)')
            ->whereRaw('UPPER(s.MIDDLENAME) = UPPER(v.middle_name)')
            ->whereRaw('UPPER(s.LASTNAME) = UPPER(v.surname)')
            ->whereRaw('s.SEX = CASE WHEN v.sex = "1" THEN "MALE" WHEN v.sex = "2" THEN "FEMALE" ELSE NULL END')
            ->whereRaw('s.BIRTHDATE = v.birthday')
            ->get();

        // Step 2: Fetch unmatched records from dp_onbint_staging
        $unmatchedRecords = DB::table('dp_onbint_staging as s')
            ->select(
                's.RSBSASYSTEMGENERATEDNUMBER',
                's.FIRSTNAME',
                's.MIDDLENAME',
                's.LASTNAME',
                's.EXTENSIONNAME',
                's.IDNUMBER',
                's.GOVTIDTYPE',
                's.STREETNO_PUROKNO',
                's.BARANGAY',
                's.CITYMUNICIPALITY',
                's.DISTRICT',
                's.PROVINCE',
                's.REGION',
                's.BIRTHDATE',
                's.PLACEOFBIRTH',
                's.MOBILENO',
                's.SEX',
                's.NATIONALITY',
                's.PROFESSION',
                's.SOURCEOFFUNDS',
                's.MOTHERMAIDENNAME',
                's.NOOFFARMPARCEL',
                's.TFA'
            )
            ->whereNotIn('s.RSBSASYSTEMGENERATEDNUMBER', function ($query) use ($id) {
                $query->select('v.rsbsa_no')
                    ->from('vw_fims_rffa_interventions as v')
                    ->join('dp_onbint_staging as s', 's.RSBSASYSTEMGENERATEDNUMBER', '=', 'v.rsbsa_no')
                    ->where('s.FILE_ID', $id)
                    ->whereRaw('UPPER(s.FIRSTNAME) = UPPER(v.first_name)')
                    ->whereRaw('UPPER(s.MIDDLENAME) = UPPER(v.middle_name)')
                    ->whereRaw('UPPER(s.LASTNAME) = UPPER(v.surname)')
                    ->whereRaw('s.SEX = CASE WHEN v.sex = "1" THEN "MALE" WHEN v.sex = "2" THEN "FEMALE" ELSE NULL END')
                    ->whereRaw('s.BIRTHDATE = v.birthday');
            })
            ->where('s.FILE_ID', $id)
            ->get();

        $recordCountUnmatched = $unmatchedRecords->count();
        $recordCount = $matched->count();

        // Check if no records were found
        if ($recordCount === 0) {
            return response()->json([
                'message' => 'No records found',
                'count' => $recordCount
            ], 404);
        }

        // Return the records and the count
        return response()->json([
            'count' => $recordCount,
            'countUnmatched' => $recordCountUnmatched,
            'data' => $matched,
            'unmatched' => $unmatchedRecords
        ]);
    }




    public function getDuplicateDataStaging()
    {

        $query = OnbintModel::whereIn('RSBSASYSTEMGENERATEDNUMBER', function ($subQuery) {
            $subQuery->select('RSBSASYSTEMGENERATEDNUMBER')
                ->from('dp_onbint_staging')
                ->whereNotNull('ID')
                ->groupBy('RSBSASYSTEMGENERATEDNUMBER')
                ->havingRaw('COUNT(*) > 1');
        })
            ->orderBy('RSBSASYSTEMGENERATEDNUMBER')
            ->orderBy('FIRSTNAME')
            ->orderBy('LASTNAME')
            ->orderBy('SEX')
            ->get();




        // Count the number of records
        $recordCount = $query->count();

        // Check if no records were found
        if ($recordCount === 0) {
            return response()->json([
                'message' => 'No records found',
                'count' => $recordCount
            ], 404);
        }

        // Return the records and the count
        return response()->json([
            'count' => $recordCount,
            'data' => $query
        ]);
    }

    public function findDuplicates(Request $request)
    {
        $id = $request->query('id');

        $stringComparison = new StringComparisonController();
        $duplicates = []; // Array to hold the duplicate results

        // Subquery to get potential duplicates based on selected fields
        $subquery = OnbintModel::select(
            'FILE_ID',
            'RSBSASYSTEMGENERATEDNUMBER',
            'FIRSTNAME',
            'MIDDLENAME',
            'LASTNAME',
            'SEX',
            'BIRTHDATE',
            'EXTENSIONNAME',
            OnbintModel::raw('COUNT(*) as duplicate_count')
        )
        ->where('FILE_ID', $id)
        ->groupBy(
            'FILE_ID',
            'RSBSASYSTEMGENERATEDNUMBER',
            'FIRSTNAME',
            'MIDDLENAME',
            'LASTNAME',
            'SEX',
            'BIRTHDATE',
            'EXTENSIONNAME'
        )
        ->havingRaw('COUNT(*) > 1')
        ->orderBy('duplicate_count', 'DESC');
        
        // Main query to include additional fields
        $query = OnbintModel::joinSub($subquery, 'duplicates', function ($join) {
            $join->on('dp_onbint_staging.RSBSASYSTEMGENERATEDNUMBER', '=', 'duplicates.RSBSASYSTEMGENERATEDNUMBER')
                ->on('dp_onbint_staging.FIRSTNAME', '=', 'duplicates.FIRSTNAME')
                ->on('dp_onbint_staging.MIDDLENAME', '=', 'duplicates.MIDDLENAME')
                ->on('dp_onbint_staging.LASTNAME', '=', 'duplicates.LASTNAME')
                ->on('dp_onbint_staging.SEX', '=', 'duplicates.SEX')
                ->on('dp_onbint_staging.BIRTHDATE', '=', 'duplicates.BIRTHDATE');
        })
        ->select(
            'dp_onbint_staging.RSBSASYSTEMGENERATEDNUMBER',
            'dp_onbint_staging.FIRSTNAME',
            'dp_onbint_staging.MIDDLENAME',
            'dp_onbint_staging.LASTNAME',
            'dp_onbint_staging.EXTENSIONNAME',
            'dp_onbint_staging.IDNUMBER',      // Ensure correct column name
            'dp_onbint_staging.GOVTIDTYPE',    // Correct the typo
            'dp_onbint_staging.SEX',
            'dp_onbint_staging.BIRTHDATE',
            'dp_onbint_staging.STREETNO_PUROKNO',
            'dp_onbint_staging.BARANGAY',
            'dp_onbint_staging.CITYMUNICIPALITY',
            'dp_onbint_staging.DISTRICT',
            'dp_onbint_staging.PROVINCE',
            'dp_onbint_staging.REGION',
            'dp_onbint_staging.PLACEOFBIRTH',
            'dp_onbint_staging.MOBILENO',
            'dp_onbint_staging.NATIONALITY',
            'dp_onbint_staging.PROFESSION',
            'dp_onbint_staging.SOURCEOFFUNDS',
            'dp_onbint_staging.MOTHERMAIDENNAME',
            'dp_onbint_staging.NOOFFARMPARCEL',
            'dp_onbint_staging.TFA',
            'duplicates.duplicate_count'
        )
        ->where('dp_onbint_staging.FILE_ID', $id)
        ->orderBy('duplicates.duplicate_count', 'DESC')
        ->chunk(1000, function ($results) use ($stringComparison, &$duplicates, $id) {
            foreach ($results as $row) {
                // Compare names using Jaro-Winkler algorithm
                $records = OnbintModel::where('dp_onbint_staging.RSBSASYSTEMGENERATEDNUMBER', $row->RSBSASYSTEMGENERATEDNUMBER)
                ->whereRaw('TRIM(LOWER(dp_onbint_staging.FIRSTNAME)) = ?', [trim(strtolower($row->FIRSTNAME))])
                ->whereRaw('TRIM(LOWER(dp_onbint_staging.LASTNAME)) = ?', [trim(strtolower($row->LASTNAME))])
                ->whereRaw('TRIM(LOWER(dp_onbint_staging.MIDDLENAME)) = ?', [trim(strtolower($row->MIDDLENAME))])
                ->where('dp_onbint_staging.SEX', $row->SEX)
                ->where('dp_onbint_staging.BIRTHDATE', $row->BIRTHDATE)
                ->get();
                

                    if ($records->count() > 1) {
                        $recordA = $records->shift()->toArray(); // The first record
                        $similarRecords = [];
                        foreach ($records as $recordB) {
                            $similarity = $stringComparison->getJaroWinkler(
                                $recordA['FIRSTNAME'] . ' ' . $recordA['MIDDLENAME'] . ' ' . $recordA['LASTNAME'],
                                $recordB['FIRSTNAME'] . ' ' . $recordB['MIDDLENAME'] . ' ' . $recordB['LASTNAME']
                            );


                            // Set a threshold for similarity (e.g., 0.50)
                            if ($similarity > 0.85) {
                                $similarRecords[] = [
                                    'FIRSTNAME' => $recordB->FIRSTNAME,
                                    'MIDDLENAME' => $recordB->MIDDLENAME,
                                    'LASTNAME' => $recordB->LASTNAME,
                                    'EXTENSIONNAME' => $recordB->EXTENSIONNAME,
                                    'IDNUMBER' => $recordB->IDNUMBER,
                                    'GOVTIDTYPE' => $recordB->GOVTIDTYPE,
                                    'SEX' => $recordB->SEX,
                                    'BIRTHDATE' => $recordB->BIRTHDATE,
                                    'STREETNO_PUROKNO' => $recordB->STREETNO_PUROKNO,
                                    'BARANGAY' => $recordB->BARANGAY,
                                    'CITYMUNICIPALITY' => $recordB->CITYMUNICIPALITY,
                                    'DISTRICT' => $recordB->DISTRICT,
                                    'PROVINCE' => $recordB->PROVINCE,
                                    'REGION' => $recordB->REGION,
                                    'TFA' => $recordB->TFA,
                                    'PLACEOFBIRTH' => $recordB->PLACEOFBIRTH,
                                    'MOBILENO' => $recordB->MOBILENO,
                                    'NATIONALITY' => $recordB->NATIONALITY,
                                    'PROFESSION' => $recordB->PROFESSION,
                                    'SOURCEOFFUNDS' => $recordB->SOURCEOFFUNDS,
                                    'MOTHERMAIDENNAME' => $recordB->MOTHERMAIDENNAME,
                                    'NOOFFARMPARCEL' => $recordB->NOOFFARMPARCEL,
                                    'similarity' => $similarity
                                ];
                            }
                        }

                        if (!empty($similarRecords)) {
                            $duplicates[] = [
                                'RSBSASYSTEMGENERATEDNUMBER' => $recordA['RSBSASYSTEMGENERATEDNUMBER'],
                                'FIRSTNAME' => $recordA['FIRSTNAME'],
                                'MIDDLENAME' => $recordA['MIDDLENAME'],
                                'LASTNAME' => $recordA['LASTNAME'],
                                'EXTENSIONNAME' => $recordA['EXTENSIONNAME'],
                                'IDNUMBER' => $recordA['IDNUMBER'],
                                'GOVTIDTYPE' => $recordA['GOVTIDTYPE'],
                                'SEX' => $recordA['SEX'],
                                'BIRTHDATE' => $recordA['BIRTHDATE'],
                                'STREETNO_PUROKNO' => $recordA['STREETNO_PUROKNO'],
                                'BARANGAY' => $recordA['BARANGAY'],
                                'CITYMUNICIPALITY' => $recordA['CITYMUNICIPALITY'],
                                'DISTRICT' => $recordA['DISTRICT'],
                                'PROVINCE' => $recordA['PROVINCE'],
                                'REGION' => $recordA['REGION'],
                                'TFA' => $recordA['TFA'],
                                'PLACEOFBIRTH' => $recordA['PLACEOFBIRTH'],
                                'MOBILENO' => $recordA['MOBILENO'],
                                'NATIONALITY' => $recordA['NATIONALITY'],
                                'PROFESSION' => $recordA['PROFESSION'],
                                'SOURCEOFFUNDS' => $recordA['SOURCEOFFUNDS'],
                                'MOTHERMAIDENNAME' => $recordA['MOTHERMAIDENNAME'],
                                'NOOFFARMPARCEL' => $recordA['NOOFFARMPARCEL'],
                                'similarity' => $similarity,
                                'duplicates' => $similarRecords
                            ];
                        }
                    }
                }
            });
            if ($request->has('export')) {
               
                return $this->exportDedup($duplicates);
            }

        return response()->json($duplicates); // Return the duplicates as a JSON response
    }

    public function checkValidation(Request $request)
    {
        $invalidData = [];
        $file_name = '';
        $id = $request->query('id');


        // Query the data from the OnbintModel
        $records = OnbintModel::select(
            'dp_onbint_staging.ID',
            'FILE_ID',
            'file_uploaded.file_name',
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
            'TFA'
        )
            ->leftJoin('file_uploaded', 'file_uploaded.id', '=', 'dp_onbint_staging.FILE_ID') // Corrected this line
            ->where('file_uploaded.id', $id)
            ->get();


        // Loop through each record and validate the fields
        foreach ($records as $record) {
            foreach ($record->toArray() as $column => $cellValue) {
                $isValid = true;
                $file_name = $record->file_name; // Or you can log this instead
                $file_id = $id; // Or you can log this instead

                // Validation for FIRSTNAME, MIDDLENAME, LASTNAME
                if (in_array($column, ['FIRSTNAME', 'MIDDLENAME', 'LASTNAME'])) {
                    // Allow letters and spaces, must contain at least 2 letters in total
                    if (!preg_match('/^(?!.*\s{2,})([A-Za-z]+\s?)+$/', $cellValue) || strlen(preg_replace('/\s+/', '', $cellValue)) < 2) {
                        $isValid = false;
                    }
                }

                // Validation for EXTENSIONNAME (letters only)
                if ($column === 'EXTENSIONNAME' && !preg_match('/^[A-Za-z]*$/', $cellValue)) {
                    $isValid = false;
                }

                // Validation for BIRTHDATE (format yyyy-mm-dd)
                if ($column === 'BIRTHDATE') {
                    $dateFormat = '/^\d{4}-\d{2}-\d{2}$/';
                    if (!preg_match($dateFormat, $cellValue) || !strtotime($cellValue)) {
                        $isValid = false;
                    }
                }

                // Validation for required fields (BIRTHDATE, PROVINCE, NATIONALITY)
                if (in_array($column, ['BIRTHDATE', 'PROVINCE', 'NATIONALITY']) && (is_null($cellValue) || empty($cellValue))) {
                    $isValid = false;
                }

                // Validation for MOBILENO (11 digits)
                if ($column === 'MOBILENO' && !preg_match('/^\d{11}$/', $cellValue)) {
                    $isValid = false;
                }

                // Validation for SEX (FEMALE or MALE)
                if ($column === 'SEX' && !in_array(strtoupper($cellValue), ['FEMALE', 'MALE'])) {
                    $isValid = false;
                }


                // Collect invalid data
                if (!$isValid) {
                    $invalidData[] = [
                        'file_id'     => $file_id,
                        'filename'     => $file_name,
                        'column_name'  => $column,
                        'invalid_data' => $cellValue,
                        // 'ID'           => $record->ID, // Add the ID to identify the record
                    ];
                }
            }
        }

        // Insert invalid data into InvalidDataModel
        if (!empty($invalidData)) {
            InvalidDataModel::insert($invalidData);
        }

        // Count invalid data
        $invalid_data_count = $this->onbint_countnull($file_id, $file_name);

        return $invalid_data_count;
    }

    public function exportUnmatch(Request $request)
    {
        // Step 1: Fetch matched records
        $id = $request->query('id');

        $unmatchedData = OnbintModel::select(
            'RSBSASYSTEMGENERATEDNUMBER',
            'FILE_ID',
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
            'TFA'
        )
            ->whereNotIn('RSBSASYSTEMGENERATEDNUMBER', function ($query) use ($id) {
                $query->select('v.rsbsa_no')
                    ->from('vw_fims_rffa_interventions as v')
                    ->join('dp_onbint_staging as s', 's.RSBSASYSTEMGENERATEDNUMBER', '=', 'v.rsbsa_no')
                    ->where('s.FILE_ID', $id)
                    ->whereRaw('UPPER(s.FIRSTNAME) = UPPER(v.first_name)')
                    ->whereRaw('UPPER(s.MIDDLENAME) = UPPER(v.middle_name)')
                    ->whereRaw('UPPER(s.LASTNAME) = UPPER(v.surname)')
                    ->whereRaw('s.SEX = CASE WHEN v.sex = "1" THEN "MALE" WHEN v.sex = "2" THEN "FEMALE" ELSE NULL END')
                    ->whereRaw('s.BIRTHDATE = v.birthday');
            })
            ->where('FILE_ID', $id);

        // Fetch the data
        $data = $unmatchedData->get();

        // Pass data to export method
        return $this->export($data);
    }

    public function export($data)
    {
        $templatePath = public_path('templates/rffa4_to_staging_match.xlsx');

        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'Template file not found.'], 404);
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();

        $row = 2;
        $index = 1;
        foreach ($data as $record) {
            $full_name = $record->FIRSTNAME." ".$record->MIDDLENAME." ".$record->LASTNAME;
            $sheet->setCellValue('A' . $row, $index);
            $sheet->setCellValue('B' . $row, "");
            $sheet->setCellValue('C' . $row, "");
            $sheet->setCellValue('D' . $row, "");
            $sheet->setCellValue('E' . $row, $record->RSBSASYSTEMGENERATEDNUMBER);
            $sheet->setCellValue('F' . $row, $record->FIRSTNAME);
            $sheet->setCellValue('G' . $row, $record->MIDDLENAME);
            $sheet->setCellValue('H' . $row, $record->LASTNAME);
            $sheet->setCellValue('I' . $row, $record->EXTENSIONNAME);
            $sheet->setCellValue('J' . $row, $record->IDNUMBER);
            $sheet->setCellValue('K' . $row, $record->GOVTIDTYPE);
            $sheet->setCellValue('L' . $row, $record->STREETNO_PUROKNO);
            $sheet->setCellValue('M' . $row, $record->BARANGAY);
            $sheet->setCellValue('N' . $row, $record->CITYMUNICIPALITY);
            $sheet->setCellValue('O' . $row, $record->PROVINCE);
            $sheet->setCellValue('P' . $row, $record->REGION);
            $sheet->setCellValue('Q' . $row, $record->PLACEOFBIRTH);
            $sheet->setCellValue('R' . $row, $record->MOBILENO);
            $sheet->setCellValue('S' . $row, $record->SEX);
            $sheet->setCellValue('T' . $row, $record->NATIONALITY);
            $sheet->setCellValue('U' . $row, $record->PROFESSION);
            $sheet->setCellValue('V' . $row, $record->MOTHERMAIDENNAME);
            $sheet->setCellValue('W' . $row, "Sheet1");
            $sheet->setCellValue('X' . $row, "row #");
            $sheet->setCellValue('Y' . $row, "filename");
            $sheet->setCellValue('Z' . $row, $record->created_at);

            $sheet->setCellValue('AA' . $row, $record->NOOFFARMPARCEL);
            $sheet->setCellValue('AB' . $row, $record->TFA);
            $sheet->setCellValue('AC' . $row, $record->BIRTHDATE);
            $sheet->setCellValue('AC' . $row, $full_name);
            $row++; 
            $index++;
        }

        $fileName = 'unmatched.xlsx';

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        try {
            $writer->save($fileName);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return response()->json(['error' => 'Error saving file: ' . $e->getMessage()], 500);
        }

        // Download the file and delete it after sending
        return response()->download($fileName)->deleteFileAfterSend(true);

        

    }

    public function exportDedup($data) {
        $templatePath = public_path('templates/rffa4_to_staging_match.xlsx');
    
        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'Template file not found.'], 404);
        }
    
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
    
        $row = 2;
        $index = 1;
        
        foreach ($data as $record) {
            // Concatenate the full name
            $full_name = $record['FIRSTNAME'] . " " . $record['MIDDLENAME'] . " " . $record['LASTNAME'];
            
            // Set cell values for unique records
            $sheet->setCellValue('A' . $row, $index); // Index column
            $sheet->setCellValue('B' . $row, $record['similarity']); // Blank column
            $sheet->setCellValue('C' . $row, ""); // Blank column
            $sheet->setCellValue('D' . $row, ""); // Blank column
            $sheet->setCellValue('E' . $row, $record['RSBSASYSTEMGENERATEDNUMBER']);
            $sheet->setCellValue('F' . $row, $record['FIRSTNAME']);
            $sheet->setCellValue('G' . $row, $record['MIDDLENAME']);
            $sheet->setCellValue('H' . $row, $record['LASTNAME']);
            $sheet->setCellValue('I' . $row, $record['EXTENSIONNAME']);
            $sheet->setCellValue('J' . $row, $record['IDNUMBER']);
            $sheet->setCellValue('K' . $row, $record['GOVTIDTYPE']);
            $sheet->setCellValue('L' . $row, $record['STREETNO_PUROKNO']);
            $sheet->setCellValue('M' . $row, $record['BARANGAY']);
            $sheet->setCellValue('N' . $row, $record['CITYMUNICIPALITY']);
            $sheet->setCellValue('O' . $row, $record['PROVINCE']);
            $sheet->setCellValue('P' . $row, $record['REGION']);
            $sheet->setCellValue('Q' . $row, $record['PLACEOFBIRTH']);
            $sheet->setCellValue('R' . $row, $record['MOBILENO']);
            $sheet->setCellValue('S' . $row, $record['SEX']);
            $sheet->setCellValue('T' . $row, $record['NATIONALITY']);
            $sheet->setCellValue('U' . $row, $record['PROFESSION']);
            $sheet->setCellValue('V' . $row, $record['MOTHERMAIDENNAME']);
            $sheet->setCellValue('W' . $row, "Sheet1"); // Example value
            $sheet->setCellValue('X' . $row, "row #");  // Example value
            $sheet->setCellValue('Y' . $row, "filename");  // Example value
            $sheet->setCellValue('Z' . $row, date('YYYY-mm-dd'));
            $sheet->setCellValue('AA' . $row, $record['NOOFFARMPARCEL']);
            $sheet->setCellValue('AB' . $row, $record['TFA']);
            $sheet->setCellValue('AC' . $row, $record['BIRTHDATE']);
            $sheet->setCellValue('AD' . $row, $full_name); // Store full name in a separate column
            
            // Increment row and index for the next record
            $row++;
            $index++;
        }
    
        $fileName = 'rffa4_to_staging_dedup.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    
        try {
            $writer->save($fileName);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return response()->json(['error' => 'Error saving file: ' . $e->getMessage()], 500);
        }
    
        // Download the file and delete it after sending
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
    


    
}
