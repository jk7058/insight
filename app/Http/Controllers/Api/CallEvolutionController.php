<?php
namespace App\Http\Controllers\Api;

use App\Models\{
    CallEvolution,
    Agent,
    Hierarchy,
    User,
    Role,
    FormDetails
};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CallEvolutionRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class CallEvolutionController extends Controller
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


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'c1' => 'required',
            'c2' => 'required',
            'c3' => 'required',
            'c4' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $CallEvolution = new CallEvolution;

        if($request->has('evaluator') && $request->filled('evaluator')){
        $getEvalutor = User::where("_id",trim($request->evaluator))->orWhere("userId",trim($request->evaluator))->get()->first();
                $Level_1 = "Level-1";
                $Level_2 = "Level-2";
                $supervisor = [];
                $supervisor['name'] = !empty($getEvalutor->$Level_1['name'])?$getEvalutor->$Level_1['name']:"";
                $supervisor['id'] = !empty($getEvalutor->$Level_1['userId'])?$getEvalutor->$Level_1['userId']:"";
                $supervisor['email'] = !empty($getEvalutor->$Level_1['email'])?$getEvalutor->$Level_1['email']:"";

                $manager = [];
                $manager['name'] = !empty($getEvalutor->$Level_2['name'])?$getEvalutor->$Level_2['name']:"";
                $manager['id'] = !empty($getEvalutor->$Level_2['userId'])?$getEvalutor->$Level_2['userId']:"";
                $manager['email'] = !empty($getEvalutor->$Level_2['email'])?$getEvalutor->$Level_2['email']:"";

                $evaluator = (object)[
                "name"=>$getEvalutor->name,
                "id"=>$getEvalutor->userId,
                "role"=>$getEvalutor->userRole,
                "email"=>$getEvalutor->userEmail,
                "assigned_by" => $request->userId,
                "assigned_date" => date("Y-m-d h:i:s"),
                "supervisor"=>$supervisor,
                "manager"=>$manager
            ];
        } else{
             $evaluator = "";
        }
        if($request->has('agent') && $request->filled('agent')){
            $getAgent = Agent::where([["_id","=",trim($request->agent)],["Status","=","Active"]])
                        ->orWhere("agent_id",trim($request->agent))->get()->first();
           # echo "<pre>"; print_r($getAgent);
              if(!empty($getAgent)){
                $supervisor = [];
                $supervisor['name'] = !empty($getAgent->Level_1['name'])?$getAgent->Level_1['name']:"";
                $supervisor['id'] = !empty($getAgent->Level_1['userId'])?$getAgent->Level_1['userId']:"";
                $supervisor['email'] = !empty($getAgent->Level_1['email'])?$getAgent->Level_1['email']:"";

                $manager1 = [];
                $manager1['name'] = !empty($getAgent->Level_2['name'])?$getAgent->Level_2['name']:"";
                $manager1['id'] = !empty($getAgent->Level_2['userId'])?$getAgent->Level_2['userId']:"";
                $manager1['email'] = !empty($getAgent->Level_2['email'])?$getAgent->Level_2['email']:"";

                $manager2 = [];
                $manager2['name'] = !empty($getAgent->Level_3['name'])?$getAgent->Level_3['name']:"";
                $manager2['id'] = !empty($getAgent->Level_3['userId'])?$getAgent->Level_3['userId']:"";
                $manager2['email'] = !empty($getAgent->Level_3['email'])?$getAgent->Level_3['email']:"";

                $manager3 = [];
                $manager3['name'] = !empty($getAgent->Level_4['name'])?$getAgent->Level_4['name']:"";
                $manager3['id'] = !empty($getAgent->Level_4['userId'])?$getAgent->Level_4['userId']:"";
                $manager3['email'] = !empty($getAgent->Level_4['email'])?$getAgent->Level_4['email']:"";


                $agent = (object)[
                        "name"=>$getAgent->agent_name,
                        "id"=>$getAgent->agent_id,
                        "email"=>$getAgent->agent_email,
                        "doj"=>$getAgent->doj,
                        "effective_date"=>$getAgent->EffectiveDate,
                        "supervisor"=>$supervisor,
                        "manager1"=>$manager1,
                        "manager2"=>$manager2,
                        "manager3"=>$manager3
                  ] ;
              }
              else{
                return response()->json([
                    'status' => 401,
                    'message' => 'Agent Details not found.',
                ], 401);
              }
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Agent Id Required.',
            ], 401);
        }



      /*  if($request->has('hierarchy') && $request->filled('hierarchy')){
            $getHierarchy = Hierarchy::where("_id",trim($request->hierarchy))->get()->first();
            if(!empty($getHierarchy)){
                    $hierarchy = (object)[
                        "id" => $getHierarchy->_id,
                        "custom1"=>$getHierarchy->c1,
                        "custom2"=>$getHierarchy->c2,
                        "custom3"=>$getHierarchy->c3,
                        "custom4"=>$getHierarchy->c4
                    ];
                }
                else{
                    return response()->json([
                        'status' => 401,
                        'message' => 'Hierarchy Details not found.',
                    ], 401);
                }
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Hierarchy Data Required.',
            ], 401);
        }
*/

            $getHierarchy = Hierarchy::where([["c1",trim($request->c1)],["c2",trim($request->c2)],["c3",trim($request->c3)],["c4",trim($request->c4)],])->get()->first();
            if(!empty($getHierarchy)){
                    $hierarchy = (object)[
                        "id" => $getHierarchy->_id,
                        "custom1"=>$getHierarchy->c1,
                        "custom2"=>$getHierarchy->c2,
                        "custom3"=>$getHierarchy->c3,
                        "custom4"=>$getHierarchy->c4
                    ];
                }
                else{
                    return response()->json([
                        'status' => 401,
                        'message' => 'Hierarchy Details not found.',
                    ], 401);
                }


        if($request->has('userId') && $request->filled('userId')){
                $getCallAddedBy = User::where("_id",trim($request->userId))->orWhere("userId",trim($request->userId))->get()->first();
                if(!empty($getCallAddedBy)){
                    $call_added_by = [
                            "name"=>$getCallAddedBy->name,
                            "id"=>$getCallAddedBy->userId
                        ];
                    }else{
                        return response()->json([
                            'status' => 401,
                            'message' => 'Call AddedBy Details not found.',
                        ], 401);
                    }
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Hierarchy Required.',
            ], 401);
        }

        $callDetails = (object)[
            "call_id"=>$request->call_id,
            "call_date"=>$request->call_date,
            "call_duration"=>$request->call_duration,
            "call_register_date"=>date("Y-m-d"),
            "call_register_by"=>$call_added_by
          ];

        $addCallEvolution = $CallEvolution->create([
            "form_name"=>$request->form_name,
            "form_version"=>$request->form_version,
            "channel"=>$request->channel,
            "affiliation"=>$request->affiliation,
            "evaluator"=>$evaluator,
            "agent"=>$agent,
            "hierarchy"=>$hierarchy,
            "call"=>$callDetails,
            "evaluation_status"=> isset($request->evaluation_status)?$request->evaluation_status:"",
        ]);

        return response()->json(['status' => 200,'message'=>'Call created successfully.', 'data'=> $addCallEvolution], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CallEvolution  $callEvolution
     * @return \Illuminate\Http\Response
     */
    public function show(CallEvolution $callEvolution)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CallEvolution  $callEvolution
     * @return \Illuminate\Http\Response
     */
    public function edit(CallEvolution $callEvolution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CallEvolution  $callEvolution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CallEvolution $callEvolution)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CallEvolution  $callEvolution
     * @return \Illuminate\Http\Response
     */
    public function destroy(CallEvolution $callEvolution)
    {
        //
    }

    public function getAgents(Request $request){
        $validator = Validator::make($request->all(), [
            'c1' => 'required',
            'c2' => 'required',
            'c3' => 'required',
            'c4' => 'required',
            'name' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $getHierarchy = Hierarchy::where([["c1",trim($request->c1)],["c2",trim($request->c2)],["c3",trim($request->c3)],["c4",trim($request->c4)],])->get()->first();
        if(empty($getHierarchy))
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'Hierarchy Details not found.',
                ], 200);
            }

       $getAgents  = Agent::where([['agent_name', 'like', '%'. $request->name.'%'],["hierarchy_id",$getHierarchy->_id]])
                     ->where('Status','Active')->get();

        if (empty($getAgents)) {
        return response()->json(['status' => 200,'message'=>'No records Found', 'data'=> []], 200);
        }
       $agents = [];
         foreach($getAgents as $agentss){
                $agents[] = [
                'agent_id' =>$agentss->agent_id,
                'agent_name'=>$agentss->agent_name,
                'agent_name_id' => $agentss->agent_name."(".$agentss->agent_id.")"
                ];
         }
        return response()->json(['status' => 200,'message'=>'Agents', 'data'=> $agents], 200);
    }

    public function getAgentSuperVisor(Request $request){
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
       $getSupervisor  = Agent::where('agent_id', $request->agent_id)->get();

       if (!sizeof($getSupervisor)) {
        return response()->json(['status' => 200,'message'=>'No records Found', 'data'=> []], 200);
       }
       $supervisor = [
        "super_visior_id"=>$getSupervisor[0]['Level_1']['userId'],
        "super_visior_name"=>$getSupervisor[0]['Level_1']['name'],
        "super_visior_name_id"=>$getSupervisor[0]['Level_1']['name']."(".$getSupervisor[0]['Level_1']['userId'].")"
       ];

       return response()->json(['status' => 200,'message'=>'Agents', 'data'=> $supervisor], 200);
    }

    public function getOtherEvaluators(Request $request){
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

       $getEvaluators  = User::where("userId","<>",$request->userId)
                         ->where("userType",2)
                         ->whereIn('userRole', array(2, 3, 4))
                         ->where("userStatus","Active")->get();

        if (empty($getEvaluators)) {
            return response()->json(['status' => 200,'message'=>'No records Found', 'data'=> []], 200);
        }
       $users = [];
       foreach($getEvaluators as $evaluator){
              $users[] = [
              'agent_id' =>$evaluator->userId,
              'agent_name'=>$evaluator->name,
              'agent_name_id' => $evaluator->name."(".$evaluator->userId.")"
              ];
       }

       return response()->json(['status' => 200,'message'=>'Evaluators', 'data'=> $users], 200);
    }

    public function getFormName(Request $request){
        $validator = Validator::make($request->all(), [
            'c1' => 'required',
            'c2' => 'required',
            'c3' => 'required',
            'c4' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $getFormName  = FormDetails::select("form_name","form_version")
                       ->where("form_status",1)
                       ->where("custom1",$request->c1)
                       ->where("custom2",$request->c2)
                       ->where("custom3",$request->c3)
                       ->where("custom4",$request->c4)
                       ->whereBetween("effective",[date("Y-m-01"),date("Y-m-t", strtotime(date("Y-m-1")))])
                       ->first();


        if(empty($getFormName)){
            $getFormName  = FormDetails::select("form_name","form_version")
                        ->where("effective","=<",date("Y-m-d"))
                        ->where("form_status",1)
                        ->where("custom1",$request->c1)
                        ->where("custom2",$request->c2)
                        ->where("custom3",$request->c3)
                        ->where("custom4",$request->c4)->first();
            }
        if (empty($getFormName)) {
            return response()->json(['status' => 200,'message'=>'No records Found', 'data'=> []], 200);
        }

       return response()->json(['status' => 200,'message'=>'Form Name', 'data'=> $getFormName], 200);
    }

    public function getUnassignedCalls(Request $request)
    {
        $unAssignedCalls = CallEvolution::where([["evaluator",""],["evaluation_status","<>","Abort"]])->get();
        if (empty($unAssignedCalls)) {
            return response()->json(['status' => 200,'message'=>'No records Found', 'data'=> []], 200);
        }
        return response()->json(['status' => 200,'message'=>'UnAssigned Calls.', 'data'=> $unAssignedCalls], 200);
    }

    public function AssignCallLater(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluator' => 'required',
            'call_id' => 'required',
            'id' => 'required',
            'userId' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $CallEvolution = new CallEvolution;
        $getLastEvaluator = CallEvolution::select("evaluator")->where("_id",$request->id)->get()->toArray();

        $last_evaluators = [];

        if(isset($getLastEvaluator[0]['evaluator']['last_evaluators']) && !empty($getLastEvaluator[0]['evaluator']['last_evaluators'])){
            $last_evaluators = $getLastEvaluator[0]['evaluator']['last_evaluators'] ;
            unset($getLastEvaluator[0]['evaluator']['last_evaluators']);
            array_push($last_evaluators,$getLastEvaluator[0]['evaluator']);
        }
        else{
             array_push($last_evaluators,$getLastEvaluator[0]['evaluator']);
        }
//        print_r($last_evaluators);
//  exit;
        if($request->has('evaluator') && $request->filled('evaluator')){
            $getEvalutor = User::where("_id",trim($request->evaluator))->orWhere("userId",trim($request->evaluator))->get()->first();
            if (empty($getEvalutor)) {
                return response()->json(['status' => 200,'message'=>'Evaluator records not found', 'data'=> []], 200);
            }
                    $Level_1 = "Level-1";
                    $Level_2 = "Level-2";
                    $supervisor = [];
                    $supervisor['name'] = !empty($getEvalutor->$Level_1['name'])?$getEvalutor->$Level_1['name']:"";
                    $supervisor['id'] = !empty($getEvalutor->$Level_1['userId'])?$getEvalutor->$Level_1['userId']:"";
                    $supervisor['email'] = !empty($getEvalutor->$Level_1['email'])?$getEvalutor->$Level_1['email']:"";

                    $manager = [];
                    $manager['name'] = !empty($getEvalutor->$Level_2['name'])?$getEvalutor->$Level_2['name']:"";
                    $manager['id'] = !empty($getEvalutor->$Level_2['userId'])?$getEvalutor->$Level_2['userId']:"";
                    $manager['email'] = !empty($getEvalutor->$Level_2['email'])?$getEvalutor->$Level_2['email']:"";

                    $evaluator = (object)[
                    "name"=>$getEvalutor->name,
                    "id"=>$getEvalutor->userId,
                    "role"=>$getEvalutor->userRole,
                    "email"=>$getEvalutor->userEmail,
                    "assigned_by" => $request->userId,
                    "assigned_date" => date("Y-m-d h:i:s"),
                    "supervisor"=>$supervisor,
                    "manager"=>$manager,
                    "last_evaluators" => $last_evaluators
                ];
            }
        $updateAssignee  = $CallEvolution->where("call.call_id",$request->call_id)->where("_id",$request->id)->update(["evaluator"=>$evaluator]);

        if(!$updateAssignee){
            return response()->json(['status' => 401,'message'=>'Data not updated.', 'data'=> []], 401);
        }
        $updatedCall = CallEvolution::where("_id",$request->id)->get();

        return response()->json(['status' => 200,'message'=>'Call data', 'data'=> $updatedCall], 200);

    }

    public function getMyCalls(Request $request)
    {
        $myCalls = CallEvolution::where([["evaluator.id",$request->evaluator],["evaluation_status","<>","Abort"]])->get();
        if (empty($myCalls)) {
            return response()->json(['status' => 200,'message'=>'No records Found', 'data'=> []], 200);
        }
        return response()->json(['status' => 200,'message'=>'My Calls.', 'data'=> $myCalls], 200);
    }

    public function getAllCalls(Request $request)
    {
        $allCalls = CallEvolution::all();
        if (empty($allCalls)) {
            return response()->json(['status' => 200,'message'=>'No records Found', 'data'=> []], 200);
        }
        return response()->json(['status' => 200,'message'=>'All Calls.', 'data'=> $allCalls], 200);
    }

    public function updateCallStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluation_status' => 'required',
            'id' => 'required',
            'user_id' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        if($request->evaluation_status == "Abort"){

            $validator = Validator::make($request->all(), [
                'abort_reason' => 'required'
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 400);
            }

            $statusData = [
                "abort_reason"=>$request->abort_reason,
                "evaluation_status"=>$request->evaluation_status,
                "Abort_by" => $request->user_id,
                "Abort_date" => date("Y-m-d h:i:s")
            ];
        }
        else if($request->evaluation_status == "In Progress" || $request->evaluation_status == "In_Progress" || $request->evaluation_status == "In_progress"){
            $statusData = [
                "evaluation_status"=>$request->evaluation_status,
                "In_progress_by" => $request->user_id,
                "In_progress_date" => date("Y-m-d h:i:s")
            ];
        }
        else if($request->evaluation_status == "Completed"){
            $statusData = [
                "evaluation_status"=>$request->evaluation_status,
                "Completed_by" => $request->user_id,
                "Completed_date" => date("Y-m-d h:i:s")
            ];
        }
        else if($request->evaluation_status == "Ata-aborted"){
            $validator = Validator::make($request->all(), [
                'abort_reason' => 'required'
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 400);
            }
            $statusData = [
                'abort_reason' => $request->abort_reason,
                "evaluation_status"=>$request->evaluation_status,
                "Completed_by" => $request->user_id,
                "Completed_date" => date("Y-m-d h:i:s")
            ];
        }
        $updateStatus = CallEvolution::where("_id",$request->id)->update($statusData);
        if (empty($updateStatus)) {
            return response()->json(['status' => 200,'message'=>'Something Went Wrong, Please try again.', 'data'=> []], 200);
        }
        return response()->json(['status' => 200,'message'=>'Status Updated Successfully.', 'data'=> $updateStatus], 200);
    }

    public function AddEvolution(Request $request){

        $CallEvolution = new CallEvolution;

        $salesConsumer = [
        /////  SALES:Consumer  Access Line
        "sales_consumer_access_line" => $request->sales_consumer_access_line,
        "sales_consumer_access_line_comment" => $request->sales_consumer_access_line_comment,
        "sales_consumer_access_line_asked_delivery_questions" => $request->sales_consumer_access_line_asked_delivery_questions,
        "sales_consumer_access_line_asked_delivery_questions_comment" => $request->sales_consumer_access_line_asked_delivery_questions_comment,
        "sales_consumer_access_line_offer_products" => $request->sales_consumer_access_line_offer_products,
        "sales_consumer_access_line_offer_products_comment" => $request->sales_consumer_access_line_offer_products_comment,
        "sales_consumer_access_line_offer_tailored_based" => $request->sales_consumer_access_line_offer_tailored_based,
        "sales_consumer_access_line_offer_tailored_based_comment" => $request->sales_consumer_access_line_offer_tailored_based_comment,
        "sales_consumer_access_line_asked_for_sale" => $request->sales_consumer_access_line_asked_for_sale,
        "sales_consumer_access_line_asked_for_sale_comment" => $request->sales_consumer_access_line_asked_for_sale_comment,
        "sales_consumer_access_line_did_customer_object" => $request->sales_consumer_access_line_did_customer_object,
        "sales_consumer_access_line_did_customer_object_comment" => $request->sales_consumer_access_line_did_customer_object_comment,
        "sales_consumer_access_line_attempt_to_overcome_objections" => $request->sales_consumer_access_line_attempt_to_overcome_objections,
        "sales_consumer_access_line_attempt_to_overcome_objections_comment" => $request->sales_consumer_access_line_attempt_to_overcome_objections_comment,
        "sales_consumer_access_line_reason_customer_declined_offer" => $request->sales_consumer_access_line_reason_customer_declined_offer,
        "sales_consumer_access_line_reason_customer_declined_offer_comment" => $request->sales_consumer_access_line_reason_customer_declined_offer_comment,
        "sales_consumer_access_line_closed_the_sale" => $request->sales_consumer_access_line_closed_the_sale,
        "sales_consumer_access_line_closed_the_sale_comment" => $request->sales_consumer_access_line_closed_the_sale_comment,
         /////  SALES:Consumer  Cyber Shield
         "sales_consumer_cyber_shield" => $request->sales_consumer_cyber_shield,
         "sales_consumer_cyber_shield_comment" => $request->sales_consumer_cyber_shield_comment,
         "sales_consumer_cyber_shield_asked_delivery_questions" => $request->sales_consumer_cyber_shield_asked_delivery_questions,
         "sales_consumer_cyber_shield_asked_delivery_questions_comment" => $request->sales_consumer_cyber_shield_asked_delivery_questions_comment,
         "sales_consumer_cyber_shield_offer_products" => $request->sales_consumer_cyber_shield_offer_products,
         "sales_consumer_cyber_shield_offer_products_comment" => $request->sales_consumer_cyber_shield_offer_products_comment,
         "sales_consumer_cyber_shield_offer_tailored_based" => $request->sales_consumer_cyber_shield_offer_tailored_based,
         "sales_consumer_cyber_shield_offer_tailored_based_comment" => $request->sales_consumer_cyber_shield_offer_tailored_based_comment,
         "sales_consumer_cyber_shield_asked_for_sale" => $request->sales_consumer_cyber_shield_asked_for_sale,
         "sales_consumer_cyber_shield_asked_for_sale_comment" => $request->sales_consumer_cyber_shield_asked_for_sale_comment,
         "sales_consumer_cyber_shield_did_customer_object" => $request->sales_consumer_cyber_shield_did_customer_object,
         "sales_consumer_cyber_shield_did_customer_object_comment" => $request->sales_consumer_cyber_shield_did_customer_object_comment,
         "sales_consumer_cyber_shield_attempt_to_overcome_objections" => $request->sales_consumer_cyber_shield_attempt_to_overcome_objections,
         "sales_consumer_cyber_shield_attempt_to_overcome_objections_comment" => $request->sales_consumer_cyber_shield_attempt_to_overcome_objections_comment,
         "sales_consumer_cyber_shield_reason_customer_declined_offer" => $request->sales_consumer_cyber_shield_reason_customer_declined_offer,
         "sales_consumer_cyber_shield_reason_customer_declined_offer_comment" => $request->sales_consumer_cyber_shield_reason_customer_declined_offer_comment,
         "sales_consumer_cyber_shield_closed_the_sale" => $request->sales_consumer_cyber_shield_closed_the_sale,
         "sales_consumer_cyber_shield_closed_the_sale_comment" => $request->sales_consumer_cyber_shield_closed_the_sale_comment,
          /////  SALES:Consumer  Dish
         "sales_consumer_dish" => $request->sales_consumer_dish,
         "sales_consumer_dish_comment" => $request->sales_consumer_dish_comment,
         "sales_consumer_dish_asked_delivery_questions" => $request->sales_consumer_dish_asked_delivery_questions,
         "sales_consumer_dish_asked_delivery_questions_comment" => $request->sales_consumer_dish_asked_delivery_questions_comment,
         "sales_consumer_dish_offer_products" => $request->sales_consumer_dish_offer_products,
         "sales_consumer_dish_offer_products_comment" => $request->sales_consumer_dish_offer_products_comment,
         "sales_consumer_dish_offer_tailored_based" => $request->sales_consumer_dish_offer_tailored_based,
         "sales_consumer_dish_offer_tailored_based_comment" => $request->sales_consumer_dish_offer_tailored_based_comment,
         "sales_consumer_dish_asked_for_sale" => $request->sales_consumer_dish_asked_for_sale,
         "sales_consumer_dish_asked_for_sale_comment" => $request->sales_consumer_dish_asked_for_sale_comment,
         "sales_consumer_dish_did_customer_object" => $request->sales_consumer_dish_did_customer_object,
         "sales_consumer_dish_did_customer_object_comment" => $request->sales_consumer_dish_did_customer_object_comment,
         "sales_consumer_dish_attempt_to_overcome_objections" => $request->sales_consumer_dish_attempt_to_overcome_objections,
         "sales_consumer_dish_attempt_to_overcome_objections_comment" => $request->sales_consumer_dish_attempt_to_overcome_objections_comment,
         "sales_consumer_dish_reason_customer_declined_offer" => $request->sales_consumer_dish_reason_customer_declined_offer,
         "sales_consumer_dish_reason_customer_declined_offer_comment" => $request->sales_consumer_dish_reason_customer_declined_offer_comment,
         "sales_consumer_dish_closed_the_sale" => $request->sales_consumer_dish_closed_the_sale,
         "sales_consumer_dish_closed_the_sale_comment" => $request->sales_consumer_dish_closed_the_sale_comment,
          /////  SALES:Consumer  DirecTv
         "sales_consumer_directv" => $request->sales_consumer_directv,
         "sales_consumer_directv_comment" => $request->sales_consumer_directv_comment,
         "sales_consumer_directv_asked_delivery_questions" => $request->sales_consumer_directv_asked_delivery_questions,
         "sales_consumer_directv_asked_delivery_questions_comment" => $request->sales_consumer_directv_asked_delivery_questions_comment,
         "sales_consumer_directv_offer_products" => $request->sales_consumer_directv_offer_products,
         "sales_consumer_directv_offer_products_comment" => $request->sales_consumer_directv_offer_products_comment,
         "sales_consumer_directv_offer_tailored_based" => $request->sales_consumer_directv_offer_tailored_based,
         "sales_consumer_directv_offer_tailored_based_comment" => $request->sales_consumer_directv_offer_tailored_based_comment,
         "sales_consumer_directv_asked_for_sale" => $request->sales_consumer_directv_asked_for_sale,
         "sales_consumer_directv_asked_for_sale_comment" => $request->sales_consumer_directv_asked_for_sale_comment,
         "sales_consumer_directv_did_customer_object" => $request->sales_consumer_directv_did_customer_object,
         "sales_consumer_directv_did_customer_object_comment" => $request->sales_consumer_directv_did_customer_object_comment,
         "sales_consumer_directv_attempt_to_overcome_objections" => $request->sales_consumer_directv_attempt_to_overcome_objections,
         "sales_consumer_directv_attempt_to_overcome_objections_comment" => $request->sales_consumer_directv_attempt_to_overcome_objections_comment,
         "sales_consumer_directv_reason_customer_declined_offer" => $request->sales_consumer_directv_reason_customer_declined_offer,
         "sales_consumer_directv_reason_customer_declined_offer_comment" => $request->sales_consumer_directv_reason_customer_declined_offer_comment,
         "sales_consumer_directv_closed_the_sale" => $request->sales_consumer_directv_closed_the_sale,
         "sales_consumer_directv_closed_the_sale_comment" => $request->sales_consumer_directv_closed_the_sale_comment,
          /////  SALES:Consumer  DirecTv Stream
         "sales_consumer_directv_stream" => $request->sales_consumer_directv_stream,
         "sales_consumer_directv_stream_comment" => $request->sales_consumer_directv_stream_comment,
         "sales_consumer_directv_stream_asked_delivery_questions" => $request->sales_consumer_directv_stream_asked_delivery_questions,
         "sales_consumer_directv_stream_asked_delivery_questions_comment" => $request->sales_consumer_directv_stream_asked_delivery_questions_comment,
         "sales_consumer_directv_stream_offer_products" => $request->sales_consumer_directv_stream_offer_products,
         "sales_consumer_directv_stream_offer_products_comment" => $request->sales_consumer_directv_stream_offer_products_comment,
         "sales_consumer_directv_stream_offer_tailored_based" => $request->sales_consumer_directv_stream_offer_tailored_based,
         "sales_consumer_directv_stream_offer_tailored_based_comment" => $request->sales_consumer_directv_stream_offer_tailored_based_comment,
         "sales_consumer_directv_stream_asked_for_sale" => $request->sales_consumer_directv_stream_asked_for_sale,
         "sales_consumer_directv_stream_asked_for_sale_comment" => $request->sales_consumer_directv_stream_asked_for_sale_comment,
         "sales_consumer_directv_stream_did_customer_object" => $request->sales_consumer_directv_stream_did_customer_object,
         "sales_consumer_directv_stream_did_customer_object_comment" => $request->sales_consumer_directv_stream_did_customer_object_comment,
         "sales_consumer_directv_stream_attempt_to_overcome_objections" => $request->sales_consumer_directv_stream_attempt_to_overcome_objections,
         "sales_consumer_directv_stream_attempt_to_overcome_objections_comment" => $request->sales_consumer_directv_stream_attempt_to_overcome_objections_comment,
         "sales_consumer_directv_stream_reason_customer_declined_offer" => $request->sales_consumer_directv_stream_reason_customer_declined_offer,
         "sales_consumer_directv_stream_reason_customer_declined_offer_comment" => $request->sales_consumer_directv_stream_reason_customer_declined_offer_comment,
         "sales_consumer_directv_stream_closed_the_sale" => $request->sales_consumer_directv_stream_closed_the_sale,
         "sales_consumer_directv_stream_closed_the_sale_comment" => $request->sales_consumer_directv_stream_closed_the_sale_comment,
         /////  SALES:Consumer  HSI
         "sales_consumer_hsi" => $request->sales_consumer_hsi,
         "sales_consumer_hsi_comment" => $request->sales_consumer_hsi_comment,
         "sales_consumer_hsi_asked_delivery_questions" => $request->sales_consumer_hsi_asked_delivery_questions,
         "sales_consumer_hsi_asked_delivery_questions_comment" => $request->sales_consumer_hsi_asked_delivery_questions_comment,
         "sales_consumer_hsi_offer_products" => $request->sales_consumer_hsi_offer_products,
         "sales_consumer_hsi_offer_products_comment" => $request->sales_consumer_hsi_offer_products_comment,
         "sales_consumer_hsi_offer_tailored_based" => $request->sales_consumer_hsi_offer_tailored_based,
         "sales_consumer_hsi_offer_tailored_based_comment" => $request->sales_consumer_hsi_offer_tailored_based_comment,
         "sales_consumer_hsi_asked_for_sale" => $request->sales_consumer_hsi_asked_for_sale,
         "sales_consumer_hsi_asked_for_sale_comment" => $request->sales_consumer_hsi_asked_for_sale_comment,
         "sales_consumer_hsi_did_customer_object" => $request->sales_consumer_hsi_did_customer_object,
         "sales_consumer_hsi_did_customer_object_comment" => $request->sales_consumer_hsi_did_customer_object_comment,
         "sales_consumer_hsi_attempt_to_overcome_objections" => $request->sales_consumer_hsi_attempt_to_overcome_objections,
         "sales_consumer_hsi_attempt_to_overcome_objections_comment" => $request->sales_consumer_hsi_attempt_to_overcome_objections_comment,
         "sales_consumer_hsi_reason_customer_declined_offer" => $request->sales_consumer_hsi_reason_customer_declined_offer,
         "sales_consumer_hsi_reason_customer_declined_offer_comment" => $request->sales_consumer_hsi_reason_customer_declined_offer_comment,
         "sales_consumer_hsi_closed_the_sale" => $request->sales_consumer_hsi_closed_the_sale,
         "sales_consumer_hsi_closed_the_sale_comment" => $request->sales_consumer_hsi_closed_the_sale_comment,
         /////  SALES:Consumer  HSI FIBER
         "sales_consumer_hsi_fiber" => $request->sales_consumer_hsi_fiber,
         "sales_consumer_hsi_fiber_comment" => $request->sales_consumer_hsi_fiber_comment,
         "sales_consumer_hsi_fiber_asked_delivery_questions" => $request->sales_consumer_hsi_fiber_asked_delivery_questions,
         "sales_consumer_hsi_fiber_asked_delivery_questions_comment" => $request->sales_consumer_hsi_fiber_asked_delivery_questions_comment,
         "sales_consumer_hsi_fiber_offer_products" => $request->sales_consumer_hsi_fiber_offer_products,
         "sales_consumer_hsi_fiber_offer_products_comment" => $request->sales_consumer_hsi_fiber_offer_products_comment,
         "sales_consumer_hsi_fiber_offer_tailored_based" => $request->sales_consumer_hsi_fiber_offer_tailored_based,
         "sales_consumer_hsi_fiber_offer_tailored_based_comment" => $request->sales_consumer_hsi_fiber_offer_tailored_based_comment,
         "sales_consumer_hsi_fiber_asked_for_sale" => $request->sales_consumer_hsi_fiber_asked_for_sale,
         "sales_consumer_hsi_fiber_asked_for_sale_comment" => $request->sales_consumer_hsi_fiber_asked_for_sale_comment,
         "sales_consumer_hsi_fiber_did_customer_object" => $request->sales_consumer_hsi_fiber_did_customer_object,
         "sales_consumer_hsi_fiber_did_customer_object_comment" => $request->sales_consumer_hsi_fiber_did_customer_object_comment,
         "sales_consumer_hsi_fiber_attempt_to_overcome_objections" => $request->sales_consumer_hsi_fiber_attempt_to_overcome_objections,
         "sales_consumer_hsi_fiber_attempt_to_overcome_objections_comment" => $request->sales_consumer_hsi_fiber_attempt_to_overcome_objections_comment,
         "sales_consumer_hsi_fiber_reason_customer_declined_offer" => $request->sales_consumer_hsi_fiber_reason_customer_declined_offer,
         "sales_consumer_hsi_fiber_reason_customer_declined_offer_comment" => $request->sales_consumer_hsi_fiber_reason_customer_declined_offer_comment,
         "sales_consumer_hsi_fiber_closed_the_sale" => $request->sales_consumer_hsi_fiber_closed_the_sale,
         "sales_consumer_hsi_fiber_closed_the_sale_comment" => $request->sales_consumer_hsi_fiber_closed_the_sale_comment,
         /////  SALES:Consumer  HSI Upgrade
         "sales_consumer_hsi_upgrade" => $request->sales_consumer_hsi_upgrade,
         "sales_consumer_hsi_upgrade_comment" => $request->sales_consumer_hsi_upgrade_comment,
         "sales_consumer_hsi_upgrade_asked_delivery_questions" => $request->sales_consumer_hsi_upgrade_asked_delivery_questions,
         "sales_consumer_hsi_upgrade_asked_delivery_questions_comment" => $request->sales_consumer_hsi_upgrade_asked_delivery_questions_comment,
         "sales_consumer_hsi_upgrade_offer_products" => $request->sales_consumer_hsi_upgrade_offer_products,
         "sales_consumer_hsi_upgrade_offer_products_comment" => $request->sales_consumer_hsi_upgrade_offer_products_comment,
         "sales_consumer_hsi_upgrade_offer_tailored_based" => $request->sales_consumer_hsi_upgrade_offer_tailored_based,
         "sales_consumer_hsi_upgrade_offer_tailored_based_comment" => $request->sales_consumer_hsi_upgrade_offer_tailored_based_comment,
         "sales_consumer_hsi_upgrade_asked_for_sale" => $request->sales_consumer_hsi_upgrade_asked_for_sale,
         "sales_consumer_hsi_upgrade_asked_for_sale_comment" => $request->sales_consumer_hsi_upgrade_asked_for_sale_comment,
         "sales_consumer_hsi_upgrade_did_customer_object" => $request->sales_consumer_hsi_upgrade_did_customer_object,
         "sales_consumer_hsi_upgrade_did_customer_object_comment" => $request->sales_consumer_hsi_upgrade_did_customer_object_comment,
         "sales_consumer_hsi_upgrade_attempt_to_overcome_objections" => $request->sales_consumer_hsi_upgrade_attempt_to_overcome_objections,
         "sales_consumer_hsi_upgrade_attempt_to_overcome_objections_comment" => $request->sales_consumer_hsi_upgrade_attempt_to_overcome_objections_comment,
         "sales_consumer_hsi_upgrade_reason_customer_declined_offer" => $request->sales_consumer_hsi_upgrade_reason_customer_declined_offer,
         "sales_consumer_hsi_upgrade_reason_customer_declined_offer_comment" => $request->sales_consumer_hsi_upgrade_reason_customer_declined_offer_comment,
         "sales_consumer_hsi_upgrade_closed_the_sale" => $request->sales_consumer_hsi_upgrade_closed_the_sale,
         "sales_consumer_hsi_upgrade_closed_the_sale_comment" => $request->sales_consumer_hsi_upgrade_closed_the_sale_comment,
         /////  SALES:Consumer  IWM
         "sales_consumer_iwm" => $request->sales_consumer_iwm,
         "sales_consumer_iwm_comment" => $request->sales_consumer_iwm_comment,
         "sales_consumer_iwm_asked_delivery_questions" => $request->sales_consumer_iwm_asked_delivery_questions,
         "sales_consumer_iwm_asked_delivery_questions_comment" => $request->sales_consumer_iwm_asked_delivery_questions_comment,
         "sales_consumer_iwm_offer_products" => $request->sales_consumer_iwm_offer_products,
         "sales_consumer_iwm_offer_products_comment" => $request->sales_consumer_iwm_offer_products_comment,
         "sales_consumer_iwm_offer_tailored_based" => $request->sales_consumer_iwm_offer_tailored_based,
         "sales_consumer_iwm_offer_tailored_based_comment" => $request->sales_consumer_iwm_offer_tailored_based_comment,
         "sales_consumer_iwm_asked_for_sale" => $request->sales_consumer_iwm_asked_for_sale,
         "sales_consumer_iwm_asked_for_sale_comment" => $request->sales_consumer_iwm_asked_for_sale_comment,
         "sales_consumer_iwm_did_customer_object" => $request->sales_consumer_iwm_did_customer_object,
         "sales_consumer_iwm_did_customer_object_comment" => $request->sales_consumer_iwm_did_customer_object_comment,
         "sales_consumer_iwm_attempt_to_overcome_objections" => $request->sales_consumer_iwm_attempt_to_overcome_objections,
         "sales_consumer_iwm_attempt_to_overcome_objections_comment" => $request->sales_consumer_iwm_attempt_to_overcome_objections_comment,
         "sales_consumer_iwm_reason_customer_declined_offer" => $request->sales_consumer_iwm_reason_customer_declined_offer,
         "sales_consumer_iwm_reason_customer_declined_offer_comment" => $request->sales_consumer_iwm_reason_customer_declined_offer_comment,
         "sales_consumer_iwm_closed_the_sale" => $request->sales_consumer_iwm_closed_the_sale,
         "sales_consumer_iwm_closed_the_sale_comment" => $request->sales_consumer_iwm_closed_the_sale_comment,
         /////  SALES:Consumer  Personal Tech Pro
         "sales_consumer_personal_tech_pro" => $request->sales_consumer_personal_tech_pro,
         "sales_consumer_personal_tech_pro_comment" => $request->sales_consumer_personal_tech_pro_comment,
         "sales_consumer_personal_tech_pro_asked_delivery_questions" => $request->sales_consumer_personal_tech_pro_asked_delivery_questions,
         "sales_consumer_personal_tech_pro_asked_delivery_questions_comment" => $request->sales_consumer_personal_tech_pro_asked_delivery_questions_comment,
         "sales_consumer_personal_tech_pro_offer_products" => $request->sales_consumer_personal_tech_pro_offer_products,
         "sales_consumer_personal_tech_pro_offer_products_comment" => $request->sales_consumer_personal_tech_pro_offer_products_comment,
         "sales_consumer_personal_tech_pro_offer_tailored_based" => $request->sales_consumer_personal_tech_pro_offer_tailored_based,
         "sales_consumer_personal_tech_pro_offer_tailored_based_comment" => $request->sales_consumer_personal_tech_pro_offer_tailored_based_comment,
         "sales_consumer_personal_tech_pro_asked_for_sale" => $request->sales_consumer_personal_tech_pro_asked_for_sale,
         "sales_consumer_personal_tech_pro_asked_for_sale_comment" => $request->sales_consumer_personal_tech_pro_asked_for_sale_comment,
         "sales_consumer_personal_tech_pro_did_customer_object" => $request->sales_consumer_personal_tech_pro_did_customer_object,
         "sales_consumer_personal_tech_pro_did_customer_object_comment" => $request->sales_consumer_personal_tech_pro_did_customer_object_comment,
         "sales_consumer_personal_tech_pro_attempt_to_overcome_objections" => $request->sales_consumer_personal_tech_pro_attempt_to_overcome_objections,
         "sales_consumer_personal_tech_pro_attempt_to_overcome_objections_comment" => $request->sales_consumer_personal_tech_pro_attempt_to_overcome_objections_comment,
         "sales_consumer_personal_tech_pro_reason_customer_declined_offer" => $request->sales_consumer_personal_tech_pro_reason_customer_declined_offer,
         "sales_consumer_personal_tech_pro_reason_customer_declined_offer_comment" => $request->sales_consumer_personal_tech_pro_reason_customer_declined_offer_comment,
         "sales_consumer_personal_tech_pro_closed_the_sale" => $request->sales_consumer_personal_tech_pro_closed_the_sale,
         "sales_consumer_personal_tech_pro_closed_the_sale_comment" => $request->sales_consumer_personal_tech_pro_closed_the_sale_comment,
         /////  SALES:Consumer HughesNet
         "sales_consumer_hugesnet" => $request->sales_consumer_hugesnet,
         "sales_consumer_hugesnet_comment" => $request->sales_consumer_hugesnet_comment,
         "sales_consumer_hugesnet_asked_delivery_questions" => $request->sales_consumer_hugesnet_asked_delivery_questions,
         "sales_consumer_hugesnet_asked_delivery_questions_comment" => $request->sales_consumer_hugesnet_asked_delivery_questions_comment,
         "sales_consumer_hugesnet_offer_products" => $request->sales_consumer_hugesnet_offer_products,
         "sales_consumer_hugesnet_offer_products_comment" => $request->sales_consumer_hugesnet_offer_products_comment,
         "sales_consumer_hugesnet_offer_tailored_based" => $request->sales_consumer_hugesnet_offer_tailored_based,
         "sales_consumer_hugesnet_offer_tailored_based_comment" => $request->sales_consumer_hugesnet_offer_tailored_based_comment,
         "sales_consumer_hugesnet_asked_for_sale" => $request->sales_consumer_hugesnet_asked_for_sale,
         "sales_consumer_hugesnet_asked_for_sale_comment" => $request->sales_consumer_hugesnet_asked_for_sale_comment,
         "sales_consumer_hugesnet_did_customer_object" => $request->sales_consumer_hugesnet_did_customer_object,
         "sales_consumer_hugesnet_did_customer_object_comment" => $request->sales_consumer_hugesnet_did_customer_object_comment,
         "sales_consumer_hugesnet_attempt_to_overcome_objections" => $request->sales_consumer_hugesnet_attempt_to_overcome_objections,
         "sales_consumer_hugesnet_attempt_to_overcome_objections_comment" => $request->sales_consumer_hugesnet_attempt_to_overcome_objections_comment,
         "sales_consumer_hugesnet_reason_customer_declined_offer" => $request->sales_consumer_hugesnet_reason_customer_declined_offer,
         "sales_consumer_hugesnet_reason_customer_declined_offer_comment" => $request->sales_consumer_hugesnet_reason_customer_declined_offer_comment,
         "sales_consumer_hugesnet_closed_the_sale" => $request->sales_consumer_hugesnet_closed_the_sale,
         "sales_consumer_hugesnet_closed_the_sale_comment" => $request->sales_consumer_hugesnet_closed_the_sale_comment,
         /////  SALES:Consumer Wifi Extender
         "sales_consumer_wifi_extender" => $request->sales_consumer_wifi_extender,
         "sales_consumer_wifi_extender_comment" => $request->sales_consumer_wifi_extender_comment,
         "sales_consumer_wifi_extender_asked_delivery_questions" => $request->sales_consumer_wifi_extender_asked_delivery_questions,
         "sales_consumer_wifi_extender_asked_delivery_questions_comment" => $request->sales_consumer_wifi_extender_asked_delivery_questions_comment,
         "sales_consumer_wifi_extender_offer_products" => $request->sales_consumer_wifi_extender_offer_products,
         "sales_consumer_wifi_extender_offer_products_comment" => $request->sales_consumer_wifi_extender_offer_products_comment,
         "sales_consumer_wifi_extender_offer_tailored_based" => $request->sales_consumer_wifi_extender_offer_tailored_based,
         "sales_consumer_wifi_extender_offer_tailored_based_comment" => $request->sales_consumer_wifi_extender_offer_tailored_based_comment,
         "sales_consumer_wifi_extender_asked_for_sale" => $request->sales_consumer_wifi_extender_asked_for_sale,
         "sales_consumer_wifi_extender_asked_for_sale_comment" => $request->sales_consumer_wifi_extender_asked_for_sale_comment,
         "sales_consumer_wifi_extender_did_customer_object" => $request->sales_consumer_wifi_extender_did_customer_object,
         "sales_consumer_wifi_extender_did_customer_object_comment" => $request->sales_consumer_wifi_extender_did_customer_object_comment,
         "sales_consumer_wifi_extender_attempt_to_overcome_objections" => $request->sales_consumer_wifi_extender_attempt_to_overcome_objections,
         "sales_consumer_wifi_extender_attempt_to_overcome_objections_comment" => $request->sales_consumer_wifi_extender_attempt_to_overcome_objections_comment,
         "sales_consumer_wifi_extender_reason_customer_declined_offer" => $request->sales_consumer_wifi_extender_reason_customer_declined_offer,
         "sales_consumer_wifi_extender_reason_customer_declined_offer_comment" => $request->sales_consumer_wifi_extender_reason_customer_declined_offer_comment,
         "sales_consumer_wifi_extender_closed_the_sale" => $request->sales_consumer_wifi_extender_closed_the_sale,
         "sales_consumer_wifi_extender_closed_the_sale_comment" => $request->sales_consumer_wifi_extender_closed_the_sale_comment,
          /////  SALES:Consumer Secure WiFi
          "sales_consumer_secure_wifi" => $request->sales_consumer_secure_wifi,
          "sales_consumer_secure_wifi_comment" => $request->sales_consumer_secure_wifi_comment,
          "sales_consumer_secure_wifi_asked_delivery_questions" => $request->sales_consumer_secure_wifi_asked_delivery_questions,
          "sales_consumer_secure_wifi_asked_delivery_questions_comment" => $request->sales_consumer_secure_wifi_asked_delivery_questions_comment,
          "sales_consumer_secure_wifi_offer_products" => $request->sales_consumer_secure_wifi_offer_products,
          "sales_consumer_secure_wifi_offer_products_comment" => $request->sales_consumer_secure_wifi_offer_products_comment,
          "sales_consumer_secure_wifi_offer_tailored_based" => $request->sales_consumer_secure_wifi_offer_tailored_based,
          "sales_consumer_secure_wifi_offer_tailored_based_comment" => $request->sales_consumer_secure_wifi_offer_tailored_based_comment,
          "sales_consumer_secure_wifi_asked_for_sale" => $request->sales_consumer_secure_wifi_asked_for_sale,
          "sales_consumer_secure_wifi_asked_for_sale_comment" => $request->sales_consumer_secure_wifi_asked_for_sale_comment,
          "sales_consumer_secure_wifi_did_customer_object" => $request->sales_consumer_secure_wifi_did_customer_object,
          "sales_consumer_secure_wifi_did_customer_object_comment" => $request->sales_consumer_secure_wifi_did_customer_object_comment,
          "sales_consumer_secure_wifi_attempt_to_overcome_objections" => $request->sales_consumer_secure_wifi_attempt_to_overcome_objections,
          "sales_consumer_secure_wifi_attempt_to_overcome_objections_comment" => $request->sales_consumer_secure_wifi_attempt_to_overcome_objections_comment,
          "sales_consumer_secure_wifi_reason_customer_declined_offer" => $request->sales_consumer_secure_wifi_reason_customer_declined_offer,
          "sales_consumer_secure_wifi_reason_customer_declined_offer_comment" => $request->sales_consumer_secure_wifi_reason_customer_declined_offer_comment,
          "sales_consumer_secure_wifi_closed_the_sale" => $request->sales_consumer_secure_wifi_closed_the_sale,
          "sales_consumer_secure_wifi_closed_the_sale_comment" => $request->sales_consumer_secure_wifi_closed_the_sale_comment,
        ];
        $salesSBG = [
          "sales_sbg_agent_lead_cbb" => $request->sales_sbg_agent_lead_cbb,
          "sales_sbg_agent_lead_cbb_comment" => $request->sales_sbg_agent_lead_cbb_comment,
        ];

        $saveConsumer = [
            /////  SAVE:Consumer  Access Line
            "save_consumer_call_require_save_attemp" => $request->save_consumer_call_require_save_attemp,
            "save_consumer_call_require_save_attemp_comment" => $request->save_consumer_call_require_save_attemp_comment,
            "save_consumer_agent_reviewd_customer_account" => $request->save_consumer_agent_reviewd_customer_account,
            "save_consumer_agent_reviewd_customer_account_comment" => $request->save_consumer_agent_reviewd_customer_account_comment,
            "save_consumer_primary_reason_to_disconnect" => $request->save_consumer_primary_reason_to_disconnect,
            "save_consumer_primary_reason_to_disconnect_comment" => $request->save_consumer_primary_reason_to_disconnect_comment,
            "save_consumer_agent_identified_secondary_reason" => $request->save_consumer_agent_identified_secondary_reason,
            "save_consumer_agent_identified_secondary_reason_comment" => $request->save_consumer_agent_identified_secondary_reason_comment,
            "save_consumer_agent_identified_secondary_reason_yes" => $request->save_consumer_agent_identified_secondary_reason_yes,
            "save_consumer_agent_identified_secondary_reason_yes_comment" => $request->save_consumer_agent_identified_secondary_reason_yes_comment,
            "save_consumer_agent_ask_discovery_question_to_determine" => $request->save_consumer_agent_ask_discovery_question_to_determine,
            "save_consumer_agent_ask_discovery_question_to_determine_comment" => $request->save_consumer_agent_ask_discovery_question_to_determine_comment,
            "save_consumer_agent_save_consumer" => $request->save_consumer_agent_save_consumer,
            "save_consumer_agent_save_consumer_comment" => $request->save_consumer_agent_save_consumer_comment,
            "save_consumer_save_offer_presented" => $request->save_consumer_save_offer_presented,
            "save_consumer_save_offer_presented_comment" => $request->save_consumer_save_offer_presented_comment,
            "save_consumer_agent_lead_with_correct_offer" => $request->save_consumer_agent_lead_with_correct_offer,
            "save_consumer_agent_lead_with_correct_offer_comment" => $request->save_consumer_agent_lead_with_correct_offer_comment,
            "save_consumer_agent_lead_with_no_correct_offer" => $request->save_consumer_agent_lead_with_no_correct_offer,
            "save_consumer_agent_lead_with_no_correct_offer_comment" => $request->save_consumer_agent_lead_with_no_correct_offer_comment,
            "save_consumer_agent_present_appropriate_fallback_offer" => $request->save_consumer_agent_present_appropriate_fallback_offer,
            "save_consumer_agent_present_appropriate_fallback_offer_comment" => $request->save_consumer_agent_present_appropriate_fallback_offer_comment,
            "save_consumer_save_offer_accepted" => $request->save_consumer_save_offer_accepted,
            "save_consumer_save_offer_accepted_comment" => $request->save_consumer_save_offer_accepted_comment,
            "save_consumer_agent_offer_bill_cycle_end_date" => $request->save_consumer_agent_offer_bill_cycle_end_date,
            "save_consumer_agent_offer_bill_cycle_end_date_comment" => $request->save_consumer_agent_offer_bill_cycle_end_date_comment,
            "save_consumer_agent_obeserved_using_assumptive_language" => $request->save_consumer_agent_obeserved_using_assumptive_language,
            "save_consumer_agent_obeserved_using_assumptive_language_comment" => $request->save_consumer_agent_obeserved_using_assumptive_language_comment,
            "save_consumer_agent_save_offers_appropriately" => $request->save_consumer_agent_save_offers_appropriately,
            "save_consumer_agent_save_offers_appropriately_comment" => $request->save_consumer_agent_save_offers_appropriately_comment,
            /////  SAVE:Consumer  HSI
            "save_consumer_hsi_make_save_attempt" => $request->save_consumer_hsi_make_save_attempt,
            "save_consumer_hsi_hsi_make_save_attempt_comment" => $request->save_consumer_hsi_hsi_make_save_attempt_comment,
            "save_consumer_hsi_did_customer_object" => $request->save_consumer_hsi_did_customer_object,
            "save_consumer_hsi_did_customer_object_comment" => $request->save_consumer_hsi_did_customer_object_comment,
            "save_consumer_hsi_attemp_to_overcome_object_with_regards" => $request->save_consumer_hsi_attemp_to_overcome_object_with_regards,
            "save_consumer_hsi_attemp_to_overcome_object_with_regards_comment" => $request->save_consumer_hsi_attemp_to_overcome_object_with_regards_comment,
            "save_consumer_hsi_reason_customer_declined_offer" => $request->save_consumer_hsi_reason_customer_declined_offer,
            "save_consumer_hsi_reason_customer_declined_offer_comment" => $request->save_consumer_hsi_reason_customer_declined_offer_comment,
            "save_consumer_hsi_save_the_product" => $request->save_consumer_hsi_save_the_product,
            "save_consumer_hsi_save_the_product_comment" => $request->save_consumer_hsi_save_the_product_comment,
            /////  SAVE:Consumer  Access Line
            "save_consumer_access_line_make_save_attempt" => $request->save_consumer_access_line_make_save_attempt,
            "save_consumer_access_line_make_save_attempt_comment" => $request->save_consumer_access_line_make_save_attempt_comment,
            "save_consumer_access_line_did_customer_object" => $request->save_consumer_access_line_did_customer_object,
            "save_consumer_access_line_did_customer_object_comment" => $request->save_consumer_access_line_did_customer_object_comment,
            "save_consumer_access_line_attemp_to_overcome_object_with_regards" => $request->save_consumer_access_line_attemp_to_overcome_object_with_regards,
            "save_consumer_access_line_attemp_to_overcome_object_with_regards_comment" => $request->save_consumer_access_line_attemp_to_overcome_object_with_regards_comment,
            "save_consumer_access_line_reason_customer_declined_offer" => $request->save_consumer_access_line_reason_customer_declined_offer,
            "save_consumer_access_line_reason_customer_declined_offer_comment" => $request->save_consumer_access_line_reason_customer_declined_offer_comment,
            "save_consumer_access_line_save_the_product" => $request->save_consumer_access_line_save_the_product,
            "save_consumer_access_line_save_the_product_comment" => $request->save_consumer_access_line_save_the_product_comment,
            /////  SAVE:Consumer  Other
            "save_consumer_other_make_save_attempt" => $request->save_consumer_other_make_save_attempt,
            "save_consumer_other_make_save_attempt_comment" => $request->save_consumer_other_make_save_attempt_comment,
            "save_consumer_other_did_customer_object" => $request->save_consumer_other_did_customer_object,
            "save_consumer_other_did_customer_object_comment" => $request->save_consumer_other_did_customer_object_comment,
            "save_consumer_other_attemp_to_overcome_object_with_regards" => $request->save_consumer_other_attemp_to_overcome_object_with_regards,
            "save_consumer_other_attemp_to_overcome_object_with_regards_comment" => $request->save_consumer_other_attemp_to_overcome_object_with_regards_comment,
            "save_consumer_other_reason_customer_declined_offer" => $request->save_consumer_other_reason_customer_declined_offer,
            "save_consumer_other_reason_customer_declined_offer_comment" => $request->save_consumer_other_reason_customer_declined_offer_comment,
            "save_consumer_other_save_the_product" => $request->save_consumer_other_save_the_product,
            "save_consumer_other_save_the_product_comment" => $request->save_consumer_other_save_the_product_comment,
            ];
            $savesSBG = [
              "save_sbg_call_require_save_attempt" => $request->save_sbg_call_require_save_attempt,
              "save_sbg_call_require_save_attempt_comment" => $request->save_sbg_call_require_save_attempt_comment,
              "save_sbg_agent_complete_account_review" => $request->save_sbg_agent_complete_account_review,
              "save_sbg_agent_complete_account_review_comment" => $request->save_sbg_agent_complete_account_review_comment,
              "save_sbg_agent_ask_discovery_question_to_determine" => $request->save_sbg_agent_ask_discovery_question_to_determine,
              "save_sbg_agent_ask_discovery_question_to_determine_comment" => $request->save_sbg_agent_ask_discovery_question_to_determine_comment,
              "save_sbg_agent_save_customer" => $request->save_sbg_agent_save_customer,
              "save_sbg_agent_save_customer_comment" => $request->save_sbg_agent_save_customer_comment,
              "save_sbg_agent_lead_with_correct_offer" => $request->save_sbg_agent_lead_with_correct_offer,
              "save_sbg_agent_lead_with_correct_offer_comment" => $request->save_sbg_agent_lead_with_correct_offer_comment,
              "save_sbg_agent_no_lead_with_correct_offer" => $request->save_sbg_agent_no_lead_with_correct_offer,
              "save_sbg_agent_no_lead_with_correct_offer_comment" => $request->save_sbg_agent_no_lead_with_correct_offer_comment,
              "save_sbg_agent_present_fallback_offer" => $request->save_sbg_agent_present_fallback_offer,
              "save_sbg_agent_present_fallback_offer_comment" => $request->save_sbg_agent_present_fallback_offer_comment,
              "save_sbg_agent_present_no_fallback_offer" => $request->save_sbg_agent_present_no_fallback_offer,
              "save_sbg_agent_present_no_fallback_offer_comment" => $request->save_sbg_agent_present_no_fallback_offer_comment,
              "save_sbg_save_offer_accepted" => $request->save_sbg_save_offer_accepted,
              "save_sbg_save_offer_accepted_comment" => $request->save_sbg_save_offer_accepted_comment,
              "save_sbg_agent_offer_bill_cycle_end_date" => $request->save_sbg_agent_offer_bill_cycle_end_date,
              "save_sbg_agent_offer_bill_cycle_end_date_comment" => $request->save_sbg_agent_offer_bill_cycle_end_date_comment,
              "save_sbg_agent_observed_using_assumptive_language" => $request->save_sbg_agent_observed_using_assumptive_language,
              "save_sbg_agent_observed_using_assumptive_language_comment" => $request->save_sbg_agent_observed_using_assumptive_language_comment,
              "save_sbg_agent_save_offers_appropriately" => $request->save_sbg_agent_save_offers_appropriately,
              "save_sbg_agent_save_offers_appropriately_comment" => $request->save_sbg_agent_save_offers_appropriately_comment,
              /////  SAVE:SBG  Access Line
              "save_sbg_access_line_make_save_attempt" => $request->save_sbg_access_line_make_save_attempt,
              "save_sbg_access_line_make_save_attempt_comment" => $request->save_sbg_access_line_make_save_attempt_comment,
              "save_sbg_access_line_did_customer_object" => $request->save_sbg_access_line_did_customer_object,
              "save_sbg_access_line_did_customer_object_comment" => $request->save_sbg_access_line_did_customer_object_comment,
              "save_sbg_access_line_attemp_to_overcome_object_with_regards" => $request->save_sbg_access_line_attemp_to_overcome_object_with_regards,
              "save_sbg_access_line_attemp_to_overcome_object_with_regards_comment" => $request->save_sbg_access_line_attemp_to_overcome_object_with_regards_comment,
              "save_sbg_access_line_reason_customer_declined_offer" => $request->save_sbg_access_line_reason_customer_declined_offer,
              "save_sbg_access_line_reason_customer_declined_offer_comment" => $request->save_sbg_access_line_reason_customer_declined_offer_comment,
              "save_sbg_access_line_save_the_product" => $request->save_sbg_access_line_save_the_product,
              "save_sbg_access_line_save_the_product_comment" => $request->save_sbg_access_line_save_the_product_comment,
               /////  SAVE:SBG  HSI
              "save_sbg_hsi_make_save_attempt" => $request->save_sbg_hsi_make_save_attempt,
              "save_sbg_hsi_make_save_attempt_comment" => $request->save_sbg_hsi_make_save_attempt_comment,
              "save_sbg_hsi_did_customer_object" => $request->save_sbg_hsi_did_customer_object,
              "save_sbg_hsi_did_customer_object_comment" => $request->save_sbg_hsi_did_customer_object_comment,
              "save_sbg_hsi_attemp_to_overcome_object_with_regards" => $request->save_sbg_hsi_attemp_to_overcome_object_with_regards,
              "save_sbg_hsi_attemp_to_overcome_object_with_regards_comment" => $request->save_sbg_hsi_attemp_to_overcome_object_with_regards_comment,
              "save_sbg_hsi_reason_customer_declined_offer" => $request->save_sbg_hsi_reason_customer_declined_offer,
              "save_sbg_hsi_reason_customer_declined_offer_comment" => $request->save_sbg_hsi_reason_customer_declined_offer_comment,
              "save_sbg_hsi_save_the_product" => $request->save_sbg_hsi_save_the_product,
              "save_sbg_hsi_save_the_product_comment" => $request->save_sbg_hsi_save_the_product_comment,
               /////  SAVE:SBG  OTHER
              "save_sbg_other_make_save_attempt" => $request->save_sbg_other_make_save_attempt,
              "save_sbg_other_make_save_attempt_comment" => $request->save_sbg_other_make_save_attempt_comment,
              "save_sbg_other_did_customer_object" => $request->save_sbg_other_did_customer_object,
              "save_sbg_other_did_customer_object_comment" => $request->save_sbg_other_did_customer_object_comment,
              "save_sbg_other_attemp_to_overcome_object_with_regards" => $request->save_sbg_other_attemp_to_overcome_object_with_regards,
              "save_sbg_other_attemp_to_overcome_object_with_regards_comment" => $request->save_sbg_other_attemp_to_overcome_object_with_regards_comment,
              "save_sbg_other_reason_customer_declined_offer" => $request->save_sbg_other_reason_customer_declined_offer,
              "save_sbg_other_reason_customer_declined_offer_comment" => $request->save_sbg_other_reason_customer_declined_offer_comment,
              "save_sbg_other_save_the_product" => $request->save_sbg_other_save_the_product,
              "save_sbg_other_save_the_product_comment" => $request->save_sbg_other_save_the_product_comment,
            ];

            $services = [
                "service_representative_verify_and_update_all_applicable_credit_related_information" => $request->service_representative_verify_and_update_all_applicable_credit_related_information,
                "service_representative_verify_and_update_all_applicable_credit_related_information_comment" => $request->service_representative_verify_and_update_all_applicable_credit_related_information_comment,
                "service_representative_verify_and_update_all_applicable_credit_related_information_score" => $request->service_representative_verify_and_update_all_applicable_credit_related_information_score,
                "service_agent_paraphrase_reassure_reason_for_call" => $request->service_agent_paraphrase_reassure_reason_for_call,
                "service_agent_paraphrase_reassure_reason_for_call_comment" => $request->service_agent_paraphrase_reassure_reason_for_call_comment,
                "service_agent_paraphrase_reassure_reason_for_call_score" => $request->service_agent_paraphrase_reassure_reason_for_call_score,
                "service_agent_follow_top_down_collection_process" => $request->service_agent_follow_top_down_collection_process,
                "service_agent_follow_top_down_collection_process_comment" => $request->service_agent_follow_top_down_collection_process_comment,
                "service_agent_follow_top_down_collection_process_score" => $request->service_agent_follow_top_down_collection_process_score,
                "service_agent_follow_top_down_collection_process_no" => $request->service_agent_follow_top_down_collection_process_no,
                "service_agent_follow_top_down_collection_process_no_comment" => $request->service_agent_follow_top_down_collection_process_no_comment,
                "service_obonly_offer_reestablish_centurylink_service" => $request->service_obonly_offer_reestablish_centurylink_service,
                "service_obonly_offer_reestablish_centurylink_service_comment" => $request->service_obonly_offer_reestablish_centurylink_service_comment,
                "service_obonly_offer_reestablish_centurylink_service_score" => $request->service_obonly_offer_reestablish_centurylink_service_score,
                "service_obonly_offer_reestablish_centurylink_service_no" => $request->service_obonly_offer_reestablish_centurylink_service_no,
                "service_obonly_offer_reestablish_centurylink_service_no_comment" => $request->service_obonly_offer_reestablish_centurylink_service_no_comment,
                "service_did_agent_offer_autopay" => $request->service_did_agent_offer_autopay,
                "service_did_agent_offer_autopay_comment" => $request->service_did_agent_offer_autopay_comment,
                "service_did_agent_offer_autopay_score" => $request->service_did_agent_offer_autopay_score,
                "service_did_agent_offer_autopay_no" => $request->service_did_agent_offer_autopay_no,
                "service_did_agent_offer_autopay_no_comment" => $request->service_did_agent_offer_autopay_no_comment,
                "service_did_agent_offer_paperless_billing" => $request->service_did_agent_offer_paperless_billing,
                "service_did_agent_offer_paperless_billing_comment" => $request->service_did_agent_offer_paperless_billing_comment,
                "service_did_agent_offer_paperless_billing_score" => $request->service_did_agent_offer_paperless_billing_score,
                "service_did_agent_offer_paperless_billing_no" => $request->service_did_agent_offer_paperless_billing_no,
                "service_did_agent_offer_paperless_billing_no_comment" => $request->service_did_agent_offer_paperless_billing_no_comment,
                "service_did_agent_structure_scheduled_payment_during_call" => $request->service_did_agent_structure_scheduled_payment_during_call,
                "service_did_agent_structure_scheduled_payment_during_call_comment" => $request->service_did_agent_structure_scheduled_payment_during_call_comment,
                "service_did_agent_structure_scheduled_payment_during_call_score" => $request->service_did_agent_structure_scheduled_payment_during_call_score,
                "service_did_agent_structure_scheduled_payment_during_call_yes" => $request->service_did_agent_structure_scheduled_payment_during_call_yes,
                "service_did_agent_structure_scheduled_payment_during_call_yes_comment" => $request->service_did_agent_structure_scheduled_payment_during_call_yes_comment,
                "service_did_agent_restore_services_on_promise_to_pay_dusring_call" => $request->service_did_agent_restore_services_on_promise_to_pay_dusring_call,
                "service_did_agent_restore_services_on_promise_to_pay_dusring_call_comment" => $request->service_did_agent_restore_services_on_promise_to_pay_dusring_call_comment,
                "service_did_agent_restore_services_on_promise_to_pay_dusring_call_score" => $request->service_did_agent_restore_services_on_promise_to_pay_dusring_call_score,
                "service_did_agent_restore_services_on_promise_to_pay_dusring_call_yes" => $request->service_did_agent_restore_services_on_promise_to_pay_dusring_call_yes,
                "service_did_agent_restore_services_on_promise_to_pay_dusring_call_yes_comment" => $request->service_did_agent_restore_services_on_promise_to_pay_dusring_call_yes_comment,
                "service_did_agent_explain_consequences" => $request->service_did_agent_explain_consequences,
                "service_did_agent_explain_consequences_comment" => $request->service_did_agent_explain_consequences_comment,
                "service_did_agent_explain_consequences_score" => $request->service_did_agent_explain_consequences_score,
                "service_did_agent_explain_consequences_no" => $request->service_did_agent_explain_consequences_no,
                "service_did_agent_explain_consequences_no_comment" => $request->service_did_agent_explain_consequences_no_comment,
                "service_did_agent_follow_applicable_collections_process" => $request->service_did_agent_follow_applicable_collections_process,
                "service_did_agent_follow_applicable_collections_process_comment" => $request->service_did_agent_follow_applicable_collections_process_comment,
                "service_did_agent_follow_applicable_collections_process_score" => $request->service_did_agent_follow_applicable_collections_process_score,
                "service_did_agent_follow_applicable_collections_process_no" => $request->service_did_agent_follow_applicable_collections_process_no,
                "service_did_agent_follow_applicable_collections_process_no_comment" => $request->service_did_agent_follow_applicable_collections_process_no_comment,
                "service_did_agent_advice_total_balance_both_present_past" => $request->service_did_agent_advice_total_balance_both_present_past,
                "service_did_agent_advice_total_balance_both_present_past_comment" => $request->service_did_agent_advice_total_balance_both_present_past_comment,
                "service_did_agent_advice_total_balance_both_present_past_no" => $request->service_did_agent_advice_total_balance_both_present_past_no,
                "service_did_agent_advice_total_balance_both_present_past_no_comment" => $request->service_did_agent_advice_total_balance_both_present_past_no_comment,
                "service_did_agent_fully_resolved_reason_for_call" => $request->service_did_agent_fully_resolved_reason_for_call,
                "service_did_agent_fully_resolved_reason_for_call_comment" => $request->service_did_agent_fully_resolved_reason_for_call_comment,
                "service_did_agent_fully_resolved_reason_for_call_score" => $request->service_did_agent_fully_resolved_reason_for_call_score,
                "service_did_agent_fully_resolved_reason_for_call_no" => $request->service_did_agent_fully_resolved_reason_for_call_no,
                "service_did_agent_fully_resolved_reason_for_call_no_comment" => $request->service_did_agent_fully_resolved_reason_for_call_no_comment,
                "service_did_agent_maintain_appropriate_communication_style" => $request->service_did_agent_maintain_appropriate_communication_style,
                "service_did_agent_maintain_appropriate_communication_style_comment" => $request->service_did_agent_maintain_appropriate_communication_style_comment,
                "service_did_agent_maintain_appropriate_communication_style_no" => $request->service_did_agent_maintain_appropriate_communication_style_no,
                "service_did_agent_maintain_appropriate_communication_style_no_comment" => $request->service_did_agent_maintain_appropriate_communication_style_no_comment,
                "service_was_customer_transferred_to_service_agent" => $request->service_was_customer_transferred_to_service_agent,
                "service_was_customer_transferred_to_service_agent_comment" => $request->service_was_customer_transferred_to_service_agent_comment,
              ];

              $compliance = [
                "compliance_agent_follow_all_applicable_regulatory_compliance" => $request->compliance_agent_follow_all_applicable_regulatory_compliance,
                "compliance_agent_follow_all_applicable_regulatory_compliance_comment" => $request->compliance_agent_follow_all_applicable_regulatory_compliance_comment,
                "compliance_agent_follow_all_applicable_regulatory_compliance_no" => $request->compliance_agent_follow_all_applicable_regulatory_compliance_no,
                "compliance_agent_follow_all_applicable_regulatory_compliance_no_comment" => $request->compliance_agent_follow_all_applicable_regulatory_compliance_no_comment,
                "compliance_agent_present_all_applicable_related_rccs" => $request->compliance_agent_present_all_applicable_related_rccs,
                "compliance_agent_present_all_applicable_related_rccs_comment" => $request->compliance_agent_present_all_applicable_related_rccs_comment,
                "compliance_agent_present_all_applicable_related_rccs_score" => $request->compliance_agent_present_all_applicable_related_rccs_score,
                "compliance_delivery_method_avaliable" => $request->compliance_delivery_method_avaliable,
                "compliance_delivery_method_avaliable_comment" => $request->compliance_delivery_method_avaliable_comment,
                "compliance_delivery_method_declined" => $request->compliance_delivery_method_declined,
                "compliance_delivery_method_declined_comment" => $request->compliance_delivery_method_declined_comment,
                "compliance_delivery_method_used" => $request->compliance_delivery_method_used,
                "compliance_delivery_method_used_comment" => $request->compliance_delivery_method_used_comment,
                "compliance_agent_verify_sms_number_for_text" => $request->compliance_agent_verify_sms_number_for_text,
                "compliance_agent_verify_sms_number_for_text_comment" => $request->compliance_agent_verify_sms_number_for_text_comment,
                "compliance_agent_verify_sms_number_for_text_score" => $request->compliance_agent_verify_sms_number_for_text_score,
                "compliance_rccs_sent_using_send_rcc_methods" => $request->compliance_rccs_sent_using_send_rcc_methods,
                "compliance_rccs_sent_using_send_rcc_methods_comment" => $request->compliance_rccs_sent_using_send_rcc_methods_comment,
                "compliance_needed_intro_phrase_used_text" => $request->compliance_neededintro_phrase_used_text,
                "compliance_needed_intro_phrase_used_text_comment" => $request->compliance_needed_intro_phrase_used_text_comment,
                "compliance_needed_intro_phrase_used_email" => $request->compliance_needed_intro_phrase_used_email,
                "compliance_needed_intro_phrase_used_email_comment" => $request->compliance_needed_intro_phrase_used_email_comment,
                "compliance_needed_intro_phrase_used_audio" => $request->compliance_needed_intro_phrase_used__audio,
                "compliance_needed_intro_phrase_used_audio_comment" => $request->compliance_needed_intro_phrase_used_audio_comment,
                "compliance_rcc_issue_identify" => $request->compliance_rcc_issue_identify,
                "compliance_rcc_issue_identify_comment" => $request->compliance_rcc_issue_identify_comment,
                "compliance_disclosure_issue_tracking" => [
                    "1st_2nd_invoice" => $request->first_second_invoice,
                    "additional_otc" => $request->additional_otc,
                    "auto_pay" => $request->auto_pay,
                    "bulk_upgrades_cons_only" => $request->bulk_upgrades_cons_only,
                    "closer_cons_only" => $request->closer_cons_only,
                    "confirmation_letter" => $request->confirmation_letter,
                    "cyber_shield" => $request->cyber_shield,
                    "digital_home_phone" => $request->digital_home_phone,
                    "directTV" => $request->directTV,
                    "discount_offer_no_expire" => $request->discount_offer_no_expire,
                    "discount_offer_expire" => $request->discount_offer_expire,
                    "acp_cons_only" => $request->acp_cons_only,
                    "fees_surcharges" => $request->fees_surcharges,
                    "installations" => $request->installations,
                    "landlord_permissions" => $request->landlord_permissions,
                    "lease_agreements" => $request->lease_agreements,
                    "mrc_rcc" => $request->mrc_rcc,
                    "no_mrc_impact" => $request->no_mrc_impact,
                    "otc_rcc" => $request->otc_rcc,
                    "other_rcc" => $request->other_rcc,
                    "pfl_rcc_cons_only" => $request->pfl_rcc_cons_only,
                    "prem_attach_sbg_only" => $request->prem_attach_sbg_only,
                    "promo_limitations" => $request->promo_limitations,
                    "prorated_charges_credits" => $request->prorated_charges_credits,
                    "quote_error" => $request->quote_error,
                    "rcc_refusal" => $request->rcc_refusal,
                    "ret_ap_paperless" => $request->ret_ap_paperless,
                    "ret_disc_landline" => $request->ret_disc_landline,
                    "ret_final_bill_prorated_charges" => $request->ret_final_bill_prorated_charges,
                    "ret_lease_equip" => $request->ret_lease_equip,
                    "reward_card" => $request->reward_card,
                    "service_agreements" => $request->service_agreements,
                    "simple_pay_mrc" => $request->simple_pay_mrc,
                    "simple_pay_otc" => $request->simple_pay_otc,
                    "state_billed_hsi_offer_cons_only" => $request->state_billed_hsi_offer_cons_only,
                    "state_billed_promo_limitations_cons_only" => $request->state_billed_promo_limitations_cons_only,
                    "taxes_estimate" => $request->taxes_estimate,
                    "taxes_no_estimate" => $request->taxes_no_estimate,
                    "term_etf" => $request->term_etf,
                    "vc_hsi" => $request->vc_hsi,
                    "vc_status" => $request->vc_status,
                    "vivial" => $request->vivial,
                    "rate_changes" => $request->rate_changes,
                    "pre_use_services" => $request->pre_use_services,
                    "late_fees" => $request->late_fees,
                    "third_party_services" => $request->third_party_services,
                    "pre_payment_with_debit_credit" => $request->pre_payment_with_debit_credit,
                ],
                "compliance_agent_on_orders_and_system" => $request->compliance_agent_on_orders_and_system,
                "compliance_agent_on_orders_and_system_comment" => $request->compliance_agent_on_orders_and_system_comment,
                "compliance_agent_on_orders_and_system_no_etry_errors" => $request->compliance_agent_on_orders_and_system_no_etry_errors,
                "compliance_agent_on_orders_and_system_no_etry_errors_comment" => $request->compliance_agent_on_orders_and_system_no_etry_errors_comment,
                "compliance_agent_use_verbatim_call_flow_statments" => $request->compliance_agent_use_verbatim_call_flow_statments,
                "compliance_agent_use_verbatim_call_flow_statments_comment" => $request->compliance_agent_use_verbatim_call_flow_statments_comment,
                "compliance_agent_use_verbatim_call_flow_statments_no_etry_errors" => $request->compliance_agent_use_verbatim_call_flow_statments_no_etry_errors,
                "compliance_agent_use_verbatim_call_flow_statments_no_etry_errors_comment" => $request->compliance_agent_use_verbatim_call_flow_statments_no_etry_errors_comment,
              ];

              $closing = [
                "closing_did_agent_meet_recap_expectations" => $request->closing_did_agent_meet_recap_expectations,
                "closing_did_agent_meet_recap_expectations_comment" => $request->closing_did_agent_meet_recap_expectations_comment,
                "closing_did_agent_meet_no_recap_expectations" => $request->closing_did_agent_meet_no_recap_expectations,
                "closing_did_agent_meet_no_recap_expectations_comment" => $request->closing_did_agent_meet_no_recap_expectations_comment,
                "closing_did_agent_advice_customer_for_nps_survey" => $request->closing_did_agent_advice_customer_for_nps_survey,
                "closing_did_agent_advice_customer_for_nps_survey_comment" => $request->closing_did_agent_advice_customer_for_nps_survey_comment,
                "closing_did_agent_advice_customer_for_no_nps_survey" => $request->closing_did_agent_advice_customer_for_no_nps_survey,
                "closing_did_agent_advice_customer_for_no_nps_survey_comment" => $request->closing_did_agent_advice_customer_for_no_nps_survey_comment,
                "closing_based_on_call_with_customer_account_notations" => $request->closing_based_on_call_with_customer_account_notations,
                "closing_based_on_call_with_customer_account_notations_comment" => $request->closing_based_on_call_with_customer_account_notations_comment,
                "closing_based_on_call_with_customer_account_notations_score" => $request->closing_based_on_call_with_customer_account_notations_score,
                "closing_based_on_call_with_customer_no_account_notations" => $request->closing_based_on_call_with_customer_no_account_notations,
                "closing_based_on_call_with_customer_no_account_notations_comment" => $request->closing_based_on_call_with_customer_no_account_notations_comment,
                "closing_ask_for_additional_concern" => $request->closing_ask_for_additional_concern,
                "closing_ask_for_additional_concern_comment" => $request->closing_ask_for_additional_concern_comment
              ];

              $transfer_hold = [
                "transfer_hold_did_agent_demonstrate_integrity_during_customer_contact" => $request->transfer_hold_did_agent_follow_call_handling_process,
                "transfer_hold_did_agent_follow_call_handling_process_comment" => $request->transfer_hold_did_agent_follow_call_handling_process_comment,
                "transfer_hold_did_agent_follow_call_handling_process_score" => $request->transfer_hold_did_agent_follow_call_handling_process_score,
                "transfer_hold_did_agent_follow_no_call_handling_process" => $request->transfer_hold_did_agent_follow_no_call_handling_process,
                "transfer_hold_did_agent_follow_no_call_handling_process_comment" => $request->transfer_hold_did_agent_follow_no_call_handling_process_comment,
                "transfer_hold_call_was_transfered" => $request->transfer_hold_call_was_transfered,
                "transfer_hold_call_was_transfered_comment" => $request->transfer_hold_call_was_transfered_comment,
                "transfer_hold_did_agent_follow_hold_process" => $request->transfer_hold_did_agent_follow_hold_process,
                "transfer_hold_did_agent_follow_hold_process_comment" => $request->transfer_hold_did_agent_follow_hold_process_comment,
                "transfer_hold_did_agent_follow_no_hold_process" => $request->transfer_hold_did_agent_follow_no_hold_process,
                "transfer_hold_did_agent_follow_no_hold_process_comment" => $request->transfer_hold_did_agent_follow_no_hold_process_comment,
                "transfer_hold_did_agent_status_customer_avoid_excessive_hold" => $request->transfer_hold_did_agent_status_customer_avoid_excessive_hold,
                "transfer_hold_did_agent_status_customer_avoid_excessive_hold_comment" => $request->transfer_hold_did_agent_status_customer_avoid_excessive_hold_comment,
                "transfer_hold_did_agent_ensure_silence_dead_air_opportunities" => $request->transfer_hold_did_agent_ensure_silence_dead_air_opportunities_hold,
                "transfer_hold_did_agent_ensure_silence_dead_air_opportunities_comment" => $request->transfer_hold_did_agent_ensure_silence_dead_air_opportunities_comment,
                "transfer_hold_did_agent_ensure_no_idle_time_during_contact" => $request->transfer_hold_did_agent_ensure_no_idle_time_during_contact_hold,
                "transfer_hold_did_agent_ensure_no_idle_time_during_contact_comment" => $request->transfer_hold_did_agent_ensure_no_idle_time_during_contact_comment,
                "transfer_hold_did_agent_ensure_no_idle_time_during_contact_no" => $request->transfer_hold_did_agent_ensure_no_idle_time_during_contact_no,
                "transfer_hold_did_agent_ensure_no_idle_time_during_contact_no_comment" => $request->transfer_hold_did_agent_ensure_no_idle_time_during_contact_no_comment,
              ];

              $soft_skills = [
                "soft_skills_did_agent_demonstrate_integrity_during_customer_contact" => $request->soft_skills_did_agent_demonstrate_integrity_during_customer_contact,
                "soft_skills_did_agent_demonstrate_integrity_during_customer_contact_comment" => $request->soft_skills_did_agent_demonstrate_integrity_during_customer_contact_comment,
                "soft_skills_did_agent_demonstrate_integrity_during_customer_contact_score" => $request->soft_skills_did_agent_demonstrate_integrity_during_customer_contact_score,
                "soft_skills_did_agent_demonstrate_no_integrity_during_customer_contact" => $request->soft_skills_did_agent_demonstrate_no_integrity_during_customer_contact,
                "soft_skills_did_agent_demonstrate_no_integrity_during_customer_contact_comment" => $request->soft_skills_did_agent_demonstrate_no_integrity_during_customer_contact_comment,
                "soft_skills_did_agent_handle_contact_with_professionalism" => $request->soft_skills_did_agent_handle_contact_with_professionalism,
                "soft_skills_did_agent_handle_contact_with_professionalism_comment" => $request->soft_skills_did_agent_handle_contact_with_professionalism_comment,
                "soft_skills_did_agent_handle_contact_with_professionalism_score" => $request->soft_skills_did_agent_handle_contact_with_professionalism_score,
                "soft_skills_did_agent_handle_contact_with_no_call_release_observed" => $request->soft_skills_did_agent_handle_contact_with_no_call_release_observed,
                "soft_skills_did_agent_handle_contact_with_no_call_release_observed_comment" => $request->soft_skills_did_agent_handle_contact_with_no_call_release_observed_comment,
                "soft_skills_did_agent_handle_contact_with_no_call_release_observed_score" => $request->soft_skills_did_agent_handle_contact_with_no_call_release_observed_score,
                "soft_skills_did_agent_transfer_call_if_caller_requested_supervisor" => $request->soft_skills_did_agent_transfer_call_if_caller_requested_supervisor,
                "soft_skills_did_agent_transfer_call_if_caller_requested_supervisor_comment" => $request->soft_skills_did_agent_transfer_call_if_caller_requested_supervisor_comment,
                "soft_skills_did_agent_transfer_call_if_caller_requested_supervisor_score" => $request->soft_skills_did_agent_transfer_call_if_caller_requested_supervisor_score,
                "soft_skills_did_agent_use_tools_appropriately_during_contact" => $request->soft_skills_did_agent_use_tools_appropriately_during_contact,
                "soft_skills_did_agent_use_tools_appropriately_during_contact_comment" => $request->soft_skills_did_agent_use_tools_appropriately_during_contact_comment,
                "soft_skills_did_agent_use_tools_appropriately_during_contact_score" => $request->soft_skills_did_agent_use_tools_appropriately_during_contact_score,
                "soft_skills_did_agent_take_ownership_show_empathy_make_every_attempt_to_assist" => $request->soft_skills_did_agent_take_ownership_show_empathy_make_every_attempt_to_assist,
                "soft_skills_did_agent_take_ownership_show_empathy_make_every_attempt_to_assist_comment" => $request->soft_skills_did_agent_take_ownership_show_empathy_make_every_attempt_to_assist_comment,
                "soft_skills_did_agent_take_no_ownership_show_empathy_make_every_attempt_to_assist" => $request->soft_skills_did_agent_take_no_ownership_show_empathy_make_every_attempt_to_assist,
                "soft_skills_did_agent_take_no_ownership_show_empathy_make_every_attempt_to_assist_comment" => $request->soft_skills_did_agent_take_no_ownership_show_empathy_make_every_attempt_to_assist_comment,
              ];

              $auto_fail = [
                "auto_fail_auto_failure_observed" => $request->auto_fail_auto_failure_observed,
                "auto_fail_auto_failure_observed_comment" => $request->auto_fail_auto_failure_observed_comment,
                "auto_fail_was_auto_failure_observed_if_yes" => $request->auto_fail_was_auto_failure_observed_if_yes,
                "auto_fail_was_auto_failure_observed_if_yes_comment" => $request->auto_fail_was_auto_failure_observed_if_yes_comment,
              ];

              $qa_tracking_only = [
                "qa_tracking_only_did_agent_offer_paperless_billing_to_customer" => $request->qa_tracking_only_did_agent_offer_paperless_billing_to_customer,
                "qa_tracking_only_did_agent_offer_paperless_billing_to_customer_comment" => $request->qa_tracking_only_did_agent_offer_paperless_billing_to_customer_comment,
                "qa_tracking_only_did_agent_offer_paperless_billing_to_customer_yes_declined" => $request->qa_tracking_only_did_agent_offer_paperless_billing_to_customer_yes_declined,
                "qa_tracking_only_did_agent_offer_paperless_billing_to_customer_yes_declined_comment" => $request->qa_tracking_only_did_agent_offer_paperless_billing_to_customer_yes_declined_comment,
                "qa_tracking_only_did_agent_fully_reolve_reason_for_call_and_eliminiate" => $request->qa_tracking_only_did_agent_fully_reolve_reason_for_call_and_eliminiate,
                "qa_tracking_only_did_agent_fully_reolve_reason_for_call_and_eliminiate_comment" => $request->qa_tracking_only_did_agent_fully_reolve_reason_for_call_and_eliminiate_comment,
              ];

              $psor_tracking_only = [
                "psor_tracking_only_is_psor_call" => $request->psor_tracking_only_is_psor_call,
                "psor_tracking_only_is_psor_call_comment" => $request->psor_tracking_only_is_psor_call_comment,
                "psor_tracking_only_cancel_intent_reason" => $request->psor_tracking_only_cancel_intent_reason,
                "psor_tracking_only_cancel_intent_reason_comment" => $request->psor_tracking_only_cancel_intent_reason_comment,
                "psor_tracking_only_presented_best_solution_that_targets_primary_cancel" => $request->psor_tracking_only_presented_best_solution_that_targets_primary_cancel,
                "psor_tracking_only_presented_best_solution_that_targets_primary_cancel_comment" => $request->psor_tracking_only_presented_best_solution_that_targets_primary_cancel_comment,
                "psor_tracking_only_did_agent_take_ownership_for_delay_in_service" => $request->psor_tracking_only_did_agent_take_ownership_for_delay_in_service,
                "psor_tracking_only_did_agent_take_ownership_for_delay_in_service_comment" => $request->psor_tracking_only_did_agent_take_ownership_for_delay_in_service_comment,
                "psor_tracking_only_customer_already_switched_to_competitor" => $request->psor_tracking_only_customer_already_switched_to_competitor,
                "psor_tracking_only_customer_already_switched_to_competitor_comment" => $request->psor_tracking_only_customer_already_switched_to_competitor_comment,
                "psor_tracking_only_did_agent_follow_directions_deliver_rebuttals" => $request->psor_tracking_only_did_agent_follow_directions_deliver_rebuttals,
                "psor_tracking_only_did_agent_follow_directions_deliver_rebuttals_comment" => $request->psor_tracking_only_did_agent_follow_directions_deliver_rebuttals_comment,
                "psor_tracking_only_did_agent_follow_directions_deliver_rebuttals_if_no" => $request->psor_tracking_only_did_agent_follow_directions_deliver_rebuttals_if_no,
                "psor_tracking_only_did_agent_follow_directions_deliver_rebuttals_if_no_comment" => $request->psor_tracking_only_did_agent_follow_directions_deliver_rebuttals_if_no_comment,
                "psor_tracking_only_did_agent_attempt_to_ask_customer_which_competitior" => $request->psor_tracking_only_did_agent_attempt_to_ask_customer_which_competitior,
                "psor_tracking_only_did_agent_attempt_to_ask_customer_which_competitior_comment" => $request->psor_tracking_only_did_agent_attempt_to_ask_customer_which_competitior_comment,
                "psor_tracking_only_customer_was_unhappy_with_products" => $request->psor_tracking_only_customer_was_unhappy_with_products,
                "psor_tracking_only_customer_was_unhappy_with_products_comment" => $request->psor_tracking_only_customer_was_unhappy_with_products_comment,
              ];

        $addCallEvolution = $CallEvolution->where("_id",$request->id)->update([
            "evalution_type" => $request->evaluation_type,
            "evaluation_time"=>$request->evaluation_time,
            "customer_state" => $request->customer_state,
            "crm" => $request->crm,
            "language" => $request->language,
            "btn_cbr" => $request->btn_cbr,
            "ani" => $request->ani,
            "conversation_recording" => $request->conversation_recording,
            "call_direction" => $request->call_direction,
            "call_type" => $request->call_type,
            "customer_type" => $request->customer_type,
            "account_type" => $request->account_type,
            "account_type_comment" => $request->account_type_comment,
            "account_tags" => $request->account_tags,
            "account_tags_comment" => $request->account_tags_comment,
            "repeat_inetraction_verbal" => $request->repeat_inetraction_verbal,
            "primary_reason_for_contact" => $request->primary_reason_for_contact,
            "primary_reason_for_contact_comment" => $request->primary_reason_for_contact_comment,
            "billing_enquiry_sub_reason" => $request->billing_enquiry_sub_reason,
            "billing_enquiry_sub_reason_comment" => $request->billing_enquiry_sub_reason_comment,
            "cosumer_products_access_line" => $request->cosumer_products_access_line,
            /////  Consumers Products
            "cosumer_products_access_line_comment" => $request->cosumer_products_access_line_comment,
            "cosumer_products_cyber_shield" => $request->cosumer_products_cyber_shield,
            "cosumer_products_cyber_shield_comment" => $request->cosumer_products_cyber_shield_comment,
            "cosumer_products_directtv" => $request->cosumer_products_directtv,
            "cosumer_products_directtv_comment" => $request->cosumer_products_directtv_comment,
            "cosumer_products_directtv_stream" => $request->cosumer_products_directtv_stream,
            "cosumer_products_directtv_stream_comment" => $request->cosumer_products_directtv_stream_comment,
            "cosumer_products_dish" => $request->cosumer_products_dish,
            "cosumer_products_dish_comment" => $request->cosumer_products_dish_comment,
            "cosumer_products_hsi" => $request->cosumer_products_hsi,
            "cosumer_products_hsi_comment" => $request->cosumer_products_hsi_comment,
            "cosumer_products_hsi_fiber" => $request->cosumer_products_hsi_fiber,
            "cosumer_products_hsi_fiber_comment" => $request->cosumer_products_hsi_fiber_comment,
            "cosumer_products_hsi_upgrade" => $request->cosumer_products_hsi_upgrade,
            "cosumer_products_hsi_upgrade_comment" => $request->cosumer_products_hsi_upgrade_comment,
            "cosumer_products_iwm" => $request->cosumer_products_iwm,
            "cosumer_products_iwm_comment" => $request->cosumer_products_iwm_comment,
            "cosumer_products_personal_tech_pro" => $request->cosumer_products_personal_tech_pro,
            "cosumer_products_personal_tech_pro_comment" => $request->cosumer_products_personal_tech_pro_comment,
            "cosumer_products_huge_net" => $request->cosumer_products_huge_net,
            "cosumer_products_huge_net_comment" => $request->cosumer_products_huge_net_comment,
            "cosumer_products_secure_wifi" => $request->cosumer_products_secure_wifi,
            "cosumer_products_secure_wifi_comment" => $request->cosumer_products_secure_wifi_comment,
            "cosumer_products_wifi_extender" => $request->cosumer_products_wifi_extender,
            "cosumer_products_wifi_extender_comment" => $request->cosumer_products_wifi_extender_comment,
            "cosumer_products_other" => $request->cosumer_products_other,
            "cosumer_products_other_comment" => $request->cosumer_products_other_comment,
            /////  Small Bussiness Products
            "small_business_products_access_line" => $request->small_business_products_access_line,
            "small_business_products_access_line_comment" => $request->small_business_products_access_line_comment,
            "small_business_products_connected_voice" => $request->small_business_products_connected_voice,
            "small_business_products_connected_voice_comment" => $request->small_business_products_connected_voice_comment,
            "small_business_products_cyber_shield" => $request->small_business_products_cyber_shield,
            "small_business_products_cyber_shield_comment" => $request->small_business_products_cyber_shield_comment,
            "small_business_products_fiber" => $request->small_business_products_fiber,
            "small_business_products_fiber_comment" => $request->small_business_products_fiber_comment,
            "small_business_products_fiber_referral" => $request->small_business_products_fiber_referral,
            "small_business_products_fiber_referral_comment" => $request->small_business_products_fiber_referral_comment,
            "small_business_products_hsi" => $request->small_business_products_hsi,
            "small_business_products_hsi_comment" => $request->small_business_products_hsi_comment,
            "small_business_products_2nd_hsi_connection" => $request->small_business_products_2nd_hsi_connection,
            "small_business_products_2nd_hsi_connection_comment" => $request->small_business_products_2nd_hsi_connection_comment,
            "small_business_products_hsi_upgrade" => $request->small_business_products_hsi_upgrade,
            "small_business_products_hsi_upgrade_comment" => $request->small_business_products_hsi_upgrade_comment,
            "small_business_products_premium_attachments" => $request->small_business_products_premium_attachments,
            "small_business_products_premium_attachments_comment" => $request->small_business_products_premium_attachments_comment,
            "small_business_products_vsa_pack" => $request->small_business_products_vsa_pack,
            "small_business_products_vsa_pack_comment" => $request->small_business_products_vsa_pack_comment,
            "small_business_products_vivial" => $request->small_business_products_vivial,
            "small_business_products_vivial_comment" => $request->small_business_products_vivial_comment,
            "small_business_products_voip" => $request->small_business_products_voip,
            "small_business_products_voip_comment" => $request->small_business_products_voip_comment,
            "small_business_products_other" => $request->small_business_products_other,
            "small_business_products_other_comment" => $request->small_business_products_other_comment,
            "high_speed_details_current_speed" => $request->high_speed_details_current_speed,
            "high_speed_details_current_speed_comment" => $request->high_speed_details_current_speed_comment,
            "high_speed_details_available_speed" => $request->high_speed_details_available_speed,
            "high_speed_details_available_speed_comment" => $request->high_speed_details_available_speed_comment,
            /////  Call/Chat Opening
            "call_chat_opening_did_the_agent_greet_customer" => $request->call_chat_opening_did_the_agent_greet_customer,
            "call_chat_opening_did_the_agent_greet_customer_comment" => $request->call_chat_opening_did_the_agent_greet_customer_comment,
            "call_chat_opening_did_the_agent_greet_customer_score" => $request->call_chat_opening_did_the_agent_greet_customer_score,
            "call_chat_opening_call_transfeered_to_agent" => $request->call_chat_opening_call_transfeered_to_agent,
            "call_chat_opening_call_transfeered_to_agent_comment" => $request->call_chat_opening_call_transfeered_to_agent_comment,
            "call_chat_opening_agent_make_good_first_impression" => $request->call_chat_opening_agent_make_good_first_impression,
            "call_chat_opening_agent_make_good_first_impression_comment" => $request->call_chat_opening_agent_make_good_first_impression_comment,
            "call_chat_opening_agent_make_good_no_first_impression" => $request->call_chat_opening_agent_make_good_no_first_impression,
            "call_chat_opening_agent_make_good_no_first_impression_comment" => $request->call_chat_opening_agent_make_good_no_first_impression_comment,
            "call_chat_opening_agent_verify_the_caller" => $request->call_chat_opening_agent_verify_the_caller,
            "call_chat_opening_agent_verify_the_caller_comment" => $request->call_chat_opening_agent_verify_the_caller_comment,
            "call_chat_opening_agent_verify_the_caller_score" => $request->call_chat_opening_agent_verify_the_caller_score,
            "call_chat_opening_agent_verify_no_caller" => $request->call_chat_opening_agent_verify_no_caller,
            "call_chat_opening_agent_verify_no_caller_comment" => $request->call_chat_opening_agent_verify_no_caller_comment,
            "call_chat_opening_agent_verify_email_capture" => $request->call_chat_opening_agent_verify_email_capture,
            "call_chat_opening_agent_verify_email_capture_comment" => $request->call_chat_opening_agent_verify_email_capture_comment,
            "call_chat_opening_agent_verify_email_capture_score" => $request->call_chat_opening_agent_verify_email_capture_score,
            "call_chat_opening_agent_verify_no_email_capture" => $request->call_chat_opening_agent_verify_no_email_capture,
            "call_chat_opening_agent_verify_no_email_capture_comment" => $request->call_chat_opening_agent_verify_no_email_capture_comment,
            "call_chat_opening_agent_verify_mobile_number" => $request->call_chat_opening_agent_verify_mobile_number,
            "call_chat_opening_agent_verify_mobile_number_comment" => $request->call_chat_opening_agent_verify_mobile_number_comment,
            "call_chat_opening_agent_verify_mobile_number_score" => $request->call_chat_opening_agent_verify_mobile_number_score,
            "call_chat_opening_agent_verify_no_mobile_number" => $request->call_chat_opening_agent_verify_no_mobile_number,
            "call_chat_opening_agent_verify_no_mobile_number_comment" => $request->call_chat_opening_agent_verify_no_mobile_number_comment,
            "call_chat_opening_agent_verify_cbr_verbally" => $request->call_chat_opening_agent_verify_cbr_verbally,
            "call_chat_opening_agent_verify_cbr_verbally_comment" => $request->call_chat_opening_agent_verify_cbr_verbally_comment,
            "call_chat_opening_agent_verify_cbr_verbally_score" => $request->call_chat_opening_agent_verify_cbr_verbally_score,
            "call_chat_opening_service_category_applicable" => $request->call_chat_opening_service_category_applicable,
            "call_chat_opening_service_category_applicable_comment" => $request->call_chat_opening_service_category_applicable_comment,
            "sales_consumer"=>$salesConsumer,
            "sales_sbg"=>$salesSBG,
            "save_consumer"=>$saveConsumer,
            "save_sbg"=>$savesSBG,
            "service"=>$services,
            "compliance"=>$compliance,
            "closing"=>$closing,
            "transfered_hold"=>$transfer_hold,
            "soft_skills"=>$soft_skills,
            "auto_fail"=>$auto_fail,
            "qa_tracking_only"=>$qa_tracking_only,
            "psor_tracking_only"=>$psor_tracking_only,
            "evaluator_comment"=>$request->evaluator_comment,
            "evaluator_date"=>date('Y-m-d h:i:s',time())
        ]);

        $evaluationData = $CallEvolution->where("_id",$request->id)->get();

        return response()->json(['status' => 200,'message'=>'Call Evaluation Added successfully.', 'data'=> $evaluationData], 200);
    }

}
