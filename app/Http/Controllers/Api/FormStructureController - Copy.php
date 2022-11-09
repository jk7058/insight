<?php

namespace App\Http\Controllers\Api;
use App\Models\FormStructure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AlertRequest;
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

        if (! count($records) > 0) {
            return response()->json(['status' => 401,'message'=>'Please check uploaded File.'], 401); 
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
            $record =  array_map("html_entity_decode", $record);

            // Set the field name as key
            $record = array_combine($fields, $record);

            // Get the clean data
            $this->rows[] = $this->clear_encoding_str($record);
        }
        // echo "<pre>";
        // print_r($this->rows);
        $file_data = [];
       
        foreach ($this->rows as $data) {  
                 
        $Lobs = explode(",",$data['custom1']);
            
        $file_data1 = [
        'client_id' => $data['client_id'],
        'lobs' =>$Lobs,
        'form_unique_id' => $data['form_unique_id'],
        'form_name' => $data['form_name'],
        'form_version' => $data['form_version'],
        'form_created_date' => $data['date_created'],
        'custom_meta_fields' => []
        ];

        $attributes = [
            'attr_name' => $data['attr_display_name'],
            'attr_id' => $data['attr_uni_id'],
            'attr_type' => $data['response_type']
        ];
        $rating = explode(",",$data['rating']);
        $rating_attr_name = explode(",",$data['rating_attr_name']);
       
        $i = 0;       
        foreach($rating_attr_name as $attr_opt_name){
            $attributes['attr_options'][] = ['attr_opt_name'=>$attr_opt_name,
           'attr_opt_value' => $rating[$i],
           'attr_opt_scr' => isset($data['weightage'])?$data['weightage']:"null"
             ] ;            
        $i++;
        }
        $sub_attributes =[];
        if($data['subattribute']!=""){
            $sub_attributes = [
                'attr_name' => $data['rating_attr_name'],
                'attr_id' => $data['subattr_id'],
                'attr_type' => $data['response_type']
            ];
            $sub_attribute_rating = explode(",",$data['rating']);
            $rating_sub_attr_name = explode(",",$data['rating_attr_name']);
           
            $j = 0;       
            foreach($rating_sub_attr_name as $sub_attr_opt_name){                
                $sub_attributes['attr_options'][] = ['attr_opt_name'=>$sub_attr_opt_name,
               'attr_opt_value' => $sub_attribute_rating[$j],
               'attr_opt_scr' => isset($data['weightage'])?$data['weightage']:"null"
                 ] ;            
            $j++;
            }
            $sub_attributes['attr_isScorable'] = ($data['scorable']=="scorable")?true:false;          
            $sub_attributes['attr_weightage'] = $data['attr_uni_id'];
            $sub_attributes['attr_isAF'] = ($data['fatal']=="yes")?true:false;
            $sub_attributes['attr_isMandatory'] = true;
            $sub_attributes['attr_description'] = "";            
        }       
        $attributes['attr_isScorable'] = ($data['scorable']=="scorable")?true:false;          
        $attributes['attr_weightage'] = $data['attr_uni_id'];
        $attributes['attr_isAF'] = ($data['fatal']=="yes")?true:false;
        $attributes['attr_isMandatory'] = true;
        $attributes['attr_description'] = "";
        $attributes['sub_attributes'] = $sub_attributes;
       
        $sub_category =[];
        if($data['subcategory']!=""){         
            
            $sub_category_attributes = [
                'attr_name' => $data['rating_attr_name'],
                'attr_id' => $data['attr_uni_id'],
                'attr_type' => $data['response_type']
            ];
            $rating = explode(",",$data['rating']);
            $rating_attr_name = explode(",",$data['rating_attr_name']);
        
            $k = 0;       
            foreach($rating_attr_name as $attr_opt_name){
                $sub_category_attributes['attr_options'][] = ['attr_opt_name'=>$attr_opt_name,
            'attr_opt_value' => $rating[$k],
            'attr_opt_scr' => isset($data['weightage'])?$data['weightage']:"null"
                ] ;            
            $k++;
            }
            $sub_category_sub_attributes = [];
            if($data['subattribute']!=""){
                $sub_category_sub_attributes = [
                    'attr_name' => $data['rating_attr_name'],
                    'attr_id' => $data['subattr_id'],
                    'attr_type' => $data['response_type']
                ];
                $sub_category_attribute_rating = explode(",",$data['rating']);
                $rating_sub_category_attr_name = explode(",",$data['rating_attr_name']);
            
                $l = 0;       
                foreach($rating_sub_category_attr_name as $sub_category_attr_opt_name){                
                    $sub_category_attributes['attr_options'][] = [
                        'attr_opt_name'=>$sub_category_attr_opt_name,
                        'attr_opt_value' => $sub_category_attribute_rating[$l],
                        'attr_opt_scr' => isset($data['weightage'])?$data['weightage']:"null"
                    ] ;            
                $l++;
                }
                $sub_category_sub_attributes['attr_isScorable'] = ($data['scorable']=="scorable")?true:false;          
                $sub_category_sub_attributes['attr_weightage'] = $data['attr_uni_id'];
                $sub_category_sub_attributes['attr_isAF'] = ($data['fatal']=="yes")?true:false;
                $sub_category_sub_attributes['attr_isMandatory'] = true;
                $sub_category_sub_attributes['attr_description'] = "";            
            }       
            $sub_category_attributes['attr_isScorable'] = ($data['scorable']=="scorable")?true:false;          
            $sub_category_attributes['attr_weightage'] = $data['attr_uni_id'];
            $sub_category_attributes['attr_isAF'] = ($data['fatal']=="yes")?true:false;
            $sub_category_attributes['attr_isMandatory'] = true;
            $sub_category_attributes['attr_description'] = "";
            $sub_category_attributes['sub_attributes'] = $sub_category_sub_attributes;   
            
            $sub_category = [
                'attr_name' => $data['subcategory'],
                'subcat_id' => $data['subcat_id'],
                "attributes" => $sub_category_attributes
            ];
        }  


        
        $headers =[];
        if($data['header']!=""){         
            
            $header_attributes = [
                'attr_name' => $data['rating_attr_name'],
                'attr_id' => $data['attr_uni_id'],
                'attr_type' => $data['response_type']
            ];
            $header_rating = explode(",",$data['rating']);
            $header_rating_attr_name = explode(",",$data['rating_attr_name']);
        
            $m = 0;       
            foreach($header_rating_attr_name as $header_attr_opt_name){
                $header_attributes['attr_options'][] = ['attr_opt_name'=>$header_attr_opt_name,
            'attr_opt_value' => $header_rating[$m],
            'attr_opt_scr' => isset($data['weightage'])?$data['weightage']:"null"
                ] ;            
            $m++;
            }
            $header_sub_attributes = [];
            if($data['subattribute']!=""){
                $header_sub_attributes = [
                    'attr_name' => $data['rating_attr_name'],
                    'attr_id' => $data['subattr_id'],
                    'attr_type' => $data['response_type']
                ];
                $header_sub_attribute_rating = explode(",",$data['rating']);
                $header_rating_sub_attr_name = explode(",",$data['rating_attr_name']);
            
                $l = 0;       
                foreach($header_rating_sub_attr_name as $header_sub_attr_opt_name){                
                    $header_attributes['attr_options'][] = [
                        'attr_opt_name'=>$header_sub_attr_opt_name,
                        'attr_opt_value' => $header_sub_attribute_rating[$l],
                        'attr_opt_scr' => isset($data['weightage'])?$data['weightage']:"null"
                    ] ;            
                $l++;
                }
                $header_sub_attributes['attr_isScorable'] = ($data['scorable']=="scorable")?true:false;          
                $header_sub_attributes['attr_weightage'] = $data['attr_uni_id'];
                $header_sub_attributes['attr_isAF'] = ($data['fatal']=="yes")?true:false;
                $header_sub_attributes['attr_isMandatory'] = true;
                $header_sub_attributes['attr_description'] = "";            
            }       
            $header_attributes['attr_isScorable'] = ($data['scorable']=="scorable")?true:false;          
            $header_attributes['attr_weightage'] = $data['attr_uni_id'];
            $header_attributes['attr_isAF'] = ($data['fatal']=="yes")?true:false;
            $header_attributes['attr_isMandatory'] = true;
            $header_attributes['attr_description'] = "";
            $header_attributes['sub_attributes'] = $header_sub_attributes;   
            
            $headers = [
                'attr_name' => $data['header'],               
                "attributes" => $header_attributes
            ];
        } 
        
       
        
        $category = [
            "cat_name"=>$data['category'],
            "cat_id"=>$data['cat_id'],
            "sub_category"=>$sub_category,
            "attributes" =>$attributes
        ];
        $file_data1['category'] = $category;
        $file_data1['header'] = $headers;
        $file_data[] = $file_data1;
        $addFormStructure[] = $FormStructure->create([
                'client_id' => $data['client_id'],
                'lobs' =>$Lobs,
                'form_unique_id' => $data['form_unique_id'],
                'form_name' => $data['form_name'],
                'form_version' => (int)$data['form_version'],
                'form_created_date' => $data['date_created'],
                'custom_meta_fields' => [],
                "category"=>$category,
                "header"=> $headers          
            ]);

        // $FormStructure->client_id = $data['client_id'];
        // $FormStructure->lobs =$Lobs;
        // $FormStructure->form_unique_id = $data['form_unique_id'];
        // $FormStructure->form_name = $data['form_name'];
        // $FormStructure->form_version = $data['form_version'];
        // $FormStructure->form_created_date = $data['date_created'];
        // $FormStructure->custom_meta_fields = [];
        // $FormStructure->category=$category;
        // $FormStructure->header= $headers;   
        // $FormStructure->save();  
    }
        // echo "<pre>";
        // print_r($file_data);
        return response()->json(['status' => 200,'message'=>'Form Structure created successfully.', 'data'=> $addFormStructure], 400);
        // $addFormStructure = $FormStructure->create([
        //     "client_id"=>$request->client_id,
        //     "lobs"=>$request->lobs,
        //     "form_unique_id"=>$request->form_unique_id,
        //     "form_name"=>$request->form_name,
        //     "form_version"=>$request->form_version,
        //     "form_created_date"=>$request->form_created_date,
        //     "custom_meta_fields"=>$custom_meta_fields,
        //     "category"=>$category           
        // ]);

        // return response()->json(['status' => 200,'message'=>'Form Structure created successfully.', 'data'=> $addaddFormStructureAlert], 400);
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
