<?php

namespace App\Http\Controllers\Api;

use App\Models\Alert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AlertRequest;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SendAlert;
use Illuminate\Support\Facades\Mail;
class AlertController extends Controller
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
    public function store(AlertRequest $request)
    {

        if ($request->has('alert_by') && ($request->filled('alert_by') == 'attribute') ) {
            if ($request->has('switchAndOr') && ($request->filled('switchAndOr') != 'or' || $request->filled('switchAndOr') != 'and' )) {
              return response()->json(['status' => 401,'message'=>'With attribute you can add only or / and.'], 400);
            }
        }

        $alert = new Alert;

        $addAlert = $alert->create([
            "alert_name"=>$request->alert_name,
            "evaluator_affiliation"=>$request->evaluator_affiliation,
            "alert_status"=>$request->alert_status,
            "alert_type"=>$request->alert_type,
            "alert_by"=>$request->alert_by,
            "switchAndOr"=>$request->switchAndOr,
            "alert_frequency"=>$request->alert_frequency,
            "form_name"=>$request->form_name,
            "form_attributes"=>$request->form_attributes,
            "measure_type"=>$request->measure_type,
            "measureOprtor"=>$request->measureOprtor,
            "measure_value"=>$request->measure_value,
            "measure_equals_y_n"=>$request->measure_equals_y_n,
            "message_temp"=>$request->message_temp,
            "custom1"=>$request->custom1,
            "custom2"=>$request->custom2,
            "custom3"=>$request->custom3,
            "custom4"=>$request->custom4,
            "alert_reciever_list"=>$request->alert_reciever_list,
            "other_alert_reciever_list"=>$request->other_alert_reciever_list,
            "empid"=>$request->empid,
            "created_by"=>$request->created_by,
            "created_by_type"=>$request->created_by_type,
            "include_me"=>$request->include_me,
            "alert_send_to"=>$request->alert_send_to,
            "notify_all"=>$request->notify_all
        ]);

        if($request->alert_type == "notification"){
            $alertNotification = App\Models\AlertNotification::create([
                "alt_name"=>$request->alt_name,
                "unique_id"=>$request->unique_id,
                "redirect_form_name"=>$request->redirect_form_name,
                "form_version"=>$request->form_version,
                "alert_data"=>$request->alert_data,
                "supp_id"=>$request->supp_id,
                "alert_receiver"=>$request->alert_receiver,
                "viewed_by"=>$request->viewed_by,
                "is_opened"=>$request->is_opened,
                "submitted_by"=>$request->submitted_by,
                "submitted_at"=>$request->submitted_at
            ]);
        }

        return response()->json(['status' => 200,'message'=>'Alert created successfully.', 'data'=> $addAlert], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function show(Alert $alert)
    {
        $alert = new Alert;
        if($request->id !=null)  {
        $Data = $alert->find($request->id);
        }else{
            $Data = $alert->all();
        }
        return response()->json(['status' => 200,'message'=>'Alert Data.', 'data'=> $Data], 400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function edit(Alert $alert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alert $alert)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $required = ['alert_name','evaluator_affiliation','alert_status','alert_type','alert_by','alert_frequency','form_name',
                     'form_attributes','measure_equals_y_n','custom1','custom2','custom3','custom4','empid','created_by','created_by_type'];

        foreach($required as $requires){
            if ($request->has($requires) && !$request->filled($requires)) {
                return response()->json(['status' => 401,'message'=>$requires.' is required.'], 400);
            }
        }

        $alert = new Alert;
        $updateAlert = $alert->find($request->id)->update(
           $request->except($request->id)
        );
        $updatedData = $alert->find($request->id);
        return response()->json(['status' => 200,'message'=>'Alert updated successfully.', 'data'=> $updatedData], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $alert = new Alert;
        $updateStatus = $alert->where("_id",$request->id)->update(['alert_status'=>'Disable']);
        $deleteAlert = $alert->find($request->id)->delete() ;

        return response()->json(['status' => 200,'message'=>'Alert deleted successfully.', 'data'=> []], 200);
    }

    public function ChangeStatus(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $alert = new Alert;
        $updateStatus = $alert->where("_id",$request->id)->update(['alert_status'=>'Enable']);

        return response()->json(['status' => 200,'message'=>'Alert Status Updated successfully.', 'data'=> []], 200);
    }

// public function test(){
//     $data = array('name'=>"Virat Gandhi");
//     Mail::send('email_template.alert_mailer_new', $data, function($message) {
//         $message->to('mailltomee@gmail.com', 'Tutorials Point')->subject
//            ('Laravel Basic Testing Mail');
//         $message->from('tech.support@mattsenkumar.com','Virat Gandhi');
//      });
//     $sendAlert = new SendAlert;
//     $sendAlert->sendAlert("test",["111"]);
// }

}
