<?php

namespace App\Http\Controllers\Api;

use App\Models\FormConditionalLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormConditionalLogicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function array_group(array $data, $by_column)
    {
        $result = [];
        foreach ($data as $item) {
            $column = $item[$by_column];
            unset($item[$by_column]);
            $result[$column][] = $item;
        }
        return $result;
    }



    private $rows = [];
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required','mimes:csv'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $path = $request->file('file')->getRealPath();

        $records = array_map('str_getcsv', file($path));

        if (!count($records) > 0) {
            return response()->json(['status' => 401, 'message' => 'Please check uploaded File.'], 401);
        }

        // Get field names from header column
        $fields = array_map('strtolower', $records[0]);

        // Remove the header column
        array_shift($records);

        foreach ($records as $record) {
            if (count($fields) != count($record)) {
                return 'csv_upload_invalid_data';
            }

            // Decode unwanted html entities
            $record = array_map("html_entity_decode", $record);

            // Set the field name as key
            $record = array_combine($fields, $record);

            // Get the clean data
            $this->rows[] = $this->clear_encoding_str($record);
        }

        $category = [];
        $counter =2;
        foreach ($this->rows as $data) {
            $form_name = $data['form_id'];
            $form_version = $data['form_version'];

            $category[] = [
           "Category_ID" =>  $data['category_id'],
           'AttributeID' => $data['attributeid'],
           'Attribute_Name' => $data['attribute_name'],
           'Attribute_Type' => $data['attribute_type'],
           'Action_Value' => $data['action_value'],
           'Logic_Type' => $data['logic_type'],
           'Attributes_Impacted' => $data['attributes_impacted'],
           'Attributes_Values' => $data['attributes_values'],
           'Attributes_Disabled' => intval($data['attributes_disabled']),
           'Options_Values' => $data['options_values'],
           'Impactatt_type' =>  $data['impactatt_type'],
           'show_enable_flag' =>  $data['show_enable_flag'],
           'Definition' =>  $data['definition'],
            ];
        $counter++;

        }
        $grouped = $this->array_group($category, 'Category_ID');
        $data =[];
        $category_arr =[];
         foreach($grouped  as $key => $val){
            $data['category'] = $key;
            $data["attributes"] = $val;
            $category_arr[] = $data;
         }
       # FormConditionalLogic::truncate();

        // echo "<pre>";
        // print_r($category_arr);
        // exit;
        $addFormConditionalLogics[] = FormConditionalLogic::create([
            "Form_Name" => $form_name,
            "Form_Version" => intval($form_version),
            "Category" => $category_arr
        ]);

        return response()->json(['status' => 200, 'message' => 'Form Conditional Logics inserted successfully.', 'data' => $addFormConditionalLogics], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormConditionalLogic  $formConditionalLogic
     * @return \Illuminate\Http\Response
     */



    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'form_name' => 'required',
            'form_version' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $formConditionalLogics = FormConditionalLogic::where([["Form_Name",$request->form_name],["Form_Version",intval($request->form_version)]])->first();
        if (empty($formConditionalLogics)) {
            return response()->json(['status' => 404,'message'=>'No records Found', 'data'=> []], 404);
            }

        return response()->json(['status' => 200, 'message' => 'Form Conditional Logics', 'data' => $formConditionalLogics], 200);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormConditionalLogic  $formConditionalLogic
     * @return \Illuminate\Http\Response
     */
    public function edit(FormConditionalLogic $formConditionalLogic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormConditionalLogic  $formConditionalLogic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormConditionalLogic $formConditionalLogic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormConditionalLogic  $formConditionalLogic
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormConditionalLogic $formConditionalLogic)
    {
        //
    }

    private function clear_encoding_str($value)
    {
        if (is_array($value)) {
            $clean = [];
            foreach ($value as $key => $val) {
                $clean[$key] = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
            }
            return $clean;
        }
        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }
}
