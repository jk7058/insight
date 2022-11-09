<?php

namespace App\Http\Controllers\Api;

use App\Models\FormCommentsLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormCommentsLogicController extends Controller
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

        $counter = 2;
        $category = [];
        $attributes =[];
        $categoryName ="";
        foreach ($this->rows as $data) {
            if($data['form_id'] =="" || $data['form_version'] == "" || $data['attributeid'] == "" || $data['attribute_type'] ==""||
            $data['action_value'] == "" || $data['comments_required'] =="" ){
                return response()->json(['status' => 404, 'message' => 'Please check Form Id, Form version, AttributesId, Attribute Type,
                Action Value, Comments Required on Row no. - '.$counter, 'data' => []], 404);
            }

            $form_name = $data['form_id'];
            $form_version = $data['form_version'];


            $category[] = [
                "Category_ID" =>  $data['category_id'],
                'AttributeID' => $data['attributeid'],
                'Attribute_Type' => $data['attribute_type'],
                'Action_Value' => $data['action_value'],
                'Attributes_Impacted' => $data['attributes_impacted'],
                'Comments_Required' => $data['comments_required'],
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
         FormCommentsLogic::truncate();
            $addFormCommentLogics = FormCommentsLogic::create([
                "Form_Name" => $form_name,
                "Form_Version" => intval($form_version),
                "Category" => $category_arr
            ]);



        return response()->json(['status' => 200, 'message' => 'Form Comments Logics inserted successfully.', 'data' => $addFormCommentLogics], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormCommentsLogic  $formCommentsLogic
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

        $formCommentsLogic = FormCommentsLogic::where([["Form_Name",$request->form_name],["Form_Version",intval($request->form_version)]])->first();
        if (empty($formCommentsLogic)) {
            return response()->json(['status' => 404,'message'=>'No records Found', 'data'=> []], 404);
            }
        return response()->json(['status' => 200, 'message' => 'Form Comments Logics', 'data' => $formCommentsLogic], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormCommentsLogic  $formCommentsLogic
     * @return \Illuminate\Http\Response
     */
    public function edit(FormCommentsLogic $formCommentsLogic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormCommentsLogic  $formCommentsLogic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormCommentsLogic $formCommentsLogic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormCommentsLogic  $formCommentsLogic
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormCommentsLogic $formCommentsLogic)
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
