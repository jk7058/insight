<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Alert;
use App\Models\AlertNotification;
use App\Models\FeedbackSetting;
use Illuminate\Http\Request;
use App\Models\FormData;
use App\Models\FormDetails;
use App\Models\FormStructure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Save Feedback.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();
        $formName = $input['form_name'];
        $form_version = $input['form_version'];
        $action = $input['action'];
        // $unique_id = $input['unique_id'];
        // $category = FormStructure::where('form_name', $formName)->where('form_version', $form_version)->groupBy('cat_id')->get();
        // $sub_catg = FormStructure::where('form_name', $formName)->where('form_version', $form_version)->groupBy('subcat_id')->get();
        // $sub_catg = $this->common->getWhereSelectDistinct('forms', ['*'], ['form_name' => $formName, 'form_version' => $form_version, 'subcategory!=' => ''], 'subcat_id');
        // $attr = $this->common->getWhereSelectDistinct('forms', ['*'], ['form_name' => $formName, 'form_version' => $form_version], 'attr_id');
        // $sub_attr = $this->common->getWhereSelectDistinct('forms', ['*'], ['form_name' => $formName, 'form_version' => $form_version, 'subattr_id !=' => ""], 'subattr_id');
        // $tableName = strtolower($formName);
        // $unique_id = $this->input->post('unique_id');
        // $redirect = 'bpo/forms/selectnew_ui/' . strtolower($formName) . '/' . $form_version . '/' . $action . '/' . $unique_id;
        $message = 'Coaching comment submitted successfully';
        // $inser_arr = [];
        // $get_all_audit_by_id = $this->get_auditDetails_all_byid($formName, $form_version, $unique_id);
        // $submitmonth = date('m',strtotime($get_all_audit_by_id[0]->submit_time));
        // $callmonth   = date('m',strtotime($get_all_audit_by_id[0]->call_date));
        // $currentyear = date('y');
        //echo '<pre>'; print_r($_POST); die;
        if ($action == 'feedback') {
            // foreach ($category as $cat) {
            //     $attribute = $this->searcharray('cat_id', $cat->cat_id, $attr);
            //     if (!empty($attribute)) {
            //         foreach ($attribute as $att) {

            //             $feedback_c = "";
            //             if ($this->input->post($att->attr_uni_id . '_fdbck_att_status') <> '' && $this->input->post($att->attr_uni_id . '_fdbck_att_com') <> '') {
            //                 $feedback_c = $att->attr_uni_id . '|||' . $this->input->post($att->attr_uni_id . '_fdbck_att_status') . '|||' . $this->input->post($att->attr_uni_id . '_fdbck_att_com');
            //                 $inser_arr [] = [$feedback_c];
            //             }

            //             $subattr = $this->searcharray('attr_id', $att->attr_id, $sub_attr);
            //             if (!empty($subattr)) {
            //                 foreach ($subattr as $subatt) {
            //                     $feedback_c = "";
            //                     if ($this->input->post($subatt->attr_uni_id . '_fdbck_att_status') <> '' && $this->input->post($subatt->attr_uni_id . '_fdbck_att_com') <> '') {
            //                         $feedback_c = $subatt->attr_uni_id . '|||' . $this->input->post($subatt->attr_uni_id . '_fdbck_att_status') . '|||' . $this->input->post($subatt->attr_uni_id . '_fdbck_att_com');
            //                         $inser_arr [] = [$feedback_c];
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }
            $formData = FormData::where('form_name', $formName)->where('form_version', $form_version)->first();
            // $chk_audit_exist = $this->common->getDistinctWhereSelectCount($tableName, ['unique_id'], ['unique_id' => $unique_id]);
            if(isset($formData)) {

                //DATA FOR CLIENT SUP ROLE
                if ($action == 'feedback') {

                    //FETCH FORM AUDIT DETAILS & FORM DETAILS
                    // ['unique_id', 'form_version', 'evaluator_name', 'evaluator_id','evaluator_sup_name', 'evaluator_sup_id', 'agent_name', 'agent_id', 'supervisor', 'supervisor_id', 'wrap_time', 'pre_fatal_score', 'total_score', 'channels', 'custom1', 'custom2', 'custom3', 'custom4',  'call_id', 'call_date', 'issue_type', 'overall_com', 'submit_time', 'feedback_com', 'feedback_by', 'feedback_date', 'feedback_delay_reason', 'feedback_accept_comment', 'esc_phase_id', 'audit_status','audit_assignment_status','last_audit_datetime']
                    // $data['audit_details'] = $this->get_auditDetails($formName, $form_version, $unique_id);
                    // $data['form_detailsData'] = $this->get_formDetailsName($formName, $form_version);
                    // $data['audit_details'][0]->url = site_url() . '/' . $redirect;

                    // //GET USERS EMAIL ID
                    // $agent_details = $this->get_agentDetails($data['audit_details'][0]->agent_id);

                    $postData = array(
                        "feedback_com" => $input['feedback_com'],
                        "feedback_delay_reason" => isset($input['feedback_delay_reason'])?$input['feedback_delay_reason']:'',
                        "feedback_date" => date("Y-m-d H:i:s"),
                        "agent_exit_status" => '1',
                        // "unique_id" => $unique_id
                    );

                    // if (!empty($inser_arr)) {
                    //     $postData['feedback_com_attr'] = json_encode($inser_arr);
                    // }
                    if ($user->userRole == 'SuperAdmin') {
                        //FEEDBACK SUBMITTED BY SUPER ADMIN
                        $postData['feedback_by'] = $user->name . '||' . $user->userId;
                        $postData['feedback_admin'] = $user->name . '||' . $user->userId;
                    } else {
                        if ($user->userRole == 'Supervisor') {
                            //FEEDBACK SUBMITTED BY PROXY SUPERVISOR
                            $postData['feedback_by'] = $user->name . '||' . $user->userId;
                            $postData['feedback_proxy'] = $user->name . '||' . $user->userId;
                        } else {
                            $postData['feedback_by'] = $user->name . '||' . $user->userId;
                        }
                    }

                    //CHECK IF AGENT IS EXIST OR INACTIVE
                    // if($agent_details[0]->status == 0) {

                    // $eval_submit_time = strtotime($formData->evaluation_time);
                    // $cur_time = strtotime(date('Y-m-d H:i:s'));
                    $diffHours = Common::getHoursWithoutWeekend($formData->evaluation_time);

                    /*
                      AGENT EXIT STATUS
                      1 => ACTIVE
                      2 => IN ACTIVE AGENT WITH IN 48 HOURS OF EVALUATION
                      3 => IN ACTIVE AGENT AFTER 48 HOURS OF EVALUATION
                     */
                    $feedbackHours = FeedbackSetting::first();
                    
                    if ($diffHours <= $feedbackHours->feedback_tat) {
                        $postData['feedback_accept_comment'] = 'N/A';
                        $postData['agent_exit_status'] = '2';
                        $postData['feedback_accept_time'] = date('Y-m-d H:i:s');
                    } else {
                        $postData['feedback_accept_comment'] = 'N/A';
                        $postData['agent_exit_status'] = '3';
                        $postData['feedback_accept_time'] = date('Y-m-d H:i:s');
                    }
                    // }
                }
                
                //ACKNOWLEDGE FOR CLIENT AGENT
                if ($action == 'feedback' && ($user->userGroup == 'client' && $user->userType == 'Agent')) {
                    $postData = array(
                        "feedback_accept_comment" => isset($input['feedback_accept_comment'])?$input['feedback_accept_comment']:'',
                        "feedback_accept_time" => date('Y-m-d H:i:s'),
                        // "unique_id" => $unique_id
                    );
                    $message = 'Your comment submitted successfully';
                }

                if($input['feedback_com_update']==1){
                    $postDataComment = array(
                        "feedback_com" => $input['feedback_com'],
                        // "unique_id" => $unique_id
                    );
                    
                    $message = 'Your comment updated successfully';
                    // dd($postDataComment);
                    foreach($postDataComment as $kcomm => $comm){
                        $formData->$kcomm = $comm;
                    }
                    $fdbck_update = $formData->save();
                    // $fdbck_update = $formData->update($postDataComment);
                    /* ********************** START DFR CHANGES ********************* */
                    // if($formName == 'clink_updated_form'){
                    //     $this->update_dfr_data($callmonth,$submitmonth,$currentyear,$postDataComment);
                    // }
                    /* ********************** END DFR CHANGES ********************* */
                    $updateData = !empty($fdbck_update)?1:$fdbck_update;

                }else{
                    foreach($postData as $kcomm => $comm){
                        $formData->$kcomm = $comm;
                    }
                    $updateData = $formData->save();
                    // $updateData = $formData->update($postData);
                    /* ********************** START DFR CHANGES ********************* */
                    // if($formName == 'clink_updated_form'){
                    //     $this->update_dfr_data($callmonth,$submitmonth,$currentyear,$postData);
                    // }
                    /* ********************** END DFR CHANGES ********************* */
                }

                if ($updateData) {
                    $formDisplayName = FormDetails::where('form_name', $formName)->where('form_version', $form_version)->first();
                    //SEND EMAIL TO PARTICULAR AGENT ASSOCIATED WITH UNIQUE ID
                    if ($user->userType == 'Supervisor' || $user->userType == 'Manager') {
                        $data['feedback_comment'] = $input['feedback_com'];
                        if (isset($formData->agent) && !empty($formData->agent)){
                            if (isset($formData->agent['email']) && !empty($formData->agent['email'])){
                                $feedback_emails = array($formData->agent['email']);
                                $feedback_sub = "Coaching Alert";
                                $data['formNameWithVersion'] = Common::form_formatedName($formDisplayName->display_name, $form_version);
                                $data['auditUrl'] = url('/');
                                $data['evaluator_name'] = $formData->evaluator['name'];
                                $data['evaluator_id'] = $formData->evaluator['id'];
                                $data['lob'] = $formData->hierarchy['custom1'];
                                $data['call_id'] = $formData->call['call_id'];
                                // $feedback_body = $this->load->view('email_template/feedback', $data, true);
                                $mail = Common::send_email($feedback_emails, $feedback_sub, $data, 'email_template.feedback');
                            }
                        }
                    }
                    // ***********************ALERT EMAIL***********************************
                    
                    $postData['sup_id'] = $input['supervisor_id'];
                    $postData['agent_id'] = $input['agent_id'];
                    $postData['heading'] = 'Coaching Submitted';
                    // $postData['unique_id'] = $unique_id;
                    $postData['call_id'] = $input['call_id'];
                    $postData['custom1'] = $input['custom1'];
                    $postData['custom2'] = $input['custom2'];
                    $postData['custom3'] = $input['custom3'];
                    $postData['custom4'] = $input['custom4'];
                    $postData['overall_com'] = $input['feedback_com'];
                    $postData['total_score'] = $input['final_score'];
                    $postData['agent_name'] = $input['agent_name'];
                    $postData['supervisor'] = $input['supervisor'];
                    $postData['pre_fatal_score'] = $input['fatal_score'];
                    $postData['feedback_delay_reason'] = $input['feedback_delay_reason'];
                    $postData['formNameWithVersion'] = $formDisplayName->display_name . ' (V' . $form_version . '.0)';
                    $postData['submitted_by'] = Common::user_formatedName($user->name, $user->userId);
                    if (!empty($input['agent_id'])) {
                        $agentEmail = Agent::where('agent_id', $input['agent_id'])->first();
                        $postData['agentEmail'] = (!empty($agentEmail->agent_email) ? $agentEmail->agent_email : '');
                    }
                    if (!empty($input['supervisor_id'])) {
                        $agentSupEmail = User::where('userId', $input['supervisor_id'])->first();
                        $postData['agentSupEmail'] = (!empty($agentSupEmail->userEmail) ? $agentSupEmail->userEmail : '');
                    }

                    $affi = json_decode($input['affiliation'],1);
                    $affi[] = 'all';
                    $fName = $formName.'_'.$form_version;
                    $query = Alert::where('form_name', $fName)->where('alert_status', 'Enable')->where('measure_type', 'coachedEval');
                    // $wherein['evaluator_affiliation'] = $affi;
                    // $where ='';
                    // $wherein['evaluator_affiliation'] = $affi;
                    $custom1 = $input['custom1'];
                    $custom2 = $input['custom2'];
                    $custom3 = $input['custom3'];
                    $custom4 = $input['custom4'];
                    if(isset($affi) && !empty($affi)){
                        $query->orWhere(function($query) use($affi){
                            foreach($affi as $c1){
                                $query->where('evaluator_affiliation', $c1);
                            }
                        });
                    }
                    if(isset($custom1) && !empty($custom1)){
                        $custom1 = json_decode($custom1, 1);
                        if(count($custom1) > 0){
                            $query->orWhere(function($query) use($custom1){
                                foreach($custom1 as $c1){
                                    $query->where('custom1', $c1);
                                }
                            });
                        }
                    }
                    if(isset($custom2) && !empty($custom2)){
                        $custom2 = json_decode($custom2, 1);
                        if(count($custom2) > 0){
                            $query->orWhere(function($query) use($custom2){
                                foreach($custom2 as $c1){
                                    $query->where('custom2', $c1);
                                }
                            });
                        }
                    }
                    if(isset($custom3) && !empty($custom3)){
                        $custom3 = json_decode($custom3, 1);
                        if(count($custom3) > 0){
                            $query->orWhere(function($query) use($custom3){
                                foreach($custom3 as $c1){
                                    $query->where('custom3', $c1);
                                }
                            });
                        }
                    }
                    if(isset($custom4) && !empty($custom4)){
                        $custom4 = json_decode($custom4, 1);
                        if(count($custom4) > 0){
                            $query->orWhere(function($query) use($custom4){
                                foreach($custom4 as $c1){
                                    $query->where('custom4', $c1);
                                }
                            });
                        }
                    }
                    $alertData = $query->get();
                   //$alertData = $this->common->getWhereSelectAll('alert',[],['form_name'=> $formName.'_'.$form_version,'alert_status'=> 'Enable','measure_type'=>'coachedEval']);
                    if(!$alertData->isEmpty()){
                        $alertResponse =  $this->sendAlert($postData,$alertData);
                    }
                    // ***************************************************************************

                    return response()->json([
                        'status'   => '200',
                        'message'  => $message,
                        'data'     => (object)[]
                    ], 200);
                } else {
                    return response()->json([
                        'status'   => '500',
                        'message'  => "Error occured.",
                        'data'     => (object)[]
                    ], 500);
                }
            } else {
                return response()->json([
                    'status'   => '400',
                    'message'  => "Evaluation does not exist.",
                    'data'     => (object)[]
                ], 400);
            }
        } else {
            return response()->json([
                'status'   => '404',
                'message'  => "Not found.",
                'data'     => (object)[]
            ], 404);
        }
    }

    /**
     * Send Alert email.
     *
     * @param  array  $fromData
     * @param  object  $alertData
     * @return void
     */
    public function sendAlert($fromData,$alertData){
        //echo '<pre>'; print_r($alertData); die;
        $Emaildata                  = [];
        $subject                    = '';
        $unique_id                  = (!empty($fromData['unique_id'])? $fromData['unique_id'] :'');
        $Emaildata['heading']       = (!empty($fromData['heading'])? $fromData['heading'] :'');
        $Emaildata['call_id']       = (!empty($fromData['call_id'])? $fromData['call_id'] :'');
        $Emaildata['sup_id']        = (!empty($fromData['sup_id'])? $fromData['sup_id'] :'');
        $Emaildata['custom1']       = (!empty($fromData['custom1'])? $fromData['custom1'] :'');
        $Emaildata['custom2']       = (!empty($fromData['custom2'])? $fromData['custom2'] :'');
        $Emaildata['custom3']       = (!empty($fromData['custom3'])? $fromData['custom3'] :'');
        $Emaildata['custom4']       = (!empty($fromData['custom4'])? $fromData['custom4'] :'');
        $Emaildata['overall_com']   = (!empty($fromData['overall_com'])? $fromData['overall_com'] :'');
        $Emaildata['pre_fatal_score']   = (!empty($fromData['pre_fatal_score'])? $fromData['pre_fatal_score'] :'');
        $Emaildata['total_score']   = (!empty($fromData['total_score'])? $fromData['total_score'] :'0');
        $Emaildata['form_name']     = (!empty($fromData['formNameWithVersion'])? $fromData['formNameWithVersion'] :'');
        $Emaildata['feedback_delay_reason']   = (!empty($fromData['feedback_delay_reason'])? $fromData['feedback_delay_reason'] :'0');
        $Emaildata['submitted_by']  = (!empty($fromData['submitted_by'])? $fromData['submitted_by'] :'');
        $Emaildata['agent_id']      = (!empty($fromData['agent_id'])? $fromData['agent_id'] :'');
        $Emaildata['supervisor']    = (!empty($fromData['supervisor'])? $fromData['supervisor']. ' ('.$Emaildata['sup_id'].')' :'');
        $Emaildata['agent_name']    = (!empty($fromData['agent_name'])? $fromData['agent_name']. ' ('.$Emaildata['agent_id'].')' :'');
        $Emaildata['agentEmail']        = (!empty($fromData['agentEmail'])? $fromData['agentEmail'] :'');
        $Emaildata['agentSupEmail']     = (!empty($fromData['agentSupEmail'])? $fromData['agentSupEmail'] :'');
        $Emaildata['call_date']         = (!empty($fromData['call_date'])? date('m-d-Y H:i:s',strtotime($fromData['call_date'])) :''); 
        $Emaildata['evaluation_date']   = (!empty($fromData['submit_time'])? date('m-d-Y H:i:s',strtotime($fromData['submit_time'])) :'');
        $Emaildata['ani']               = '';
        $Emaildata['call_time']         = '';
        $mail_dynamic_data_measure  = '';
        $mail_dynamic_data_attribute = '';
        $action = 'read';
        if (isset($alertData) && !empty($alertData)) {
            foreach ($alertData as $eachAlertData) {
                $mail_dynamic_data_measure  = '';
                $mail_dynamic_data_attribute = '';
                $subject                     = '';
                $notiData = [];
                $nameWithVer = $eachAlertData->form_name;
                $nameWithVer = explode("_", $nameWithVer);
                $form_version = array_pop($nameWithVer);
                $formName = implode('_', $nameWithVer);
                $notiData['redirect_form_name'] = $formName;
                $notiData['version']            = $form_version;
                if($eachAlertData->measure_type == 'careEval'){
                    $action = 'escalate';
                }else if($eachAlertData->measure_type == 'coachedEval'){
                    $action = 'feedback';
                }
                $Emaildata['url'] = url('/bpo/forms/selectnew_ui/'.$formName.'/'.$form_version.'/'.$action.'/'.$unique_id);
                $receiversEmailList = '';
                
                $recievers = explode(',',$eachAlertData->alert_reciever_list);
                if($eachAlertData->created_by_type == '4'){
                    if(!empty($eachAlertData->notify_all) && (empty($eachAlertData->alert_reciever_list))){  
                        if(($eachAlertData->notify_all) == 'all'){
                            if(!empty($Emaildata['agentEmail']) && !empty($Emaildata['agentSupEmail'])){
                                $receiversEmailList = $Emaildata['agentEmail'].','.$Emaildata['agentSupEmail'];
                            }else if(!empty($Emaildata['agentEmail'])){
                                $receiversEmailList = $Emaildata['agentEmail'];
                            }else if(!empty($Emaildata['agentSupEmail'])){
                                $receiversEmailList = $Emaildata['agentSupEmail'];
                            }

                            if((!empty($receiversEmailList)) && (!empty($eachAlertData->alert_send_to))){
                                $alert_send_to = explode(',',$eachAlertData->alert_send_to);
                                $receiversEmails = User::where('userId', $alert_send_to)->get();
                                foreach($receiversEmails as $key => $eachmail){
                                    $receiversEmail[$key] = $eachmail->userEmail;
                                }
                                if((!empty($receiversEmail))){
                                    $receiversEmail = implode(',',$receiversEmail);
                                    $receiversEmailList = $receiversEmailList.','.$receiversEmail;
                                }
                            }else if(!empty($eachAlertData->alert_send_to)){
                                $alert_send_to = explode(',',$eachAlertData->alert_send_to);
                                $receiversEmails = User::where('userId', $alert_send_to)->get();
                                // $receiversEmails = $this->common->getWhereInSelectAll('user',['user_email'],'empid',$alert_send_to);
                                foreach($receiversEmails as $key => $eachmail){
                                    $receiversEmail[$key] = $eachmail->userEmail;
                                }
                                if((!empty($receiversEmail))){
                                    $receiversEmailList = implode(',',$receiversEmail);
                                }
                            }

                            if((!empty($receiversEmailList)) && (!empty($eachAlertData->other_alert_reciever_list))){
                                $receiversEmailList = $receiversEmailList.','.$eachAlertData->other_alert_reciever_list;
                            }else if(!empty($eachAlertData->other_alert_reciever_list)){
                                $receiversEmailList = $eachAlertData->other_alert_reciever_list;
                            }
                            if((!empty($receiversEmailList)) && (!empty($eachAlertData->include_me))){
                                $receiversEmailList = $receiversEmailList.','.$eachAlertData->include_me;
                            }else if(!empty($eachAlertData->include_me)){
                                $receiversEmailList = $eachAlertData->include_me;
                            }
                        }
                    }else if(empty($eachAlertData->notify_all)  && (empty($eachAlertData->alert_reciever_list))){ 
                        if(!empty($eachAlertData->alert_send_to)){
                            $alert_send_to = explode(',',$eachAlertData->alert_send_to);
                            $receiversEmails = User::where('userId', $alert_send_to)->get();
                            // $receiversEmails = $this->common->getWhereInSelectAll('user',['user_email'],'empid',$alert_send_to);
                            foreach($receiversEmails as $key => $eachmail){
                                $receiversEmail[$key] = $eachmail->userEmail;
                            }
                            if((!empty($receiversEmail))){
                                $receiversEmailList = implode(',',$receiversEmail);
                            }
                        }

                        if((!empty($receiversEmailList)) && (!empty($eachAlertData->other_alert_reciever_list))){
                            $receiversEmailList = $receiversEmailList.','.$eachAlertData->other_alert_reciever_list;
                        }else if(!empty($eachAlertData->other_alert_reciever_list)){
                            $receiversEmailList = $eachAlertData->other_alert_reciever_list;
                        }
                        if((!empty($receiversEmailList)) && (!empty($eachAlertData->include_me))){
                            $receiversEmailList = $receiversEmailList.','.$eachAlertData->include_me;
                        }else if(!empty($eachAlertData->include_me)){
                            $receiversEmailList = $eachAlertData->include_me;
                        }
                    }else if(!empty($eachAlertData->alert_reciever_list)){
                        $recievers = explode(',',$eachAlertData->alert_reciever_list);
                        if(in_array($fromData['sup_id'], $recievers) || in_array($fromData['agent_id'], $recievers)){
                            $receiversEmailList = '';
                            if(!empty($eachAlertData->alert_send_to)){
                                $alert_send_to = explode(',',$eachAlertData->alert_send_to);
                                $receiversEmails = User::where('userId', $alert_send_to)->get();
                                // $receiversEmails = $this->common->getWhereInSelectAll('user',['user_email'],'empid',$alert_send_to);
                                foreach($receiversEmails as $key => $eachmail){
                                    $receiversEmail[$key] = $eachmail->userEmail;
                                }
                                if((!empty($receiversEmail))){
                                    $receiversEmail = implode(',',$receiversEmail);
                                    $receiversEmailList = $receiversEmail;
                                }
                            }
                            if((!empty($receiversEmailList)) && (!empty($eachAlertData->other_alert_reciever_list))){
                                $receiversEmailList = $receiversEmailList.','.$eachAlertData->other_alert_reciever_list;
                            }else if(!empty($eachAlertData->other_alert_reciever_list)){
                                $receiversEmailList = $eachAlertData->other_alert_reciever_list;
                            }
                            if((!empty($receiversEmailList)) && (!empty($eachAlertData->include_me))){
                                $receiversEmailList = $receiversEmailList.','.$eachAlertData->include_me;
                            }else if(!empty($eachAlertData->include_me)){
                                $receiversEmailList = $eachAlertData->include_me;
                            }
                            if(!empty($eachAlertData->notify_all) && (!empty($receiversEmailList))){
                                $notify = explode(',',$eachAlertData->notify_all);
                                if(in_array($fromData['sup_id'], $notify)){
                                    if(!empty($Emaildata['agentSupEmail'])){
                                        $receiversEmailList = $receiversEmailList.','.$Emaildata['agentSupEmail'];
                                    }
                                }
                                if(in_array($fromData['agent_id'], $notify)){
                                    if(!empty($Emaildata['agentEmail'])){
                                        $receiversEmailList = $receiversEmailList.','.$Emaildata['agentEmail'];
                                    }
                                }
                            }else  if(!empty($eachAlertData->notify_all)){
                                $notify = explode(',',$eachAlertData->notify_all);
                                if(in_array($fromData['sup_id'], $notify)){
                                    if(!empty($Emaildata['agentSupEmail'])){
                                        $receiversEmailList = $Emaildata['agentSupEmail'];
                                    }
                                }
                            }
                            //echo '<pre>'; print_r($receiversEmailList);die;
                        }
                    }

                }

                if($eachAlertData->created_by_type == '3'){
                    if(!empty($eachAlertData->alert_reciever_list)){
                        $recievers = explode(',',$eachAlertData->alert_reciever_list);
                        if(in_array($fromData['agent_id'], $recievers)){
                            $receiversEmailList = '';
                            if(!empty($Emaildata['agentEmail']) && !empty($Emaildata['agentSupEmail'])){
                                $receiversEmailList = $Emaildata['agentEmail'].','.$Emaildata['agentSupEmail'];
                            }else if(!empty($Emaildata['agentEmail'])){
                                $receiversEmailList = $Emaildata['agentEmail'];
                            }else if(!empty($Emaildata['agentSupEmail'])){
                                $receiversEmailList = $Emaildata['agentSupEmail'];
                            }
                            if((!empty($receiversEmailList)) && (!empty($eachAlertData->other_alert_reciever_list))){
                                $receiversEmailList = $receiversEmailList.','.$eachAlertData->other_alert_reciever_list;
                            }
                        }
                    }else if(($eachAlertData->empid) == ($fromData['sup_id'])){
                        
                        if(!empty($Emaildata['agentSupEmail'])){
                            $receiversEmailList = $Emaildata['agentSupEmail'];
                        }
                        if((!empty($receiversEmailList)) && (!empty($eachAlertData->other_alert_reciever_list))){
                            $receiversEmailList = $receiversEmailList.','.$eachAlertData->other_alert_reciever_list;
                        }else{
                            $receiversEmailList = $eachAlertData->other_alert_reciever_list;
                        }
                    }
                }
                //echo '<pre>'; print_r($receiversEmailList); die;
                if(!empty($receiversEmailList)){
                    if(isset($eachAlertData->alert_by) && $eachAlertData->alert_by == 'measure'){
                        if(isset($eachAlertData->measure_type) && $eachAlertData->measure_type == 'qaScore'){
                            if($eachAlertData->measureOprtor == 'aboveGoal' && $fromData['total_score']  > $eachAlertData->measure_value ){
                                $mail_dynamic_data_measure .= 'Evaluation total score is above than '.$eachAlertData->measure_value.'.</br>' ;
                                $subject = $eachAlertData->alert_name;
                            }
                            else if($eachAlertData->measureOprtor == 'grtrEqual' && $fromData['total_score']  >= $eachAlertData->measure_value ){
                                $mail_dynamic_data_measure .= 'Evaluation total score is above than or equal '.$eachAlertData->measure_value.'.</br>' ;
                                $subject = $eachAlertData->alert_name;
                            }
                            else if(($eachAlertData->measureOprtor == 'belowGoal' || $eachAlertData->measureOprtor == 'lessThan') && $fromData['total_score']  < $eachAlertData->measure_value ){
                                $mail_dynamic_data_measure .= 'Evaluation total score is below than '.$eachAlertData->measure_value.'.</br>' ;
                                $subject = $eachAlertData->alert_name;
                            }
                            else if($eachAlertData->measureOprtor == 'lessThanEqual' && $fromData['total_score']  <= $eachAlertData->measure_value ){
                                $mail_dynamic_data_measure .= 'Evaluation total score is less than or equal '.$eachAlertData->measure_value.'.</br>' ;
                                $subject = $eachAlertData->alert_name;
                            }
                            else if($eachAlertData->measureOprtor == 'notEqual' && $fromData['total_score']  != $eachAlertData->measure_value ){
                                $mail_dynamic_data_measure .= 'Evaluation total score is not equal to '.$eachAlertData->measure_value.'.</br>' ;
                                $subject = $eachAlertData->alert_name;
                            }
                            else if($eachAlertData->measureOprtor == 'between'){
                                $measure_value = explode('-',$eachAlertData->measure_value);
                                if($fromData['total_score']  >=  $measure_value[0] && $fromData['total_score'] <= $measure_value[1]){
                                $mail_dynamic_data_measure .= 'Evaluation total score is in between '.$measure_value[0] .' to '.$measure_value[1].'.</br>' ;
                                $subject = $eachAlertData->alert_name;
                                }
                            }
                        } else if (isset($eachAlertData->measure_type) && ($eachAlertData->measure_type == 'autofailed')) {
                            if ($eachAlertData->measure_equals_y_n == 'Yes') {
                                if ($fromData['total_score'] == '0' && $fromData['pre_fatal_score'] != '0') {
                                    $Emaildata['heading'] = 'Evaluation Autofailed';
                                    $subject = $eachAlertData->alert_name;
                                }
                            } else if ($eachAlertData->measure_equals_y_n == 'No') {
                                $Emaildata['heading'] = 'Evaluation Submitted';
                                $subject = $eachAlertData->alert_name;
                            }
                        }
                        else if(isset($eachAlertData->measure_type) && $eachAlertData->measure_type == 'coachedEval'){
                            $Emaildata['heading'] = 'Coaching Submitted';
                            $subject = $eachAlertData->alert_name;
                        }
                        else if(isset($eachAlertData->measure_type) && $eachAlertData->measure_type == 'auditEval'){

                            $subject = $eachAlertData->alert_name;

                            $Emaildata['audit_reason'] = $fromData['audit_reason'];
                            $Emaildata['audit_reason'] = $fromData['audit_reason'];
                        }
                        else if(isset($eachAlertData->measure_type) && $eachAlertData->measure_type == 'careEval'){
                            $subject = $eachAlertData->alert_name;
                        }
                        else if(isset($eachAlertData->measure_type) && $eachAlertData->measure_type == 'evalComplete'){
                            $Emaildata['heading'] = 'Evaluation Submitted';
                            //$subject = 'Evaluation Submitted for Call Id:'.$fromData['call_id'];
                            $subject = $eachAlertData->alert_name;
                        }
                        
                        // prepare data for mail template when alert by is measure
                        if ((!empty($subject)) && $eachAlertData->alert_type != 'notification') {
                            $Emaildata['message_temp'] = $eachAlertData->message_temp;
                            $Emaildata['mail_dynamic_data_measure'] = $mail_dynamic_data_measure;
                            $Emaildata['mail_dynamic_data_attribute'] = '';
                            // $alertBody = $this->load->view('email_template/alert_mailer_new',$Emaildata,true);
                            
                            if(!empty($receiversEmailList) && isset($eachAlertData->alert_type) && $eachAlertData->alert_type != 'notification'){
                                //echo print_r($alertBody); die;
                                $receiversEmailList = implode(',', array_unique(explode(',', $receiversEmailList)));
                                $mail = Common::send_email($receiversEmailList, $subject, $Emaildata, 'email_template.alert_mailer_new');
                            }
                        }
                        $user = Auth::user();
                        //If Alert Is By Notification
                        if ((!empty($subject)) && $eachAlertData->alert_type == 'notification') {
                            $notiReceivers = $fromData['supervisor_id'];
                            if (!empty($fromData['supervisor_id']) && ($eachAlertData->created_by_type == '4')) {
                                if ((!empty($notiReceivers)) && (!empty($eachAlertData->include_me))) {
                                    $notiReceivers = $notiReceivers . ',' . $eachAlertData->empid;
                                }
                            }
                            $notiData['unique_id'] = $unique_id;
                            $notiData['alt_name'] = $subject;
                            $notiData['alt_data'] = json_encode($Emaildata);
                            $notiData['submitted_by'] = $user->name . '||' . $user->userId;
                            $notiData['alt_receiver'] = $notiReceivers;
                            $notiData['sup_id'] = $fromData['supervisor_id'];
                            //echo '<pre>'; print_r($notiData); die;
                            $insertData = AlertNotification::create($notiData);
                        }
                    } else if (isset($eachAlertData->alert_by) && $eachAlertData->alert_by == 'attribute') {
                        if (!empty($eachAlertData->form_attributes)) {
                            $allConditionStatusArray = [];
                            $allConditionDataArray = [];
                            $alet_attributes = json_decode($eachAlertData->form_attributes);
                            foreach ($alet_attributes as $keyIndex => $eachAlertSet) {
                                $form_cat_attributes_set = json_decode($fromData[$eachAlertSet->sltCategory . '_attr']);
                                foreach ($form_cat_attributes_set as $eachAttributeSet) {
                                    $eachAttributeSet = (!empty($eachAttributeSet) ? explode('|||', $eachAttributeSet) : []);
                                    if (trim($eachAlertSet->sltAttribute) == trim($eachAttributeSet[0])) {
                                        $formAttrOption = (explode('_', $eachAttributeSet[2])[0]);
                                        if(!empty($formAttrOption)){
                                            $formAttrOption = explode(',', $formAttrOption);
                                            $result = array_intersect($formAttrOption,$eachAlertSet->sltRating);
                                            if(!empty($result)){
                                                $result = implode(',',$result);
                                                $allConditionStatusArray[$keyIndex] = 'true';
                                                    $allConditionDataArray[$keyIndex]['cat'] = $fromData[$eachAlertSet->sltCategory];
                                                    $allConditionDataArray[$keyIndex]['attribute'] = $eachAttributeSet[1];
                                                    $allConditionDataArray[$keyIndex]['option'] = $result;
                                            } else {
                                                $allConditionStatusArray[$keyIndex] = 'false';
                                            }
                                        }
                                    }
                                }
                            }

                            $tmp = array_count_values($allConditionStatusArray);
                            if(isset($tmp['true'])){
                                $cntTrue =$tmp['true'];
                            }else{
                                $cntTrue = '0';
                            }

                            // create mailer rows
                            foreach ($allConditionDataArray as $eachDataForRow) {
                                $mail_dynamic_data_attribute .= '<tr>
                                <td style="padding:8px 12px;border:1px solid #dcdcdc;text-align:center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size:11px;font-weight: 400;line-height: 1.5;">' . $eachDataForRow['cat'] . '</td>
                                <td style="padding:8px 12px;border:1px solid #dcdcdc;text-align:center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size:11px;font-weight: 400;line-height: 1.5;">' . $eachDataForRow['attribute'] . '</td>
                                <td style="padding:8px 12px;border:1px solid #dcdcdc;text-align:center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size:11px;font-weight: 400;line-height: 1.5;">' . $eachDataForRow['option'] . '</td>
                                </tr>';
                            }

                            // prepare data for mail template when alert by is attribute
                            if($mail_dynamic_data_attribute != ''){
                                $subject = $eachAlertData->alert_name;
                                $Emaildata['message_temp'] = $eachAlertData->message_temp;
                                $Emaildata['mail_dynamic_data_measure'] = '';
                                $Emaildata['mail_dynamic_data_attribute'] = $mail_dynamic_data_attribute;
                                // $alertBody = $this->load->view('email_template/alert_mailer_new', $Emaildata, true);
                                
                                //echo print_r($alertBody);die;
                                // mail send when OR condition is happen
                                if ((!empty($subject)) && $eachAlertData->switchAndOr == 'or' && (int) $cntTrue > 0) {
                                    if (isset($eachAlertData->alert_type) && $eachAlertData->alert_type == 'notification') {
                                        $Emaildata['mail_dynamic_data_attribute'] = json_encode($allConditionDataArray);
                                    }else{
                                        $receiversEmailList = implode(',', array_unique(explode(',', $receiversEmailList)));
                                        //$receiversEmailList = implode(',',$receiversEmailList);
                                        $mail = Common::send_email($receiversEmailList, $subject, $Emaildata, 'email_template.alert_mailer_new');
                                    }
                                }
    
                                // mail send when AND condition is happen
                                if ((!empty($subject)) && $eachAlertData->switchAndOr == 'and' && (int) $cntTrue == count($allConditionStatusArray)) {
                                    if (isset($eachAlertData->alert_type) && $eachAlertData->alert_type == 'notification') {
                                        $Emaildata['mail_dynamic_data_attribute'] = json_encode($allConditionDataArray);
                                    } else {
                                        //$receiversEmailList = implode(',',$receiversEmailList);
                                        $receiversEmailList = implode(',', array_unique(explode(',', $receiversEmailList)));
                                        $mail = Common::send_email($receiversEmailList, $subject, $Emaildata, 'email_template.alert_mailer_new');
                                    }
                                }
                                //If Alert Is By Notification
                                if ((!empty($subject)) && !empty($receiversEmailList) && $eachAlertData->alert_type == 'notification') {
                                    $notiReceivers = $fromData['supervisor_id'];
                                    if (!empty($fromData['supervisor_id']) && ($eachAlertData->created_by_type == '4')) {
                                        if ((!empty($notiReceivers)) && (!empty($eachAlertData->include_me))) {
                                            $notiReceivers = $notiReceivers . ',' . $eachAlertData->empid;
                                        }
                                    }
                                    $notiData['unique_id'] = $unique_id;
                                    $notiData['alt_name'] = $subject;
                                    $notiData['alt_data'] = json_encode($Emaildata);
                                    $notiData['submitted_by'] = ($this->session->userdata('name')) . '||' . ($this->session->userdata('empid'));
                                    $notiData['alt_receiver'] = $notiReceivers;
                                    $notiData['sup_id'] = $fromData['supervisor_id'];
                                    //echo '<pre>'; print_r($notiData); die;
                                    $insertData = AlertNotification::create($notiData);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $formName     = (!empty($fromData['formName'])? $fromData['formName'] :'');
            $form_version = (!empty($fromData['form_version'])? $fromData['form_version'] :'');
            $Emaildata['url'] = url('bpo/forms/selectnew_ui/'.$formName.'/'.$form_version.'/read/'.$unique_id);
            $customMeta = json_decode($fromData['custom_meta']);

            $Emaildata['ani']               = $customMeta[5]->field_submit_val;
            $Emaildata['call_date']         = (!empty($fromData['call_date'])? date('Y-m-d',strtotime($fromData['call_date'])) :'');
            $Emaildata['call_time']         = (!empty($fromData['call_date'])? date('H:i:s',strtotime($fromData['call_date'])) :'');
            $Emaildata['agentEmail']        = (!empty($fromData['agentEmail'])? $fromData['agentEmail'] :'');
            $Emaildata['agentSupEmail']     = (!empty($fromData['agentSupEmail'])? $fromData['agentSupEmail'] :'');
            $Emaildata['heading']           = 'Evaluation Submitted';
            $subject = 'Evaluation Submitted for Call Id:'.$fromData['call_id'];
            $mail_dynamic_data_measure .= 'Pre defined mail on Evaluation Submit </br>' ;

            $mailSendTo = '';
            if (!empty($Emaildata['agentEmail']) && !empty($Emaildata['agentSupEmail'])) {
                $mailSendTo = $Emaildata['agentEmail'] . ',' . $Emaildata['agentSupEmail'];
            } else if (!empty($Emaildata['agentEmail'])) {
                $mailSendTo = $Emaildata['agentEmail'];
            } else if (!empty($Emaildata['agentSupEmail'])) {
                $mailSendTo = $Emaildata['agentSupEmail'];
            }
            if(!empty($mailSendTo)){
                $mailSendTo = implode(',', array_unique(explode(',', $mailSendTo)));
                // $alertBody = $this->load->view('email_template/alert_mailer_new',$Emaildata,true);
                //echo '<pre>'; print_r($alertBody); die;
                $mail = Common::send_email($mailSendTo, $subject, $Emaildata, 'email_template.alert_mailer_new');
            }
        }
    }

    /**
     * Feedback Listing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFeedback(Request $request){
        $post_data = $request->all();
        $user = Auth::user();
        $assigned_forms = Common::filter($post_data);
        /*$sup_id_column = (($this->emp_group == 'ops') ? 'evaluator_sup_id' : 'supervisor_id');
        $agent_id_column = (($this->emp_group == 'ops') ? 'evaluator_id' : 'agent_id');
        $sup_name_column = (($this->emp_group == 'ops') ? 'evaluator_sup_name' : 'supervisor');
        $agent_name_column = (($this->emp_group == 'ops') ? 'evaluator_name' : 'agent_name');*/
        $sup_id_column = 'evaluator.supervisor.id';
        $agent_id_column = 'agent.id';
        $sup_name_column = 'evaluator.supervisor.name';
        $agent_name_column = 'agent.name';

        $custom1 = $user->usersHierachy['c1'];
        $custom2 = $user->usersHierachy['c2'];
        $custom3 = $user->usersHierachy['c3'];
        $custom4 = $user->usersHierachy['c4'];

        $emp_audit_data1 = [];
        $emp_audit_data2 = [];
        $cnt = 0;
        $user_id = $user->userId;
        $combined_array = array();

        //FILTER BUTTON CLICK
        if (isset($post_data) && !empty($post_data)) {
            $from_date = $post_data['from_date'];
            $to_date = $post_data['to_date'];
            $date_column = $post_data['date_type'];
            $form_id = isset($post_data['form']) ? $post_data['form'] : '';
            if ($date_column == 'call_date') {
                $call_type = 'call_date_time';
            } else {
                $call_type = 'evaluation_time';
            }
            if (!empty($form_id)) {
                $query = FormDetails::where('form_unique_id', $form_id);
                // $assigned_forms = FormDetails::where('form_unique_id', $form_id)->get();
            } else {
                $query = FormDetails::query();
                // $assigned_forms = Common::get_formDetailsWithHierarchy($where_in['custom1'], $where_in['custom2'], $where_in['custom3'], $where_in['custom4']);
            }
            //DYNAMIC WHERE CONDITION
            $where_in = [];
            $where_in['custom1'] = $custom1;
            $where_in['custom2'] = $custom2;
            $where_in['custom3'] = $custom3;
            $where_in['custom4'] = $custom4;
            if (isset($post_data['custom1']) && !empty($post_data['custom1'])) {
                $where_in['custom1'] = json_decode($post_data['custom1'], 1);
            }
            if (isset($post_data['custom2']) && !empty($post_data['custom2'])) {
                $where_in['custom2'] = json_decode($post_data['custom2'], 1);
            }
            if (isset($post_data['custom3']) && !empty($post_data['custom3'])) {
                $where_in['custom3'] = json_decode($post_data['custom3'], 1);
            }
            if (isset($post_data['custom4']) && !empty($post_data['custom4'])) {
                $where_in['custom4'] = json_decode($post_data['custom4'], 1);
            }

            $custom1 = $where_in['custom1'];
            $custom2 = $where_in['custom2'];
            $custom3 = $where_in['custom3'];
            $custom4 = $where_in['custom4'];

            if(!empty($custom1)){
                $query->orWhere(function($query) use($custom1){
                    foreach($custom1 as $c1){
                        $query->where('custom1', $c1);
                    }
                });
            }
            if(!empty($custom2)){
                $query->orWhere(function($query) use($custom2){
                    foreach($custom2 as $c2){
                        $query->where('custom2', $c2);
                    }
                });
            }
            if(!empty($custom3)){
                $query->orWhere(function($query) use($custom3){
                    foreach($custom3 as $c3){
                        $query->where('custom3', $c3);
                    }
                });
            }
            if(!empty($custom4)){
                $query->orWhere(function($query) use($custom4){
                    foreach($custom4 as $c4){
                        $query->where('custom4', $c4);
                    }
                }); 
            }

            $hierarchy_field = '';
            $hierarchy_value = '';
            if (isset($post_data['manager2']) && !empty($post_data['manager2'])) {
                $hierarchy_field = 'manager2_id';
                $hierarchy_value = $post_data['manager2'];
            }
            if (isset($post_data['manager1']) && !empty($post_data['manager1'])) {
                $hierarchy_field = 'manager1_id';
                $hierarchy_value = $post_data['manager1'];
            }
            if (isset($post_data['supervisor']) && !empty($post_data['supervisor'])) {
                $hierarchy_field = 'supervisor_id';
                $hierarchy_value = $post_data['supervisor'];
            }
            if (isset($post_data['agent']) && !empty($post_data['agent'])) {
                $hierarchy_field = 'agent_id';
                $hierarchy_value = $post_data['agent'];
            }

            if (!empty($hierarchy_field) && !empty($hierarchy_value)) {
                $query->where($hierarchy_field, $hierarchy_value);
                // $where_in[$hierarchy_field] = $hierarchy_value;
            }


            /*if (!empty($form_id)) {
                $assigned_forms = $this->common->get_distinct_whereInMultiple('forms_details', ['form_name', 'form_unique_id', 'display_name', 'form_version', 'form_attributes', 'rating_attr', 'rating_attr_name', 'form_status', 'custom1', 'pass_rate', 'form_weightage', 'feedback_tat', 'user_type', 'user_id', 'created_date', 'updated_date'], ['form_unique_id' => $form_id]);
            } else {
                $assigned_forms = $this->get_formDetailsWithHierarchy($custom1, $custom2, $custom3, $custom4);
            }*/

            //$where = ["evaluation_status" => 'Completed', "audit_status !=" => 'A', "CAST($date_column as DATE) >=" => $from_date, "CAST($date_column as DATE) <=" => $to_date];

            /*if ($this->input->post('filter_proxy') <> '') {
                $where[$sup_id_column] = $post_data['filter_proxy'];
            } elseif ($this->sup_admin == '' && $this->input->post('filter_overall') == 'my_team') {
                $where[$sup_id_column] = $user_id;
            }*/
            $query->where('evaluation_status', 'Completed')->where('audit_status','!=','A')->where($date_column,'>=',$from_date)->where($date_column,'<=',$to_date);
            $assigned_forms = $query->get();

            foreach ($assigned_forms as $form_name_val) {
                $formName = $form_name_val->form_name;
                $formVersion = $form_name_val->form_version;
                $display_name = $form_name_val->display_name;
                $feedback_tat = $form_name_val->feedback_tat;

                // $where["form_version"] = $formVersion;
                $post_data['form'] = $formName;
                $post_data['form_version'] = $formVersion;
                $post_data['group_by'] = $agent_id_column;
                $emp_audit_data = Common::filter_feedback($post_data);
                // $this->common->getWhereInMultiple($formName, ['COUNT(*) as count', 'form_version', 'evaluator_name', 'evaluator_id', 'agent_name', 'agent_id', 'supervisor', 'custom1', 'custom2', 'custom3', 'custom4', 'supervisor_id','affiliation'], $where_in, $where, $agent_id_column);
                //echo $this->db->last_query(); die;
                $location = '';
                if (!empty($emp_audit_data)) {
                    foreach ($emp_audit_data as $value) {
                        $pending = 0;
                        $not_met = 0;
                        $met_inside_tat = 0;
                        $met_outside_tat = 0;
                        $acknlg_pending = 0;
                        $acknlg_not_met = 0;
                        $call_escalated = 0;

                        $where1 = ["evaluation_status" => "Completed", "audit_status !=" => 'A', 'form_version' => $formVersion, $agent_id_column => $value->agent_id, "CAST($date_column as DATE) >=" => $from_date, "CAST($date_column as DATE) <=" => $to_date];
                        // if ($post_data['filter_proxy'] <> '') {
                        //     $where1[$sup_id_column] = $post_data['filter_proxy'];
                        // } elseif ($this->sup_admin == '' && $this->input->post('filter_overall') == 'my_team') {
                        //     $where1[$sup_id_column] = $user_id;
                        // }
                        $post_data['custom_field'] = $agent_id_column;
                        $post_data['custom_value'] = $value->agent['id'];
                        $emp_audit_details = Common::filter($post_data);
                        // $this->common->getWhereInMultiple($formName, ['unique_id', 'form_version', 'evaluator_name', 'evaluator_id', 'agent_name', 'agent_id', 'supervisor', 'supervisor_id',  'custom1', 'custom2', 'custom3', 'custom4',  'submit_time', 'feedback_com', 'feedback_by', 'feedback_proxy', 'feedback_date', 'feedback_com_ops', 'feedback_by_ops', 'feedback_date_ops', 'feedback_accept_comment', 'feedback_accept_time', 'agent_exit_status', 'esc_phase_id','affiliation'], $where_in, $where1, 'unique_id');
                        // echo "<pre>"; print_r($emp_audit_details);
                        foreach ($emp_audit_details as $value2) {
                            $feedback_status = $this->feedback_status($formName, $value2->form_version, $feedback_tat, $value2->feedback_com, $value2->feedback_accept_comment, $value2->submit_time, $value2->feedback_date, $value2->feedback_accept_time, $value2->agent_exit_status, $value2->feedback_by, $value2->feedback_proxy, $value2->esc_phase_id);
                            switch ($feedback_status['feedback_ovrall_status']) {
                                case 'Feedback Pending': $pending++;
                                    break;
                                case 'Feedback Not Met': $not_met++;
                                    break;
                                case 'Ack Pending': $acknlg_pending++;
                                    break;
                                case 'Ack Not Met': $acknlg_not_met++;
                                    break;
                                case 'Met Within TAT': $met_inside_tat++;
                                    break;
                                case 'Met Outside TAT': $met_outside_tat++;
                                    break;
                                case 'Call Escalated': $call_escalated++;
                                    break;
                            }
                        }

//                        $display_name = '';
//                        foreach ($form_details as $form_dtls) {
//                            if($form_dtls->form_name == $formName && $form_dtls->form_version == $value->form_version) {
//                                $display_name = $form_dtls->display_name;
//                            }
//                        }

                        $location .= $value2->hierarchy['custom4'] . ', ';
                        $emp_audit_data1[$cnt]['from_date'] = $from_date;
                        $emp_audit_data1[$cnt]['to_date'] = $to_date;
                        $emp_audit_data1[$cnt]['date_column'] = $date_column;
                        $emp_audit_data1[$cnt]['agent_id'] = $value->agent['id'];
                        $emp_audit_data1[$cnt]['agent_name'] = $value->agent['name'];
                        $emp_audit_data1[$cnt]['agent_nameId'] = Common::user_formatedName($value->agent['name'], $value->agent['id']);
                        $emp_audit_data1[$cnt]['lob_display'] = $value2->hierarchy['custom1'];
                        $emp_audit_data1[$cnt]['custom1'] = $value2->hierarchy['custom1'];
                        $emp_audit_data1[$cnt]['custom2'] = $value2->hierarchy['custom2'];
                        $emp_audit_data1[$cnt]['custom3'] = $value2->hierarchy['custom3'];
                        $emp_audit_data1[$cnt]['custom4'] = $location;
                        $emp_audit_data1[$cnt]['display_name'] = Common::form_formatedName($display_name, $formVersion);
                        $emp_audit_data1[$cnt]['form_name'] = $formName;
                        $emp_audit_data1[$cnt]['form_version'] = $formVersion;
                        $emp_audit_data1[$cnt]['affiliation'] = $value2->affiliation;

                        $emp_audit_data1[$cnt]['audit_count_view'] = '<span class="emp_audit_history fb-count primary" data-status="all">' . $value->count . '</span>';
                        $emp_audit_data1[$cnt]['pending_count_view'] = '<span class="emp_audit_history fb-count pending" data-status="Feedback Pending">' . $pending . '</span>';
                        $emp_audit_data1[$cnt]['not_met_count_view'] = '<span class="emp_audit_history fb-count declined" data-status="Feedback Not Met">' . $not_met . '</span>';
                        $emp_audit_data1[$cnt]['met_inside_count_view'] = '<span class="emp_audit_history fb-count accepted" data-status="Met Within TAT">' . $met_inside_tat . '</span>';
                        $emp_audit_data1[$cnt]['met_outside_count_view'] = '<span class="emp_audit_history fb-count declined" data-status="Met Outside TAT">' . $met_outside_tat . '</span>';
                        $emp_audit_data1[$cnt]['acknlg_pending_count_view'] = '<span class="emp_audit_history fb-count pending" data-status="Ack Pending">' . $acknlg_pending . '</span>';
                        $emp_audit_data1[$cnt]['acknlg_not_met_count_view'] = '<span class="emp_audit_history fb-count declined" data-status="Ack Not Met">' . $acknlg_not_met . '</span>';
                        $emp_audit_data1[$cnt]['call_escalated_count_view'] = '<span class="emp_audit_history fb-count primary" data-status="Call Escalated">' . $call_escalated . '</span>';

                        $emp_audit_data1[$cnt]['audit_count'] = $value->count;
                        $emp_audit_data1[$cnt]['pending_count'] = $pending;
                        $emp_audit_data1[$cnt]['not_met_count'] = $not_met;
                        $emp_audit_data1[$cnt]['met_inside_count'] = $met_inside_tat;
                        $emp_audit_data1[$cnt]['met_outside_count'] = $met_outside_tat;
                        $emp_audit_data1[$cnt]['acknlg_pending_count'] = $acknlg_pending;
                        $emp_audit_data1[$cnt]['acknlg_not_met_count'] = $acknlg_not_met;
                        $emp_audit_data1[$cnt]['call_escalated_count'] = $call_escalated;
                        //$emp_audit_data1[$cnt]['feedback_status'] = $this->feedback_label_new($pending, $not_met, $acknlg_pending, $acknlg_not_met, $met_inside_tat, $met_outside_tat, $call_escalated);
                        $emp_audit_data1[$cnt]['feedback'] = round(((($met_inside_tat + $met_outside_tat) / $value->count) * 100), 2) . "%";

                        if ($met_inside_tat == 0 && ($value->count - $pending) == 0) {
                            $emp_audit_data1[$cnt]['feedback_within_tat'] = "0%";
                        } else {
                            $emp_audit_data1[$cnt]['feedback_within_tat'] = @round((($met_inside_tat / ($value->count - $pending)) * 100), 2) . "%";
                        }
                        $data['emp_data'] = $emp_audit_data1;
                        $cnt++;
                    }
                }
            }

            // FEEDBACK SUMMARY START
            $total_auditSum = 0;
            $pending_sum = 0;
            $not_met_sum = 0;
            $met_inside_sum = 0;
            $met_outside_sum = 0;
            $acknlg_pending_sum = 0;
            $acknlg_not_met_sum = 0;
            $call_escalated_sum = 0;

            if (!empty($emp_audit_data1)) {
                foreach ($emp_audit_data1 as $v) {
                    $total_auditSum += (int) $v['audit_count'];
                    $pending_sum += $v['pending_count'];
                    $not_met_sum += $v['not_met_count'];
                    $met_inside_sum += $v['met_inside_count'];
                    $met_outside_sum += $v['met_outside_count'];
                    $acknlg_pending_sum += $v['acknlg_pending_count'];
                    $acknlg_not_met_sum += $v['acknlg_not_met_count'];
                    $call_escalated_sum += $v['call_escalated_count'];
                }

                //$emp_audit_data2[0]['total_auditSum'] = $total_auditSum;
                //$emp_audit_data2[0]['feedback_status'] = $this->feedback_label_new($pending_sum, $not_met_sum, $acknlg_pending_sum, $acknlg_not_met_sum, $met_inside_sum, $met_outside_sum, $call_escalated_sum);
                /* $emp_audit_data2[0]['pending_count'] = $pending_sum;
                  $emp_audit_data2[0]['not_met_count'] = $not_met_sum;
                  $emp_audit_data2[0]['met_inside_count'] = $met_inside_sum;
                  $emp_audit_data2[0]['met_outside_count'] = $met_outside_sum;
                  $emp_audit_data2[0]['acknlg_pending_count'] = $acknlg_pending_sum;
                  $emp_audit_data2[0]['acknlg_not_met_count'] = $acknlg_not_met_sum;
                  $emp_audit_data2[0]['call_escalated_count'] = $call_escalated_sum; */

                $emp_audit_data2[0]['total_auditSum'] = '<span class="emp_audit_history fb-count pending" data-status="all">' . $total_auditSum . '</span>';
                $emp_audit_data2[0]['pending_count'] = '<span class="emp_audit_history fb-count pending" data-status="Feedback Pending">' . $pending_sum . '</span>';
                $emp_audit_data2[0]['not_met_count'] = '<span class="emp_audit_history fb-count declined" data-status="Feedback Not Met">' . $not_met_sum . '</span>';
                $emp_audit_data2[0]['met_inside_count'] = '<span class="emp_audit_history fb-count accepted" data-status="Met Within TAT">' . $met_inside_sum . '</span>';
                $emp_audit_data2[0]['met_outside_count'] = '<span class="emp_audit_history fb-count declined" data-status="Met Outside TAT">' . $met_outside_sum . '</span>';
                $emp_audit_data2[0]['acknlg_pending_count'] = '<span class="emp_audit_history fb-count pending" data-status="Ack Pending">' . $acknlg_pending_sum . '</span>';
                $emp_audit_data2[0]['acknlg_not_met_count'] = '<span class="emp_audit_history fb-count declined" data-status="Ack Not Met">' . $acknlg_not_met_sum . '</span>';
                $emp_audit_data2[0]['call_escalated_count'] = '<span class="emp_audit_history fb-count primary" data-status="Call Escalated">' . $call_escalated_sum . '</span>';

                $emp_audit_data2[0]['feedback'] = round(((($met_inside_sum + $met_outside_sum) / $total_auditSum) * 100), 2) . "%";

                if ($met_inside_sum == 0 && ($total_auditSum - $pending_sum) == 0) {
                    $emp_audit_data2[0]['feedback_within_tat'] = "0%";
                } else {
                    $emp_audit_data2[0]['feedback_within_tat'] = @round((($met_inside_sum / ($total_auditSum - $pending_sum)) * 100), 2) . "%";
                }
            }
            // FEEDBACK SUMMARY END

            // $data['url'] = (($this->emp_group == 'ops') ? 'audit' : 'view');
            $data['url'] = 'audit';
            $data['emp_data'] = $emp_audit_data1;
            $data['summary_data'] = $emp_audit_data2;

            return response()->json([
                            'status'   => '200',
                            'message'  => 'Feedback fetch successfully',
                            'data'     => $data
                        ], 200);
        }

        //COUNT AUDIT CLICK
        // else {
        //     // echo '<pre>'; print_r($_POST); die;
        //     $from_date = ($this->input->post('from_date')) ? $this->input->post('from_date') : $this->input->post('filter_fromDate');
        //     $to_date = ($this->input->post('to_date')) ? $this->input->post('to_date') : $this->input->post('filter_toDate');
        //     $date_column = ($this->input->post('date_column')) ? $this->input->post('date_column') : $this->input->post('filter_date_column');
        //     if ($this->input->post('form_name')) {
        //         $form_name = $this->input->post('form_name');
        //         $form_ver = $this->input->post('form_version');
        //     } else {
        //         $form_name = explode('||', $post_data['filter_form'][0])[0];
        //         $form_ver = explode('||', $post_data['filter_form'][0])[1];
        //     }

        //     $form_details = $this->get_formDetailsName($form_name, $form_ver);
        //     $feedback_tat = $form_details[0]->feedback_tat;
        //     $display_name = Common::form_formatedName($form_details[0]->display_name, $form_ver);

        //     //DYNAMIC WHERE CONDITION
        //     $where_in = [];
        //     if ($this->input->post('filter_lob') <> '') {
        //         $where_in['lob'] = $post_data['filter_lob'];
        //     }
        //     if ($this->input->post('filter_campaign') <> '') {
        //         $where_in['campaign'] = $post_data['filter_campaign'];
        //     }
        //     if ($this->input->post('filter_vendor') <> '') {
        //         $where_in['vendor'] = $post_data['filter_vendor'];
        //     }
        //     if ($this->input->post('filter_location') <> '') {
        //         $where_in['location'] = $post_data['filter_location'];
        //     }

        //     //DYNAMIC WHERE CONDITION
        //     $where = ["evaluation_status" => 'Completed', "audit_status !=" => 'A', 'CAST(' . $date_column . ' as DATE) >=' => $from_date, 'CAST(' . $date_column . ' as DATE) <=' => $to_date];

        //     if ($this->sup_admin <> '') {
        //         if ($this->input->post('agent_id') <> '') {
        //             $where[$agent_id_column] = $this->input->post('agent_id');
        //         }
        //     } elseif ($this->input->post('filter_overall') == '') {
        //         //ON PAGE LOAD COUNT CLICK
        //         $where[$sup_id_column] = $this->session->userdata('empid');
        //         $where[$agent_id_column] = $this->input->post('agent_id');
        //     } else {
        //         //FEEDBACK SUMMARY COUNT CLICK
        //         if ($this->input->post('agent_id') == '') {
        //             if ($this->input->post('filter_proxy') <> '') {
        //                 $where[$sup_id_column] = $post_data['filter_proxy'];
        //             } elseif ($this->sup_admin == '' && $this->input->post('filter_overall') == 'my_team') {
        //                 $where[$sup_id_column] = $this->session->userdata('empid');
        //             }
        //         } else {
        //             //FEEDBACK AGENT COUNT CLICK
        //             if ($this->input->post('filter_proxy') <> '') {
        //                 $where[$sup_id_column] = $post_data['filter_proxy'];
        //                 $where[$agent_id_column] = $this->input->post('agent_id');
        //             } elseif ($this->input->post('filter_overall') == 'my_team') {
        //                 $where[$sup_id_column] = $this->session->userdata('empid');
        //                 $where[$agent_id_column] = $this->input->post('agent_id');
        //             } elseif ($this->input->post('filter_overall') == 'overall') {
        //                 $where[$agent_id_column] = $this->input->post('agent_id');
        //             }
        //         }
        //     }

        //     $form_details = $this->common->getSelectAll('forms_details', ['form_name', 'display_name', 'form_version']);
        //     $singleformname = $this->input->post('form_name');
        //     $singleformver = $this->input->post('form_version');

        //     if ($singleformname == '') {
        //         foreach ($post_data['filter_form'] as $form_name_val) {
        //             $formName_explode = explode('||', $form_name_val);
        //             $formName = $formName_explode[0];
        //             $formVersion = $formName_explode[1];

        //             $display_name = '';
        //             foreach ($form_details as $form_dtls) {
        //                 if($form_dtls->form_name == $formName && $form_dtls->form_version == $formVersion) {
        //                     $display_name = $form_dtls->display_name;
        //                 }
        //             }


        //             $where["form_version"] = $formVersion;
        //             // $fromna = $this->input->post('form_name');
        //             $emp_audit_data = $this->common->getWhereInMultiple($formName, ['unique_id', 'form_version', 'evaluator_name', 'evaluator_sup_name', 'evaluator_sup_id', 'evaluator_id', 'agent_name', 'agent_id', 'supervisor', 'supervisor_id', 'wrap_time', 'pre_fatal_score', 'total_score', 'channels',  'custom1', 'custom2', 'custom3', 'custom4', 'call_id', 'call_date', 'issue_type', 'submit_time', 'feedback_com', 'feedback_by', 'feedback_date', 'feedback_proxy', 'feedback_com_ops', 'feedback_by_ops', 'feedback_date_ops', 'feedback_accept_comment', 'feedback_accept_time', 'agent_exit_status', 'esc_phase_id','affiliation'], $where_in, $where, 'unique_id');
        //             //echo $this->db->last_query();
        //             //echo "<pre>"; print_r($emp_audit_data);die;
        //             $print_data = $this->employee_auditDataLoop($emp_audit_data, $display_name, $formName, $feedback_tat);
        //             array_push($combined_array, $print_data);
        //         }
        //     } else {
        //         $where["form_version"] = $singleformver;
        //         $emp_audit_data = $this->common->getWhereInMultiple($singleformname, ['unique_id', 'form_version', 'evaluator_name', 'evaluator_sup_name', 'evaluator_sup_id', 'evaluator_id', 'agent_name', 'agent_id', 'supervisor', 'supervisor_id', 'wrap_time', 'pre_fatal_score', 'total_score', 'channels',  'custom1', 'custom2', 'custom3', 'custom4',  'call_id', 'call_date', 'issue_type', 'submit_time', 'feedback_com', 'feedback_by', 'feedback_date', 'feedback_proxy', 'feedback_com_ops', 'feedback_by_ops', 'feedback_date_ops', 'feedback_accept_comment', 'feedback_accept_time', 'agent_exit_status', 'esc_phase_id','affiliation'], $where_in, $where, 'unique_id');
        //         //echo $this->db->last_query();
        //         //echo "<pre>"; print_r($emp_audit_data);die;
        //         $print_data = $this->employee_auditDataLoop($emp_audit_data, $display_name, $singleformname, $feedback_tat);
        //         array_push($combined_array, $print_data);
        //     }

        //     $emp_audit_data2['between'] = $from_date . ' To ' . $to_date;
        //     // echo "<pre>"; print_r($print_data);die;
        //     $emp_audit_data2['audit_data'] = call_user_func_array('array_merge', $combined_array);
        //     return response()->json([
        //         'status'   => '200',
        //         'message'  => 'Feedback fetch successfully',
        //         'data'     => $emp_audit_data2
        //     ], 200);
        // }
    }

    public function feedback_status($form_name,$form_ver,$feedback_tat,$feedback_com,$acknlg_com,$evltn_time,$feedback_time,$acknlg_time,$agent_exit_status,$feedback_by,$feedback_proxy,$esc_phase_id) {
        $feedback_submit      = ($feedback_com <> '') ? 'Yes' : 'No';
        $acknlg_submit        = ($acknlg_com <> '') ? 'Yes' : 'No';
        $call_escalated       = ($esc_phase_id <> '') ? 'Yes' : 'No';
        $esclton_status       = Common::escalation_status($esc_phase_id);

        $cur_time             = date('Y-m-d H:i:s');
        $eval_submit_time     = $evltn_time;
        $feedback_submit_time = $feedback_time;
        $acknlg_submit_time   = $acknlg_time;

        $esc_close_date = 'N/A';
        if($esclton_status == 'close') {
            $post_data['form'] = $form_name.'_escalation';
            $post_data['form_version'] = $form_ver;
            $esc_details = Common::filter($post_data);
            // $this->common->getWhereSelectAll($form_name.'_escalation', ['esc_closure_date'], ['unique_id'=> $unique_id, 'form_version'=> $form_ver]);
            $esc_close_date = $esc_details[0]->esc_closure_date;
            
            /*$cur_eval_timeDiff    = floor(($cur_time - $esc_close_time) / 3600);
            $fdbck_eval_timeDiff  = floor(($feedback_submit_time - $eval_submit_time) / 3600);
            $acknlg_yes_timeDiff  = floor(($acknlg_submit_time - $esc_close_time) / 3600);
            $acknlg_no_timeDiff   = floor(($cur_time - $esc_close_time) / 3600);*/
            
            $cur_eval_timeDiff    = Common::get_feedback_time_diffrence($esc_close_date, $cur_time);
            $fdbck_eval_timeDiff  = Common::get_feedback_time_diffrence($eval_submit_time, $feedback_submit_time);
            $acknlg_yes_timeDiff  = Common::get_feedback_time_diffrence($esc_close_date, $acknlg_submit_time);
            $acknlg_no_timeDiff   = Common::get_feedback_time_diffrence($esc_close_date, $cur_time);
            
            
        }
        else {
            /*$cur_eval_timeDiff    = floor(($cur_time - $eval_submit_time) / 3600);            
            $fdbck_eval_timeDiff  = floor(($feedback_submit_time - $eval_submit_time) / 3600);
            $acknlg_yes_timeDiff  = floor(($acknlg_submit_time - $eval_submit_time) / 3600);
            $acknlg_no_timeDiff   = floor(($cur_time - $eval_submit_time) / 3600);*/
            
//            $cur_time1             = date('Y-m-d H:i:s');
//            $eval_submit_time1     = $evltn_time;
//            $feedback_submit_time1 = $feedback_time;
//            $acknlg_submit_time1   = $acknlg_time;
            
            $cur_eval_timeDiff = Common::get_feedback_time_diffrence($eval_submit_time, $cur_time);
            $fdbck_eval_timeDiff  = Common::get_feedback_time_diffrence($eval_submit_time, $feedback_submit_time);
            $acknlg_yes_timeDiff  = Common::get_feedback_time_diffrence($eval_submit_time,$acknlg_submit_time);
            $acknlg_no_timeDiff   = Common::get_feedback_time_diffrence($eval_submit_time,$cur_time);
        }

        //$fdbck_eval_timeDiff_min = round(($feedback_submit_time - $eval_submit_time) / 60);
        //$cur_eval_timeDiff_min   = floor(($cur_time - $eval_submit_time) / 60);

        //IF IS IN ESCALATION PROGRESS
        if($esclton_status == 'open') {
            if ($feedback_submit == 'No') {

                //FEEDBACK NOT PROVIDED INSIDE 48 HRS
                if ($cur_eval_timeDiff <= $feedback_tat) {
                    $data['feedback_sup_status'] = "Pending";
                    $data['feedback_ack_status'] = "Pending";
                    $data['feedback_ovrall_status'] = "Call Escalated";
                }
                //FEEDBACK NOT PROVIDED OUTSIDE 48 HRS
                else {
                    $data['feedback_sup_status'] = "Not Met";
                    $data['feedback_ack_status'] = "Not Met";
                    $data['feedback_ovrall_status'] = "Call Escalated";
                }

                $data['feedback_provided_by'] = 'N/A';
                $data['feedback_proxy'] = 'N/A';
                //$data['dif_hours'] = convertToHoursMins($cur_eval_timeDiff_min);
            }

            if ($feedback_submit == 'Yes') {

                //FEEDBACK PROVIDED OUTSIDE 48 HRS
                if (($fdbck_eval_timeDiff > $feedback_tat)) {

                    //AGENT EXIT AFTER EVALUATION AND FEEDBACK OUTSIDE 48 HRS
                    if($agent_exit_status == '3') {
                        $met_inside_tat++;
                        $data['feedback_sup_status'] = "Met Outside TAT";
                        $data['feedback_ack_status'] = "Met Outside TAT";
                        $data['feedback_ovrall_status'] = "Call Escalated";
                    }
                    else {
                        $data['feedback_sup_status'] = "Met Outside TAT";
                        $data['feedback_ack_status'] = "Met Outside TAT";
                        $data['feedback_ovrall_status'] = "Call Escalated";
                    }
                }

                //FEEDBACK PROVIDED INSIDE 48 HRS
                if (($fdbck_eval_timeDiff <= $feedback_tat)) {

                    //ACKNOWLEDGEMENT NOT DONE INSIDE 48 HRS OF EVALUATION.
                    if(($acknlg_submit == 'No') && ($acknlg_no_timeDiff <= $feedback_tat)) {
                        $data['feedback_sup_status'] = "Met Within TAT";
                        $data['feedback_ack_status'] = "Pending";
                        $data['feedback_ovrall_status'] = "Call Escalated";
                    }

                    //ACKNOWLEDGEMENT NOT DONE OUTSIDE 48 HRS OF EVALUATION.
                    elseif(($acknlg_submit == 'No') && ($acknlg_no_timeDiff > $feedback_tat)) {
                        $data['feedback_sup_status'] = "Met Within TAT";
                        $data['feedback_ack_status'] = "Not Met";
                        $data['feedback_ovrall_status'] = "Call Escalated";
                    }

                    //ACKNOWLEDGEMENT DONE WITHIN 48 HRS.
                    elseif(($acknlg_submit == 'Yes') && ($acknlg_yes_timeDiff <= $feedback_tat)) {
                        $data['feedback_sup_status'] = "Met Within TAT";
                        $data['feedback_ack_status'] = "Met Within TAT";
                        $data['feedback_ovrall_status'] = "Call Escalated";
                    }

                    //ACKNOWLEDGEMENT DONE OUTSIDE 48 HRS.
                    elseif(($acknlg_submit == 'Yes') && ($acknlg_yes_timeDiff > $feedback_tat)) {
                        $data['feedback_sup_status'] = "Met Within TAT";
                        $data['feedback_ack_status'] = "Met Outside TAT";
                        $data['feedback_ovrall_status'] = "Call Escalated";
                    }
                }

                //FEEDBACK PROVIDED BY
                $explode = explode('||', $feedback_by);
                $data['feedback_provided_by'] = Common::user_formatedName($explode[0], $explode[1]);

                if($feedback_proxy <> '') {
                    $expld_prxy = explode('||', $feedback_proxy);
                    $data['feedback_proxy'] = Common::user_formatedName($expld_prxy[0], $expld_prxy[1]);
                }
                else {
                    $data['feedback_proxy'] = 'N/A';
                }

                //$data['dif_hours'] = convertToHoursMins($fdbck_eval_timeDiff_min);
            }
        }
        else {
            if ($feedback_submit == 'No') {

                //FEEDBACK NOT PROVIDED INSIDE 48 HRS
                if ($cur_eval_timeDiff <= $feedback_tat) {
                    $data['feedback_sup_status'] = "Pending";
                    $data['feedback_ack_status'] = "Pending";
                    $data['feedback_ovrall_status'] = "Feedback Pending";
                }
                //FEEDBACK NOT PROVIDED OUTSIDE 48 HRS
                else {
                    $data['feedback_sup_status'] = "Not Met";
                    $data['feedback_ack_status'] = "Not Met";
                    $data['feedback_ovrall_status'] = "Feedback Not Met";
                }

                $data['feedback_provided_by'] = 'N/A';
                $data['feedback_proxy'] = 'N/A';
                //$data['dif_hours'] = convertToHoursMins($cur_eval_timeDiff_min);
            }

            if ($feedback_submit == 'Yes') {

                //FEEDBACK PROVIDED OUTSIDE 48 HRS
                if (($fdbck_eval_timeDiff > $feedback_tat)) {

                    //AGENT EXIT AFTER EVALUATION AND FEEDBACK OUTSIDE 48 HRS
                    if($agent_exit_status == '3') {
                        $data['feedback_sup_status'] = "Met Outside TAT";
                        $data['feedback_ack_status'] = "Met Outside TAT";
                        $data['feedback_ovrall_status'] = "Met Within TAT";
                    }
                    else {
                        $data['feedback_sup_status'] = "Met Outside TAT";
                        $data['feedback_ack_status'] = "Met Outside TAT";
                        $data['feedback_ovrall_status'] = "Met Outside TAT";
                    }
                }

                //FEEDBACK PROVIDED INSIDE 48 HRS
                if (($fdbck_eval_timeDiff <= $feedback_tat)) {

                    //ACKNOWLEDGEMENT NOT DONE INSIDE 48 HRS OF EVALUATION.
                    if(($acknlg_submit == 'No') && ($acknlg_no_timeDiff <= $feedback_tat)) {
                        $data['feedback_sup_status'] = "Met Within TAT";
                        $data['feedback_ack_status'] = "Pending";
                        $data['feedback_ovrall_status'] = "Ack Pending";
                    }

                    //ACKNOWLEDGEMENT NOT DONE OUTSIDE 48 HRS OF EVALUATION.
                    elseif(($acknlg_submit == 'No') && ($acknlg_no_timeDiff > $feedback_tat)) {
                        $data['feedback_sup_status'] = "Met Within TAT";
                        $data['feedback_ack_status'] = "Not Met";
                        $data['feedback_ovrall_status'] = "Ack Not Met";
                    }

                    //ACKNOWLEDGEMENT DONE WITHIN 48 HRS.
                    elseif(($acknlg_submit == 'Yes') && ($acknlg_yes_timeDiff <= $feedback_tat)) {
                        $data['feedback_sup_status'] = "Met Within TAT";
                        $data['feedback_ack_status'] = "Met Within TAT";
                        $data['feedback_ovrall_status'] = "Met Within TAT";
                    }

                    //ACKNOWLEDGEMENT DONE OUTSIDE 48 HRS.
                    elseif(($acknlg_submit == 'Yes') && ($acknlg_yes_timeDiff > $feedback_tat)) {
                        $data['feedback_sup_status'] = "Met Within TAT";
                        $data['feedback_ack_status'] = "Met Outside TAT";
                        $data['feedback_ovrall_status'] = "Met Outside TAT";
                    }
                }

                //FEEDBACK PROVIDED BY
                $explode = explode('||', $feedback_by);
                $data['feedback_provided_by'] = Common::user_formatedName($explode[0], $explode[1]);

                if($feedback_proxy <> '') {
                    $expld_prxy = explode('||', $feedback_proxy);
                    $data['feedback_proxy'] = Common::user_formatedName($expld_prxy[0], $expld_prxy[1]);
                }
                else {
                    $data['feedback_proxy'] = 'N/A';
                }

                //$data['dif_hours'] = convertToHoursMins($fdbck_eval_timeDiff_min);
            }
        }

        $data['feedback_tat'] = $feedback_tat;
        $data['feedback_submit'] = $feedback_submit; //YES, NO
        $data['evaluation_time'] = $evltn_time; //DATETIME
        $data['feedback_time'] = $feedback_time; //DATETIME
        $data['acknlg_submit'] = $acknlg_submit; //YES, NO
        $data['acknlg_time'] = $acknlg_time; //DATETIME
        $data['agent_exit_status'] = $agent_exit_status;
        $data['call_escalated'] = ucfirst($call_escalated);
        $data['esclton_status'] = ucfirst($esclton_status);
        $data['esc_close_time'] = $esc_close_date;
        $data['form_name'] = $form_name;
        $data['form_version'] = $form_ver;
        return $data;
    }
}
