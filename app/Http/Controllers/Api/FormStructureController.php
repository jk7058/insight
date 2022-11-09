<?php

namespace App\Http\Controllers\Api;

use App\Models\FormStructure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FormStructureController extends Controller
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
    private $rows = [];
    public function store(Request $request)
    {



        $FormStructure = new FormStructure;

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
//         echo "<pre>";
//         print_r($this->rows);


        $category = [];
        $Lobs = "";
        $client_id = "";
        $form_unique_id = "";
        $custom_meta_fields = "";

        $attributes = [];
        $catId = '';
        $attrName = '';
        $score_attribute = "";
        $score_sub_attribute = '';
        $sub_attributes = [];
        $attr_options = [];
        foreach ($this->rows as $kk => $data) {
//            return $this->rows;
            $client_id = $data['client_id'];
            $form_unique_id = $data['form_unique_id'];
            $form_name = $data['form_name'];
            $form_version = $data['form_version'];
            $form_created_date = $data['date_created'];
            $Lobs = explode(",", $data['custom1']);

            /*             * ******  Fetch All categories  ******** */

            if (array_search(trim($data['category']), array_column($category, 'cat_name')) === false) {
                foreach ($category as $kc => $cc) {
                    if ($cc['cat_id'] == $catId) {
                        // array_splice($attributes,0);
                        $category[$kc]["attributes"] = $attributes;
                        unset($attributes);
                    }
                }


                array_push($category, [
                    "cat_name" => trim($data['category']),
                    "cat_id" => trim($data['cat_id'])
                ]);
                $catId = trim($data['cat_id']);
            }

            //////////  Set Attributes Only
            $subAttributeKeys = array_keys(array_column($this->rows, 'attribute'), $data['attr_display_name']);
            array_shift($subAttributeKeys);
            foreach ($subAttributeKeys as $subkey) {
                    $subAttrData = $this->rows[$subkey];
                    $sub_attributes_temp = [
                        'attr_name' => $subAttrData['attr_display_name'],
                        'attr_id' => $subAttrData['attr_uni_id'],
                        'attr_type' => $subAttrData['response_type']
                    ];
                    $sub_attribute_rating = explode(",", $subAttrData['rating']);
                    $rating_sub_attr_name = explode(",", $subAttrData['rating_attr_name']);

                    $j = 0;
                    foreach ($rating_sub_attr_name as $sub_attr_opt_name) {
                        if ($subAttrData['scorable'] == "scorable") {
                            if (isset($subAttrData['weightage'])) {
                                $score_sub_attribute = explode(",", $subAttrData['weightage']);
                                $sub_attributes_temp['attr_options'][] = [
                                    'attr_opt_name' => $sub_attr_opt_name,
                                    'attr_opt_value' => $sub_attribute_rating[$j],
                                    'attr_opt_scr' => $score_sub_attribute[$j]
                                ];
                            }
                        } else {
                            $sub_attributes_temp['attr_options'][] = [
                                'attr_opt_name' => $sub_attr_opt_name,
                                'attr_opt_value' => $sub_attribute_rating[$j],
                                'attr_opt_scr' => "null"
                            ];
                        }
                        $j++;
                    }

                    $sub_attributes_temp['attr_isScorable'] = ($subAttrData['scorable'] == "scorable") ? 1 : 0;
                    $sub_attributes_temp['attr_weightage'] = $subAttrData['attr_uni_id'];
                    $sub_attributes_temp['attr_isAF'] = ($subAttrData['fatal'] == "yes") ? 1 : 0;
                    $sub_attributes_temp['attr_isMandatory'] = 1;
                    $sub_attributes_temp['attr_description'] = "";
                    $sub_attributes[] = $sub_attributes_temp;
            }



            $rating = explode(",", $data['rating']);
            $rating_attr_name = explode(",", $data['rating_attr_name']);

            $i = 0;
            foreach ($rating_attr_name as $attr_opt_name) {
                if ($data['scorable'] == "scorable") {
                    if (isset($data['weightage'])) {
                        $score_attribute = explode(",", $data['weightage']);
                        $attr_options[] = [
                            'attr_opt_name' => $attr_opt_name,
                            'attr_opt_value' => $rating[$i],
                            'attr_opt_scr' => $score_attribute[$i]
                        ];
                    }
                } else {
                    $attr_options[] = [
                        'attr_opt_name' => $attr_opt_name,
                        'attr_opt_value' => $rating[$i],
                        'attr_opt_scr' => "null"
                    ];
                }
                $i++;
            }
            if ($data['attr_display_name'] === $data['attribute']) {
                $tempAttr = [
                    'attr_name' => $data['attr_display_name'],
                    'attr_id' => $data['attr_uni_id'],
                    'attr_type' => $data['response_type'],
                    'subcat_name' => trim($data['subcategory']),
                    'header' => trim($data['header'])
                ];
                $tempAttr['attr_options'] = $attr_options;
                $tempAttr['sub_attributes'] = $sub_attributes;

                $sub_attributes = [];
                $attr_options = [];
//            }

                $tempAttr['attr_isScorable'] = ($data['scorable'] == "scorable") ? 1 : 0;
                $tempAttr['attr_weightage'] = $data['attr_uni_id'];
                $tempAttr['attr_isAF'] = ($data['fatal'] == "yes") ? 1 : 0;
                $tempAttr['attr_isMandatory'] = 1;
                $tempAttr['attr_isMandatory'] = 1;
                $tempAttr['attr_description'] = "";
                $attributes[] = $tempAttr;
            }


        }




        $addFormStructure = $FormStructure->create([
            "client_id" => intval($client_id),
            "lobs" => $Lobs,
            "form_unique_id" => $form_unique_id,
            "form_name" => $form_name,
            "form_version" => intval($form_version),
            "form_created_date" => $form_created_date,
            "custom_meta_fields" => $custom_meta_fields,
            "category" => $category
        ]);

        return response()->json(['status' => 200, 'message' => 'Form Structure created successfully.', 'data' => $addFormStructure], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormStructure  $formStructure
     * @return \Illuminate\Http\Response
     */
    public function show(FormStructure $formStructure)
    {
        $validator = Validator::make($request->all(), [
            'form_name' => 'required',
            'form_version' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $getFormData  = FormStructure::where("form_name",$form_name)
                       ->where("form_version",intval($form_version))->first();

            if (empty($getFormData)) {
                return response()->json(['status' => 404,'message'=>'No records Found', 'data'=> []], 404);
            }

           return response()->json(['status' => 400,'message'=>'Form Name And Form Version Combination is Already Added.', 'data'=> []], 400);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormStructure  $formStructure
     * @return \Illuminate\Http\Response
     */
    public function edit(FormStructure $formStructure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormStructure  $formStructure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormStructure $formStructure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormStructure  $formStructure
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormStructure $formStructure)
    {
        //
    }

    public function getFormData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'form_name' => 'required',
            'form_version' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

       $getFormData  = FormStructure::where("form_name",$request->form_name)
                       ->where("form_version",intval($request->form_version))->first();
        if (empty($getFormData)) {
            return response()->json(['status' => 404,'message'=>'No records Found', 'data'=> []], 404);
        }

       return response()->json(['status' => 200,'message'=>'Form Data', 'data'=> $getFormData], 200);
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
