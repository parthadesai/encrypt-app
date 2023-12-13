<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ImportData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Config as SystemConfig;
use Illuminate\Support\Facades\Config;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class ImportDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ImportData::all();
        return view('index', compact('data'));
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Validate file type
            $validExtensions = ['xls', 'xlsx', 'XLS', 'XLSX'];
            $fileExtension = $file->getClientOriginalExtension();

            if (!in_array($fileExtension, $validExtensions)) {
                return redirect('/')->with('error', "Sorry, only Excel files are allowed.");
            }

            try {
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $sheetData = $sheet->toArray();
                
                // Assuming the first row contains headers
                $actualHeaders = $sheetData[0];

                // Validate headers
                $expectedHeaders = ['Name', 'Email', 'Phone Number', 'Gender', 'DOB'];

                if ($actualHeaders != $expectedHeaders) {
                    return redirect('/')->with('error', "Invalid headers. Expected: " . implode(', ', $expectedHeaders));
                }
                
                // Start a database transaction
                DB::beginTransaction();
                
                // Loop through the rows starting from the second row
                foreach ($sheetData as $index => $data) {
                    if ($index != 0) {
                        
                        // Access data using column names from headers
                        $storeData = [
                            'name'         => $data[array_search('Name', $expectedHeaders)],
                            'email'        => $data[array_search('Email', $expectedHeaders)],
                            'phone_number' => $data[array_search('Phone Number', $expectedHeaders)],
                            'gender'       => $data[array_search('Gender', $expectedHeaders)],
                            'dob'          => $data[array_search('DOB', $expectedHeaders)],
                        ];
                        ImportData::create($storeData);
                    }
                }

                // Commit the transaction if everything is successful
                DB::commit();
                return redirect('/')->with('success', "The file {$file->getClientOriginalName()} has been uploaded.");
                // return "The file {$file->getClientOriginalName()} has been uploaded.";
            } catch (\Exception $e) {
                // Handle any exceptions and rollback the transaction
                DB::rollBack();
                // return "An error occurred: " . $e->getMessage();
                return redirect('/')->with('error', "An error occurred: " . $e->getMessage());
            }
        }
        return redirect('/')->with('error', "Please upload a file !!!");
    }

    public function changeKey(Request $request)
    {
        $validatedData = $request->validate([
            'encryption_key' => 'required|min:32|max:32',
        ]);
        $new_encryption_key = $request->encryption_key;
        $results = ImportData::all();
        $data = [];
        // Start a database transaction
        try {
            DB::beginTransaction();
            $encrypter = new \Illuminate\Encryption\Encrypter($new_encryption_key, Config::get('app.cipher') );
            foreach ($results as $result) {
                
                $data[] = [
                    'name' => $encrypter->encrypt($result->name),
                    'email' => $encrypter->encrypt($result->email),
                    'phone_number' => $encrypter->encrypt($result->phone_number),
                    'gender' => $encrypter->encrypt($result->gender),
                    'dob' => $encrypter->encrypt($result->dob),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            ImportData::truncate();
            ImportData::insert($data);
            SystemConfig::where('data_key', 'encryption_key')->update(['data_value' => $new_encryption_key]);
            DB::commit();
            return redirect('/')->with('success', "Encryption Key changed successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/')->with('error', "An error occurred: " . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ImportData $importData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImportData $importData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImportData $importData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImportData $importData)
    {
        //
    }
}
