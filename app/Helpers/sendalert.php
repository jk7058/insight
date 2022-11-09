<?php

namespace App\Helpers;

use App\Models\{
Agent,
FormData,
Hierarchy,
User,
AlertNotification
};
use Illuminate\Support\Facades\Mail;
use App\Helpers\Common;
class SendAlert
{

function sendAlert($fromData,$alertData){
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
            $Emaildata['url'] = site_url().'/bpo/forms/selectnew_ui/'.$formName.'/'.$form_version.'/'.$action.'/'.$unique_id;
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
                            $receiversEmails = $this->getWhereInSelectAll(['userEmail'],'userId',$alert_send_to);
                            foreach($receiversEmails as $key => $eachmail){
                                $receiversEmail[$key] = $eachmail->userEmail;
                            }
                            if((!empty($receiversEmail))){
                                $receiversEmail = implode(',',$receiversEmail);
                                $receiversEmailList = $receiversEmailList.','.$receiversEmail;
                            }
                        }else if(!empty($eachAlertData->alert_send_to)){
                            $alert_send_to = explode(',',$eachAlertData->alert_send_to);
                            $receiversEmails = $this->getWhereInSelectAll(['userEmail'],'userId',$alert_send_to);
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
                        $receiversEmails = $this->getWhereInSelectAll(['userEmail'],'userId',$alert_send_to);
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
                            $receiversEmails = $this->getWhereInSelectAll(['userEmail'],'userId',$alert_send_to);
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
                }else if(($eachAlertData->userId) == ($fromData['sup_id'])){

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
                     //   $alertBody = view('email_template.alert_mailer_new',$Emaildata);

                        if(!empty($receiversEmailList) && isset($eachAlertData->alert_type) && $eachAlertData->alert_type != 'notification'){
                            //echo print_r($alertBody); die;
                            $receiversEmailList = implode(',', array_unique(explode(',', $receiversEmailList)));
                            //$mail = send_email($receiversEmailList, $subject, $alertBody);
                            $mail = Common::send_email($receiversEmailList, $subject, $Emaildata, 'email_template.alert_mailer_new');
                        }
                    }
                    //If Alert Is By Notification
                    if ((!empty($subject)) && $eachAlertData->alert_type == 'notification') {
                        $notiReceivers = $fromData['supervisor_id'];
                        if (!empty($fromData['supervisor_id']) && ($eachAlertData->created_by_type == '4')) {
                            if ((!empty($notiReceivers)) && (!empty($eachAlertData->include_me))) {
                                $notiReceivers = $notiReceivers . ',' . $eachAlertData->userId;
                            }
                        }
                        $notiData['unique_id'] = $unique_id;
                        $notiData['alt_name'] = $subject;
                        $notiData['alt_data'] = json_encode($Emaildata);
                        $notiData['submitted_by'] = (Auth::user()->name) . '||' . (Auth::user()->userId);
                        $notiData['alt_receiver'] = $notiReceivers;
                        $notiData['sup_id'] = $fromData['supervisor_id'];
                        //echo '<pre>'; print_r($notiData); die;
                        $insertData = $this->insert_data($notiData, 'AlertNotification');
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
                                    // if (in_array(trim($formAttrOption), $eachAlertSet->sltRating)) {
                                    //     $allConditionStatusArray[$keyIndex] = 'true';
                                    //     $allConditionDataArray[$keyIndex]['cat'] = $fromData[$eachAlertSet->sltCategory];
                                    //     $allConditionDataArray[$keyIndex]['attribute'] = $eachAttributeSet[1];
                                    //     $allConditionDataArray[$keyIndex]['option'] = $formAttrOption;
                                    // } else {
                                    //     $allConditionStatusArray[$keyIndex] = 'false';
                                    // }
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
                           // $alertBody = view('email_template.alert_mailer_new', $Emaildata);

                            //echo print_r($alertBody);die;
                            // mail send when OR condition is happen
                            if ((!empty($subject)) && $eachAlertData->switchAndOr == 'or' && (int) $cntTrue > 0) {
                                if (isset($eachAlertData->alert_type) && $eachAlertData->alert_type == 'notification') {
                                    $Emaildata['mail_dynamic_data_attribute'] = json_encode($allConditionDataArray);
                                }else{
                                    $receiversEmailList = implode(',', array_unique(explode(',', $receiversEmailList)));
                                    //$receiversEmailList = implode(',',$receiversEmailList);
                                   // $mail = send_email($receiversEmailList, $subject, $alertBody);
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
                                    $mail = send_email($receiversEmailList, $subject, $alertBody);
                                }
                            }
                            //If Alert Is By Notification
                            if ((!empty($subject)) && !empty($receiversEmailList) && $eachAlertData->alert_type == 'notification') {
                                $notiReceivers = $fromData['supervisor_id'];
                                if (!empty($fromData['supervisor_id']) && ($eachAlertData->created_by_type == '4')) {
                                    if ((!empty($notiReceivers)) && (!empty($eachAlertData->include_me))) {
                                        $notiReceivers = $notiReceivers . ',' . $eachAlertData->userId;
                                    }
                                }
                                $notiData['unique_id'] = $unique_id;
                                $notiData['alt_name'] = $subject;
                                $notiData['alt_data'] = json_encode($Emaildata);
                                $notiData['submitted_by'] = (Auth::user()->name) . '||' . (Auth::user()->userId);
                                $notiData['alt_receiver'] = $notiReceivers;
                                $notiData['sup_id'] = $fromData['supervisor_id'];
                                //echo '<pre>'; print_r($notiData); die;
                                $insertData = $this->insert_data($notiData, 'AlertNotification');
                            }
                        }
                    }
                }
            }
        }
    } else {
        $formName     = (!empty($fromData['formName'])? $fromData['formName'] :'');
        $form_version = (!empty($fromData['form_version'])? $fromData['form_version'] :'');
        $Emaildata['url'] = site_url().'/bpo/forms/selectnew_ui/'.$formName.'/'.$form_version.'/read/'.$unique_id;
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
           // $alertBody = view('alert_mailer_new',$Emaildata,true);
            //echo '<pre>'; print_r($alertBody); die;
           // $mail = $this->send_email($mailSendTo, $subject, $Emaildata, 'email_template.alert_mailer_new');
           $mail = Common::send_email($mailSendTo, $subject, $Emaildata, 'email_template.alert_mailer_new');
        }
    }
}

public function getWhereInSelectAll( $select, $whereColumn, $whereData) {
    $result = User::select($select)
    ->distinct()
    ->whereIn($whereColumn, $whereData);
    return $result;
}

public function insert_data($data, $table_name) {
    $data = $table_name.'::'.create([$data]);
    return $data->_id;
}

}
