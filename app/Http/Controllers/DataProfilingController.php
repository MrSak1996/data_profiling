<?php

namespace App\Http\Controllers;

use App\Http\Controllers\StringComparisonController;

use Facade\FlareClient\Http\Exceptions\InvalidData;
use Illuminate\Http\Request;
use App\Models\OnbintModel;
use App\Models\RFFA_INTERVENTION_MODEL;
use App\Models\InvalidDataModel;
use App\Models\InvalidModel;
use App\Models\UnmatchedOnbintRecord;
use App\Models\MatchedOnbintRecord;
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

class DataProfilingController extends Controller
{
    public function __construct()
    {
        set_time_limit(8000000);
    }

    public function getOnbintStaging(Request $request)
    {
        $page = $request->query('page');
        $itemsPerPage = $request->query('itemsPerPage', 500);
        $query = OnbintModel::select(OnbintModel::raw(
            'ID, 
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
            ->get();
        // $data = $query->paginate($itemsPerPage, ['*'], 'page', $page);
        return response()->json($query);
    }

    public function onbint_countnull($filename)
    {
        // Fetch the aggregated results
        $results = InvalidDataModel::selectRaw("
                SUM(CASE WHEN invalid_data REGEXP '[^a-zA-Z0-9 ]' THEN 1 ELSE 0 END) AS special_char_count,
                SUM(CASE WHEN LENGTH(invalid_data) < 2 THEN 1 ELSE 0 END) AS less_than_2_chars_count,
                SUM(CASE WHEN invalid_data REGEXP '[a-zA-Z]' AND invalid_data REGEXP '[0-9]' THEN 1 ELSE 0 END) AS alphanumeric_count,
                COUNT(CASE WHEN invalid_data IS NULL THEN 1 ELSE NULL END) AS null_count,
                SUM(CASE WHEN column_name = 'BIRTHDATE' AND invalid_data NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$' THEN 1 ELSE 0 END) AS wrong_date_format_count
            ")

            ->where('filename', $filename)
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

        $columnMapping = [
            'RSBSASYSTEMGENERATEDNUMBER' => 0,
            'FIRSTNAME' => 1,
            'MIDDLENAME' => 2,
            'LASTNAME' => 3,
            'EXTENSIONNAME' => 4,
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

        $batchSize = 1000; // Number of rows per batch
        $batchData = [];
        $invalidData = []; // Array to store invalid data records

        foreach ($data as $key => $value) {
            foreach ($value as $row) {
                $insert_data = [];
                $isValid = true; // Flag to check if data is valid

                foreach ($columnMapping as $column => $index) {
                    $cellValue = isset($row[$index]) ? trim($row[$index]) : null;

                    // Validation for specific fields
                    if (in_array($column, ['FIRSTNAME', 'MIDDLENAME', 'LASTNAME'])) {
                        // Allow letters and spaces, must contain at least 2 letters in total
                        if (!preg_match('/^(?!.*\s{2,})([A-Za-z]+\s?)+$/', $cellValue) || strlen(preg_replace('/\s+/', '', $cellValue)) < 2) {
                            $isValid = false;
                            $invalidData[] = [
                                'filename' => $filename,
                                'column_name' => $column,
                                'invalid_data' => $cellValue,
                            ];
                        }
                    }

                    if ($column === 'EXTENSIONNAME' || !preg_match('/^[A-Za-z]*$/', $cellValue)) {  // Allow only letters
                        $isValid = false;
                        $invalidData[] = [
                            'filename' => $filename,
                            'column_name' => $column,
                            'invalid_data' => $cellValue,
                        ];
                    }

                    if ($column === 'BIRTHDATE') {
                        // Validate format yyyy-mm-dd
                        $dateFormat = '/^\d{4}-\d{2}-\d{2}$/';
                        if (!preg_match($dateFormat, $cellValue) || !strtotime($cellValue)) {
                            $isValid = false;
                            $invalidData[] = [
                                'filename' => $filename,
                                'column_name' => $column,
                                'invalid_data' => $cellValue,
                            ];
                        }
                    }

                    if (($column === 'BIRTHDATE' || $column === 'PROVINCE' || $column === 'NATIONALITY') || (is_null($cellValue) || empty($cellValue))) {
                        $isValid = false;
                        $invalidData[] = [
                            'filename' => $filename,
                            'column_name' => $column,
                            'invalid_data' => $cellValue,
                        ];
                    }

                    if ($column === 'MOBILENO') {
                        // Validate that mobile number is 11 digits long and contains only numbers
                        if (!preg_match('/^\d{11}$/', $cellValue)) {
                            $isValid = false;
                            $invalidData[] = [
                                'filename' => $filename,
                                'column_name' => $column,
                                'invalid_data' => $cellValue,
                            ];
                        }
                    }

                    if ($column === 'SEX') {
                        // Validate that sex is either "FEMALE" or "MALE"
                        if (!in_array(strtoupper($cellValue), ['FEMALE', 'MALE'])) {
                            $isValid = false;
                            $invalidData[] = [
                                'filename' => $filename,
                                'column_name' => $column,
                                'invalid_data' => $cellValue,
                            ];
                        }
                    }

                    $insert_data[$column] = $cellValue;
                }

                // Regardless of validity, add the row to the batchData for insertion
                $batchData[] = $insert_data;

                if (count($batchData) >= $batchSize) {
                    OnbintModel::insert($batchData);
                    $batchData = [];
                }
            }
        }

        // Insert remaining data
        if (!empty($batchData)) {
            OnbintModel::insert($batchData);
        }

        // Insert invalid data into the InvalidDataModel
        if (!empty($invalidData)) {
            InvalidDataModel::insert($invalidData);
        }

        $invalid_data_count = $this->onbint_countnull($filename);

        return response()->json([
            'message' => 'Data processing complete',
            'invalid_data_count' => $invalid_data_count,
        ]);
    }


    public function uploadedFiles()
    {
        return response()->json(OnbintModel::select(OnbintModel::raw('count(*) as uploaded_files'))
            ->get());
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
                'message' => 'No records found',
                'count' => $recordCount
            ], 404);
        }

        // Return the records and the count
        return response()->json([
            'count' => $recordCount,
            'data' => $results
        ]);
    }



    public function getInvalidData()
    {
        return response()->json(InvalidModel::select(InvalidModel::raw('id, filename, specialchar, null_values, below_2letters, unwanted_char, date_format,updated_at, created_at'))
            ->get());
    }

    public function checkDataMatches()
    {
        $chunkSize = 100;
        $nonMatchingRecords = [];
        $matchingRecords = [];


        OnbintModel::with('farmers')->chunk($chunkSize, function ($onbintModels) use (&$nonMatchingRecords, &$matchingRecords) {

            for ($i = 0; $i < count($onbintModels); $i++) {
                $onbintModel = $onbintModels[$i];

                $farmers = $onbintModel->farmers;

                $isMatching = false;

                for ($j = 0; $j < count($farmers); $j++) {
                    $farmer = $farmers[$j];

                    // Convert relevant fields to uppercase for comparison
                    $onbintFirstName = strtoupper($onbintModel->FIRSTNAME);
                    $onbintLastName = strtoupper($onbintModel->LASTNAME);
                    $onbintMiddleName = strtoupper($onbintModel->MIDDLENAME);
                    $onbintBirthdate = strtoupper($onbintModel->BIRTHDATE);
                    $onbintSex = strtoupper($onbintModel->SEX);

                    $farmerFirstName = strtoupper($farmer->first_name);
                    $farmerLastName = strtoupper($farmer->surname);
                    $farmerMiddleName = strtoupper($farmer->middle_name);
                    $farmerBirthdate = strtoupper($farmer->birthday);
                    $farmerSex = strtoupper($farmer->sex);


                    // Check if the rsbsa_no matches and all other fields also match
                    if (
                        $onbintModel->RSBSASYSTEMGENERATEDNUMBER === $farmer->rsbsa_no &&
                        $onbintFirstName === $farmerFirstName &&
                        $onbintLastName === $farmerLastName &&
                        $onbintMiddleName === $farmerMiddleName &&
                        $onbintBirthdate === $farmerBirthdate &&
                        $onbintSex === $farmerSex
                    ) {
                        // If all fields match, add to matching records and mark as matching
                        $matchingRecords[] = [
                            'onbint_model' => $onbintModel,
                        ];

                        //Insert match record
                        MatchedOnbintRecord::create([
                            'RSBSASYSTEMGENERATEDNUMBER' => $onbintModel->RSBSASYSTEMGENERATEDNUMBER,
                            'FIRSTNAME' => $onbintModel->FIRSTNAME,
                            'MIDDLENAME' => $onbintModel->MIDDLENAME,
                            'LASTNAME' => $onbintModel->LASTNAME,
                            'EXTENSIONNAME' => $onbintModel->EXTENSIONNAME, // Assuming it could be null
                            'IDNUMBER' => $onbintModel->IDNUMBER,
                            'GOVTIDTYPE' => $onbintModel->GOVTIDTYPE,
                            'STREETNO_PUROKNO' => $onbintModel->STREETNO_PUROKNO,
                            'BARANGAY' => $onbintModel->BARANGAY,
                            'CITYMUNICIPALITY' => $onbintModel->CITYMUNICIPALITY,
                            'DISTRICT' => $onbintModel->DISTRICT,
                            'PROVINCE' => $onbintModel->PROVINCE,
                            'REGION' => $onbintModel->REGION,
                            'BIRTHDATE' => $onbintModel->BIRTHDATE,
                            'PLACEOFBIRTH' => $onbintModel->PLACEOFBIRTH,
                            'MOBILENO' => $onbintModel->MOBILENO,
                            'SEX' => $onbintModel->SEX,
                            'NATIONALITY' => $onbintModel->NATIONALITY,
                            'PROFESSION' => $onbintModel->PROFESSION,
                            'SOURCEOFFUNDS' => $onbintModel->SOURCEOFFUNDS,
                            'MOTHERMAIDENNAME' => $onbintModel->MOTHERMAIDENNAME,
                            'NOOFFARMPARCEL' => $onbintModel->NOOFFARMPARCEL,
                            'TFA' => $onbintModel->TFA,
                            'remarks' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $isMatching = true;
                        break; // No need to check further farmers for this onbintModel
                    }
                }

                if (!$isMatching) {
                    $nonMatchingRecords[] = [
                        'onbint_model' => $onbintModel,
                    ];

                    UnmatchedOnbintRecord::create([
                        'RSBSASYSTEMGENERATEDNUMBER' => $onbintModel->RSBSASYSTEMGENERATEDNUMBER,
                        'FIRSTNAME' => $onbintModel->FIRSTNAME,
                        'MIDDLENAME' => $onbintModel->MIDDLENAME,
                        'LASTNAME' => $onbintModel->LASTNAME,
                        'EXTENSIONNAME' => $onbintModel->EXTENSIONNAME, // Assuming it could be null
                        'IDNUMBER' => $onbintModel->IDNUMBER,
                        'GOVTIDTYPE' => $onbintModel->GOVTIDTYPE,
                        'STREETNO_PUROKNO' => $onbintModel->STREETNO_PUROKNO,
                        'BARANGAY' => $onbintModel->BARANGAY,
                        'CITYMUNICIPALITY' => $onbintModel->CITYMUNICIPALITY,
                        'DISTRICT' => $onbintModel->DISTRICT,
                        'PROVINCE' => $onbintModel->PROVINCE,
                        'REGION' => $onbintModel->REGION,
                        'BIRTHDATE' => $onbintModel->BIRTHDATE,
                        'PLACEOFBIRTH' => $onbintModel->PLACEOFBIRTH,
                        'MOBILENO' => $onbintModel->MOBILENO,
                        'SEX' => $onbintModel->SEX,
                        'NATIONALITY' => $onbintModel->NATIONALITY,
                        'PROFESSION' => $onbintModel->PROFESSION,
                        'SOURCEOFFUNDS' => $onbintModel->SOURCEOFFUNDS,
                        'MOTHERMAIDENNAME' => $onbintModel->MOTHERMAIDENNAME,
                        'NOOFFARMPARCEL' => $onbintModel->NOOFFARMPARCEL,
                        'TFA' => $onbintModel->TFA,
                        'remarks' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
        return response()->json([
            'matched_records' => $matchingRecords,
            'unmatched_records' => $nonMatchingRecords,
        ]);
    }

    public function getUnmatchData()
    {
        $query = UnmatchedOnbintRecord::select(
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
            'TFA'
        )->get();

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

    public function getMatchData()
    {
        // Fetch the unmatched records
        $query = MatchedOnbintRecord::select(
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
            'TFA'
        )->get();

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

    public function exportUnmatch()
    {
        $unmatchedData = UnmatchedOnbintRecord::all(); // Fetch your data

        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Define the headers
        $headers = [
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
            'TOTAL FARM AREA'
        ];

        // Define styles
        $redFontStyle = [
            'font' => [
                'color' => ['rgb' => 'FF0000']
            ]
        ];

        $specialCharPattern = '/[^a-zA-Z0-9]/';
        $dateFormat = '/^\d{4}-\d{2}-\d{2}$/'; // YYYY-MM-DD format for birthdate

        // Add headers to the first row
        $columnIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnIndex . '1', $header);
            $sheet->getColumnDimension($columnIndex)->setAutoSize(true);
            $columnIndex++;
        }

        // Separate invalid and valid data
        $invalidData = [];
        $validData = [];

        foreach ($unmatchedData as $data) {
            $isValid = true;
            $invalidColumns = [];

            // Validate FIRSTNAME, MIDDLENAME, LASTNAME: Allow letters and spaces, at least 2 letters
            foreach (['FIRSTNAME', 'MIDDLENAME', 'LASTNAME'] as $column) {
                $cellValue = $data->$column;
                if (!preg_match('/^(?!.*\s{2,})([A-Za-z]+\s?)+$/', $cellValue) || strlen(preg_replace('/\s+/', '', $cellValue)) < 2) {
                    $isValid = false;
                    $invalidColumns[] = $column;
                }
            }

            // Validate EXTENSIONNAME: Allow only letters
            $cellValue = $data->EXTENSIONNAME;
            if (!preg_match('/^[A-Za-z]*$/', $cellValue)) {
                $isValid = false;
                $invalidColumns[] = 'EXTENSIONNAME';
            }

            // Validate BIRTHDATE: Must be in YYYY-MM-DD format
            $cellValue = $data->BIRTHDATE;
            if (!preg_match($dateFormat, $cellValue) || !strtotime($cellValue)) {
                $isValid = false;
                $invalidColumns[] = 'BIRTHDATE';
            }

            // Validate MOBILENO: Must be 11 digits
            $cellValue = $data->MOBILENO;
            if (!preg_match('/^\d{11}$/', $cellValue)) {
                $isValid = false;
                $invalidColumns[] = 'MOBILENO';
            }

            // Validate SEX: Must be either 'FEMALE' or 'MALE'
            $cellValue = $data->SEX;
            if (!in_array(strtoupper($cellValue), ['FEMALE', 'MALE'])) {
                $isValid = false;
                $invalidColumns[] = 'SEX';
            }

            // Store data in the appropriate array
            if ($isValid) {
                $validData[] = $data;
            } else {
                $invalidData[] = $data;
            }
        }

        // Merge invalid and valid data, ensuring invalid entries are at the top
        $mergedData = array_merge($invalidData, $validData);

        // Add the data rows
        $rowIndex = 2;
        foreach ($mergedData as $data) {
            $rowValues = [
                'A' => $data->RSBSASYSTEMGENERATEDNUMBER,
                'B' => $data->FIRSTNAME,
                'C' => $data->MIDDLENAME,
                'D' => $data->LASTNAME,
                'E' => $data->EXTENSIONNAME,
                'F' => $data->IDNUMBER,
                'G' => $data->GOVTIDTYPE,
                'H' => $data->STREETNO_PUROKNO,
                'I' => $data->BARANGAY,
                'J' => $data->CITYMUNICIPALITY,
                'K' => $data->DISTRICT,
                'L' => $data->PROVINCE,
                'M' => $data->REGION,
                'N' => $data->BIRTHDATE,
                'O' => $data->PLACEOFBIRTH,
                'P' => $data->MOBILENO,
                'Q' => $data->SEX,
                'R' => $data->NATIONALITY,
                'S' => $data->PROFESSION,
                'T' => $data->SOURCEOFFUNDS,
                'U' => $data->MOTHERMAIDENNAME,
                'V' => $data->NOOFFARMPARCEL,
                'W' => $data->TFA
            ];

            foreach ($rowValues as $column => $value) {
                $sheet->setCellValue($column . $rowIndex, $value);

                // Apply red font to invalid data
                if (
                    (in_array($column, ['B', 'C', 'D']) && !preg_match('/^(?!.*\s{2,})([A-Za-z]+\s?)+$/', $value)) ||
                    ($column == 'E' && !preg_match('/^[A-Za-z]*$/', $value)) ||
                    ($column == 'N' && (!preg_match($dateFormat, $value) || !strtotime($value))) ||
                    ($column == 'P' && !preg_match('/^\d{11}$/', $value)) ||
                    ($column == 'Q' && !in_array(strtoupper($value), ['FEMALE', 'MALE']))
                ) {
                    $sheet->getStyle($column . $rowIndex)->applyFromArray($redFontStyle);
                }
            }

            $rowIndex++;
        }

        // Output the spreadsheet as a downloadable Excel file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'unmatched_data.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
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

    public function findDuplicates()
{
    $stringComparison = new StringComparisonController();
    $duplicates = []; // Array to hold the duplicate results

    // Subquery to get potential duplicates based on selected fields
    $subquery = OnbintModel::select(
            'RSBSASYSTEMGENERATEDNUMBER',
            'FIRSTNAME',
            'MIDDLENAME',
            'LASTNAME',
            'SEX',
            'BIRTHDATE',
            OnbintModel::raw('COUNT(*) as duplicate_count')
        )
        ->groupBy(
            'RSBSASYSTEMGENERATEDNUMBER',
            'FIRSTNAME',
            'MIDDLENAME',
            'LASTNAME',
            'SEX',
            'BIRTHDATE'
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
            'dp_onbint_staging.SEX',
            'dp_onbint_staging.BIRTHDATE',
            'dp_onbint_staging.STREETNO_PUROKNO',
            'dp_onbint_staging.BARANGAY',
            'dp_onbint_staging.CITYMUNICIPALITY',
            'dp_onbint_staging.DISTRICT',
            'dp_onbint_staging.PROVINCE',
            'dp_onbint_staging.REGION',
            'duplicates.duplicate_count'
        )
        ->orderBy('duplicates.duplicate_count', 'DESC')
        ->chunk(1000, function ($results) use ($stringComparison, &$duplicates) {
            foreach ($results as $row) {
                // Compare names using Jaro-Winkler algorithm
                $records = OnbintModel::where('dp_onbint_staging.RSBSASYSTEMGENERATEDNUMBER', $row->RSBSASYSTEMGENERATEDNUMBER)
                    ->whereRaw('TRIM(LOWER(dp_onbint_staging.FIRSTNAME)) = ?', [trim(strtolower($row->FIRSTNAME))])
                    ->whereRaw('TRIM(LOWER(dp_onbint_staging.LASTNAME)) = ?', [trim(strtolower($row->LASTNAME))])
                    ->whereRaw('TRIM(LOWER(dp_onbint_staging.MIDDLENAME)) = ?', [trim(strtolower($row->MIDDLENAME))])
                    ->where('dp_onbint_staging.SEX', $row->SEX)
                    ->where('dp_onbint_staging.BIRTHDATE', $row->BIRTHDATE)
                    ->get(); // Retrieve records with similar attributes

                if ($records->count() > 1) {
                    $recordA = $records->shift()->toArray(); // The first record
                    $similarRecords = [];

                    foreach ($records as $recordB) {
                        $similarity = $stringComparison->getJaroWinkler(
                            $recordA['FIRSTNAME'] . ' ' . $recordA['MIDDLENAME'] . ' ' . $recordA['LASTNAME'],
                            $recordB['FIRSTNAME'] . ' ' . $recordB['MIDDLENAME'] . ' ' . $recordB['LASTNAME']
                        );

                        // Set a threshold for similarity (e.g., 0.50)
                        if ($similarity > 0.50) {
                            $similarRecords[] = [
                                'FIRSTNAME' => $recordB->FIRSTNAME,
                                'MIDDLENAME' => $recordB->MIDDLENAME,
                                'LASTNAME' => $recordB->LASTNAME,
                                'SEX' => $recordB->SEX,
                                'BIRTHDATE' => $recordB->BIRTHDATE,
                                'STREETNO_PUROKNO' => $recordB->STREETNO_PUROKNO,
                                'BARANGAY' => $recordB->BARANGAY,
                                'CITYMUNICIPALITY' => $recordB->CITYMUNICIPALITY,
                                'DISTRICT' => $recordB->DISTRICT,
                                'PROVINCE' => $recordB->PROVINCE,
                                'REGION' => $recordB->REGION,
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
                            'SEX' => $recordA['SEX'],
                            'BIRTHDATE' => $recordA['BIRTHDATE'],
                            'STREETNO_PUROKNO' => $recordA['STREETNO_PUROKNO'],
                            'BARANGAY' => $recordA['BARANGAY'],
                            'CITYMUNICIPALITY' => $recordA['CITYMUNICIPALITY'],
                            'DISTRICT' => $recordA['DISTRICT'],
                            'PROVINCE' => $recordA['PROVINCE'],
                            'REGION' => $recordA['REGION'],
                            'duplicates' => $similarRecords
                        ];
                    }
                }
            }
        });

    return response()->json($duplicates); // Return the duplicates as a JSON response
}

}
