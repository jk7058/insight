<?php

namespace App\Http\Controllers\Api;
use App\Models\FormDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormDetailsRequest;
use Illuminate\Support\Facades\Validator;

class FormDetailsController extends Controller
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
    public function store(FormDetailsRequest $request)
    {
        $FormDetails = new FormDetails;

        $alreadyAddedDetails = $FormDetails->where(["form_name"=>$request->form_name, "form_version"=>$request->form_version])->get();

        if(sizeof($alreadyAddedDetails)){
            return response()->json(['status' => 201,'message'=>'This combination of Form Name and Form Version data is already added.'], 201);
        }

        $form_unique_id = substr(bin2hex(random_bytes(20)),0, 20);
        $custom_meta = [[
            "field_label"=> "Type of Evaluation",
            "field_type"=> "dropdown",
            "field_required"=> "yes",
            "field_value"=> "Project, QA, PIWIL, Supervisor, Training, Strategic Partner, TSC, Coaching, Trainee",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "Customer State",
            "field_type"=> "dropdown",
            "field_required"=> "yes",
            "field_value"=> "AK, AL, AR, AZ, CA, CO, CT, DE, FL, GA, HI, IA, ID, IL, IN, KS, KY, LA, MA, MD, ME, MI, MN, MO, MS, MT, NC, ND, NE, NH, NJ, NM, NV, NY, OH, OK, OR, PA, RI, SC, SD, TN, TX, UT, VA, VT, WA, WI, WV, WY, NA=> No CTL Account",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "C.R.M.",
            "field_type"=> "dropdown",
            "field_required"=> "yes",
            "field_value"=> "CRIS, Ensemble, Not Available",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "Language",
            "field_type"=> "dropdown",
            "field_required"=> "yes",
            "field_value"=> "English, Spanish",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "BTN/CBR",
            "field_type"=> "number",
            "field_required"=> "yes",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "ANI#",
            "field_type"=> "text",
            "field_required"=> "no",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "Conversation / Recording Duration",
            "field_type"=> "timepicker",
            "field_required"=> "yes",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "Call Direction",
            "field_type"=> "dropdown",
            "field_required"=> "yes",
            "field_value"=> "Inbound, Outbound",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "Call Type",
            "field_type"=> "dropdown",
            "field_required"=> "yes",
            "field_value"=> "Audio, Chat, Video",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "Customer Stage",
            "field_type"=> "dropdown",
            "field_required"=> "yes",
            "field_value"=> "Shopper, New or Existing, Previous/Closed",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "Customer Type",
            "field_type"=> "dropdown",
            "field_required"=> "yes",
            "field_value"=> "CON- CRIS, CON- ENS, SBG- CRIS, SBG- ENS, NA",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "Customer Tenure",
            "field_type"=> "dropdown",
            "field_required"=> "yes",
            "field_value"=> "0-90 Days, 91-180 Days, 181+ Days, NA",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ],
        [
            "field_label"=> "Invalidation Comment",
            "field_type"=> "textarea",
            "field_required"=> "no",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "false"
        ],
        [
            "field_label"=> "CTL QA Management Comments",
            "field_type"=> "textarea",
            "field_required"=> "no",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "false"
        ],
        [
            "field_label"=> "MK Audit Feedback Loop",
            "field_type"=> "textarea",
            "field_required"=> "no",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "false"
        ],
        [
            "field_label"=> "Evaluator Review Feedback",
            "field_type"=> "textarea",
            "field_required"=> "no",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "false"
        ],
        [
            "field_label"=> "Agent comment",
            "field_type"=> "textarea",
            "field_required"=> "no",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "false"
        ],
        [
            "field_label"=> "Supervisor comment",
            "field_type"=> "textarea",
            "field_required"=> "no",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "false"
        ],
        [
            "field_label"=> "Performance Area Note",
            "field_type"=> "textarea",
            "field_required"=> "no",
            "field_value"=> "",
            "field_submit_val"=> "",
            "visibility"=> "true"
        ]
    ];
        $addFormDetails = $FormDetails->create([
            "client_id"=>intval($request->client_id),
            "form_unique_id"=>$form_unique_id,
            "form_name"=>$request->form_name,
            "form_version"=>intval($request->form_version),
            "form_attributes"=>intval($request->form_attributes),
            "category_count"=>intval($request->category_count),
            "rating_attr"=>$request->rating_attr,
            "rating_attr_name"=>$request->rating_attr_name,
            "tb_name"=>$request->tb_name,
            "display_name"=>$request->display_name,
            "form_status"=>intval($request->form_status),
            "custom1"=>$request->custom1,
            "custom2"=>$request->custom2,
            "custom3"=>$request->custom3,
            "custom4"=>$request->custom4,
            "channels"=>$request->channels,
            "effective"=>$request->effective,
            "pass_rate"=>intval($request->pass_rate),
            "form_weightage"=>$request->form_weightage,
            "feedback_tat"=>intval($request->feedback_tat),
            "user_type"=>intval($request->user_type),
            "user_id"=>$request->user_id,
            "custom_meta"=>$custom_meta,
        ]);

        return response()->json(['status' => 200,'message'=>'Form Details created successfully.', 'data'=> $addFormDetails], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormDetails  $formDetails
     * @return \Illuminate\Http\Response
     */
    public function show(FormDetails $formDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormDetails  $formDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(FormDetails $formDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormDetails  $formDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormDetails $formDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormDetails  $formDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormDetails $formDetails)
    {
        //
    }

    public function getFormCustomMeta(Request $request){
        $validator = Validator::make($request->all(), [
            'form_name' => 'required',
            'form_version' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $getFormName  = FormDetails::select("form_name","form_version","custom_meta")
                       ->where("form_name",$request->form_name)
                       ->where("form_version",intval($request->form_version))
                       ->first();

        if (empty($getFormName)) {
            return response()->json(['status' => 200,'message'=>'No records Found', 'data'=> []], 200);
        }

       return response()->json(['status' => 200,'message'=>'Custom Meta', 'data'=> $getFormName], 200);
    }
}
