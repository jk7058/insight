<?php

namespace App\Helpers;

use App\Models\Agent;
use App\Models\FormData;
use App\Models\FormDetails;
use App\Models\Hierarchy;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class Common
{

    public static function getDistinctWhereSelectRow($table, $where = null)
    {
        switch ($table) {
            case "hierarchy":
                $result = Hierarchy::query();
                break;

            case "agents":
                $result = Agent::query();
                break;

            case "user":
                $result = User::query();
                break;
        }
        foreach ($where as $k => $v) {
            $result->where($k, $v);
        }
        return $result->first();
    }

    public static function getWhereInMultiple_sort($table, $select, $wherein, $where = null, $gorup_by = null, $sort = null)
    {
        switch ($table) {
            case "hierarchy":
                $result = Hierarchy::select($select);
                break;

            case "agents":
                $result = Agent::select($select);
                break;

            case "user":
                $result = User::select($select);
                break;
        }
        // $result->whereIn($wherein);

        foreach ($where as $k => $v) {
            $result->where($k, $v);
        }
        if ($gorup_by) {
            $result->groupBy($gorup_by);
        }
        if ($sort) {
            $result->orderBy($sort);
        }

        return $result->get();
    }

    public static function isValidDate($date)
    {
        if(date('Y-m-d', strtotime($date)) === $date)
            $status = 'true';
        else
            $status = 'false';
            
        return $status;
    }

    public static function get_formDetailsWithHierarchy($custom1,$custom2,$custom3,$custom4,$status = null) {
        $query = FormDetails::query();
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
        if($status == 'inactive'){
            $query->where('form_status', '0');
        }
        elseif($status == 'active'){
            $query->where('form_status', '1');
        }
        else{
            $query->where(function($query){
                $query->where('form_status', '0');
                $query->orWhere('form_status', '1');
            });
        }
        $formName = $query->orderBy('effective','desc')->get();
        return $formName;
    }

    public static function filter($post_data) {
        $query = FormData::where('evaluation_status', 'Completed')->where('audit_status', '!=', 'A');

        //custom1
        if(isset($post_data['custom1']) && !empty($post_data['custom1'])){
            $custom1 = json_decode($post_data['custom1'], 1);
            $query->orWhere(function($query) use($custom1){
                foreach($custom1 as $c1){
                    $query->where('hierarchy.custom1', $c1);
                }
            });
        }
        
        //custom2
        if(isset($post_data['custom2']) && !empty($post_data['custom2'])){
            $custom2 = json_decode($post_data['custom2'], 1);
            $query->orWhere(function($query) use($custom2){
                foreach($custom2 as $c2){
                    $query->where('hierarchy.custom2', $c2);
                }
            });
        }
        
        //custom3
        if(isset($post_data['custom3']) && !empty($post_data['custom3'])){
            $custom3 = json_decode($post_data['custom3'], 1);
            $query->orWhere(function($query) use($custom3){
                foreach($custom3 as $c3){
                    $query->where('hierarchy.custom3', $c3);
                }
            });
        }

        //custom4
        if(isset($post_data['custom4']) && !empty($post_data['custom4'])){
            $custom4 = json_decode($post_data['custom4'], 1);
            $query->orWhere(function($query) use($custom4){
                foreach($custom4 as $c4){
                    $query->where('hierarchy.custom4', $c4);
                }
            });
        }

        //call id
        if (isset($post_data['call_id']) && !empty($post_data['call_id'])) {
            $query->where('call.call_id', $post_data['call_id']);
        }

        //agent id
        if (isset($post_data['agent_id']) && !empty($post_data['agent_id'])) {
            $query->where('agent.id', $post_data['agent_id']);
        }

        //From Date
        if (isset($post_data['from_date']) && !empty($post_data['from_date'])) {
            $call_type   = ($post_data['date_type'] == 'call_date') ? 'call.call_date' : 'evaluation_time';
            $query->where($call_type, '>=', $post_data['from_date']);
        }

        //To Date
        if (isset($post_data['to_date']) && !empty($post_data['to_date'])) {
            $call_type   = ($post_data['date_type'] == 'call_date') ? 'call.call_date' : 'evaluation_time';
            $query->where($call_type, '>=', $post_data['to_date']);
        }

        //Form
        if (isset($post_data['form']) && !empty($post_data['form'])) {
            $form_id     = isset($post_data['form']) ? $post_data['form'] : '';
            $query->where('form_name', $post_data['form']);
        }

        //Manager3
        if (isset($post_data['manager3']) && !empty($post_data['manager3'])) {
            $query->where('agent.manager3.id', $post_data['manager3']);
        }

        //Manager2
        if (isset($post_data['manager2']) && !empty($post_data['manager2'])) {
            $query->where('agent.manager2.id', $post_data['manager2']);
        }

        //Manager1
        if (isset($post_data['manager1']) && !empty($post_data['manager1'])) {
            $query->where('agent.manager1.id', $post_data['manager1']);
        }

        //Supervisor
        if (isset($post_data['supervisor']) && !empty($post_data['supervisor'])) {
            $query->where('agent.supervisor.id', $post_data['supervisor']);
        }

        //Agent
        if (isset($post_data['agent']) && !empty($post_data['agent'])) {
            $query->where('agent.id', $post_data['agent']);
        }

        //Affiliation
        if (isset($post_data['affiliation']) && !empty($post_data['affiliation'])) {
            if ($post_data['affiliation'] != 'All') {
                $query->where('affiliation', $post_data['affiliation']);
            }
        }

        //Assign To
        if (isset($post_data['assign_to']) && !empty($post_data['assign_to'])) {
                $query->where('assign_to', $post_data['assign_to']);
        }

         //Form Version
         if (isset($post_data['form_version']) && !empty($post_data['form_version'])) {
            $query->where('form_version', $post_data['form_version']);
        }

        //Custom Field
        if (isset($post_data['custom_field']) && !empty($post_data['custom_field'])) {
            $query->where($post_data['custom_field'], $post_data['custom_value']);
        }

        //Group By
        if (isset($post_data['group_by']) && !empty($post_data['group_by'])) {
            $query->groupBy($post_data['group_by']);
        }

        $records = $query->get();
        return $records;    

    }

    public static function user_formatedName($userName, $empid) {
        if($userName <> '' && $empid <> '') {
            return ucfirst($userName) . '&nbsp;(' . $empid . ')';
        }
    }

    public static function form_formatedName($display_name, $version) {
        if($display_name <> '' && $version <> '') {
            return ucfirst($display_name)." (V".$version.".0)";
        }
    }

    public static function getHoursWithoutWeekend($datetime1){
        $datetime2 = date('Y-m-d H:i:s');

        $timestamp1 = strtotime($datetime1);
        $timestamp2 = strtotime($datetime2);

        $weekend = array(0, 6);

        // if(in_array(date("w", $timestamp1), $weekend) || in_array(date("w", $timestamp2), $weekend))
        // {
        //     //one of the dates is weekend, return 0?
        //     return 0;
        // }

        $diff = $timestamp2 - $timestamp1;
        $one_day = 60 * 60 * 24; //number of seconds in the day

        if($diff < $one_day)
        {
            return floor($diff / 3600);
        }

        $days_between = floor($diff / $one_day);
        $remove_days  = 0;

        for($i = 1; $i <= $days_between; $i++)
        {
        $next_day = $timestamp1 + ($i * $one_day);
        if(in_array(date("w", $next_day), $weekend))
        {
            $remove_days++; 
        }
        }

        return floor(($diff - ($remove_days * $one_day)) / 3600);
    }

    public static function send_email($emails, $subject, $data, $template){
        $mail = Mail::send($template, $data, function($message) use ($emails, $subject) {
            $message->to($emails)
            ->subject($subject);
        });
        return $mail;
    }

    public static function filter_feedback($post_data) {
        $query = FormData::select('COUNT(*) as count','*')->where('evaluation_status', 'Completed')->where('audit_status', '!=', 'A');

        //custom1
        if(isset($post_data['custom1']) && !empty($post_data['custom1'])){
            $custom1 = json_decode($post_data['custom1'], 1);
            $query->orWhere(function($query) use($custom1){
                foreach($custom1 as $c1){
                    $query->where('hierarchy.custom1', $c1);
                }
            });
        }
        
        //custom2
        if(isset($post_data['custom2']) && !empty($post_data['custom2'])){
            $custom2 = json_decode($post_data['custom2'], 1);
            $query->orWhere(function($query) use($custom2){
                foreach($custom2 as $c2){
                    $query->where('hierarchy.custom2', $c2);
                }
            });
        }
        
        //custom3
        if(isset($post_data['custom3']) && !empty($post_data['custom3'])){
            $custom3 = json_decode($post_data['custom3'], 1);
            $query->orWhere(function($query) use($custom3){
                foreach($custom3 as $c3){
                    $query->where('hierarchy.custom3', $c3);
                }
            });
        }

        //custom4
        if(isset($post_data['custom4']) && !empty($post_data['custom4'])){
            $custom4 = json_decode($post_data['custom4'], 1);
            $query->orWhere(function($query) use($custom4){
                foreach($custom4 as $c4){
                    $query->where('hierarchy.custom4', $c4);
                }
            });
        }

        //call id
        if (isset($post_data['call_id']) && !empty($post_data['call_id'])) {
            $query->where('call.call_id', $post_data['call_id']);
        }

        //agent id
        if (isset($post_data['agent_id']) && !empty($post_data['agent_id'])) {
            $query->where('agent.id', $post_data['agent_id']);
        }

        //From Date
        if (isset($post_data['from_date']) && !empty($post_data['from_date'])) {
            $call_type   = ($post_data['date_type'] == 'call_date') ? 'call.call_date' : 'evaluation_time';
            $query->where($call_type, '>=', $post_data['from_date']);
        }

        //To Date
        if (isset($post_data['to_date']) && !empty($post_data['to_date'])) {
            $call_type   = ($post_data['date_type'] == 'call_date') ? 'call.call_date' : 'evaluation_time';
            $query->where($call_type, '>=', $post_data['to_date']);
        }

        //Form
        if (isset($post_data['form']) && !empty($post_data['form'])) {
            $form_id     = isset($post_data['form']) ? $post_data['form'] : '';
            $query->where('form_name', $post_data['form']);
        }

        //Manager3
        if (isset($post_data['manager3']) && !empty($post_data['manager3'])) {
            $query->where('agent.manager3.id', $post_data['manager3']);
        }

        //Manager2
        if (isset($post_data['manager2']) && !empty($post_data['manager2'])) {
            $query->where('agent.manager2.id', $post_data['manager2']);
        }

        //Manager1
        if (isset($post_data['manager1']) && !empty($post_data['manager1'])) {
            $query->where('agent.manager1.id', $post_data['manager1']);
        }

        //Supervisor
        if (isset($post_data['supervisor']) && !empty($post_data['supervisor'])) {
            $query->where('agent.supervisor.id', $post_data['supervisor']);
        }

        //Agent
        if (isset($post_data['agent']) && !empty($post_data['agent'])) {
            $query->where('agent.id', $post_data['agent']);
        }

        //Affiliation
        if (isset($post_data['affiliation']) && !empty($post_data['affiliation'])) {
            if ($post_data['affiliation'] != 'All') {
                $query->where('affiliation', $post_data['affiliation']);
            }
        }

        //Assign To
        if (isset($post_data['assign_to']) && !empty($post_data['assign_to'])) {
                $query->where('assign_to', $post_data['assign_to']);
        }

         //Form Version
         if (isset($post_data['form_version']) && !empty($post_data['form_version'])) {
            $query->where('form_version', $post_data['form_version']);
        }

        //Custom Field
        if (isset($post_data['custom_field']) && !empty($post_data['custom_field'])) {
            $query->where($post_data['custom_field'], $post_data['custom_value']);
        }

        //Group By
        if (isset($post_data['group_by']) && !empty($post_data['group_by'])) {
            $query->groupBy($post_data['group_by']);
        }

        $records = $query->get();
        return $records;    

    }

    public static function escalation_status($esc_phase_id) {
        $status = '';
        if($esc_phase_id == '') {
            $status = 'not escalated';
        }
        elseif($esc_phase_id !== '' && in_array($esc_phase_id, array('1', '2', '3', '5', '6', '8', '9', '10', '11'))) {
            $status = 'open';
        }
        elseif($esc_phase_id !== '' && in_array($esc_phase_id, array('4', '7', '12'))) {
            $status = 'close';
        }
        return $status;
    }

    public static function get_feedback_time_diffrence($evltn_time,$feedback_time){

        $eval_time = date('Y-m-d',strtotime($evltn_time));
        $feed_time = date('Y-m-d',strtotime($feedback_time));
        $time_val = date('H:i:s',strtotime($feedback_time));
        
        $dates = [];
        while($eval_time <= $feed_time) {
            $dates[] = date('Y-m-d', strtotime($eval_time));
            $eval_time = date('Y-m-d',strtotime('+1 day', strtotime($eval_time)));
        }
        
        $weekoff = 0;
        for($i = 0; $i<count($dates); $i++){
            $date = $dates[$i];
            if(date('w', strtotime($date)) == 0 || date('w', strtotime($date)) > 5 ){
                $weekoff++;
            }
        }
        
        if(count($dates) == $weekoff){
            $fdbck_eval_timeDiff  = floor((strtotime($feedback_time) - strtotime($evltn_time)) / 3600);
        }
        else if($weekoff > 0 ) {
            $total_days = count($dates) - $weekoff -1;
            $final_date = date('Y-m-d', strtotime('+'.$total_days.' day', strtotime($evltn_time)));
            if(date('w', strtotime($date)) == 0 || date('w', strtotime($feedback_time)) > 5){
                $time_val = '23:59:59';
            }
            $newtime = $final_date.' '.$time_val;
            $feedback_time2 = date('Y-m-d H:i:s',strtotime($newtime));
            $fdbck_eval_timeDiff  = floor((strtotime($feedback_time2) - strtotime($evltn_time)) / 3600);
        }
        else{
            $fdbck_eval_timeDiff  = floor((strtotime($feedback_time) - strtotime($evltn_time)) / 3600);
        }
        
        return $fdbck_eval_timeDiff;
    }

    // public function get_merged_result($forms,$select,$where_in,$where,$group_by,$order_by){ 
    //     $tables = array_unique(array_column($forms, 'form_name'));
    //     $union_queries = array();
        
    //     foreach($tables as $table){
    //         $this->db->select($select);
    //         $this->db->from($table);
    //         $this->db->where($where);
    //         //$this->db->join('call_roster', 'users.usrID = users_profiles.usrpID');
    //         $this->multiple_where_in($where_in);
    //         if($group_by <> '') {
    //             $this->db->group_by($group_by);
    //         }
    //         $union_queries[] = $this->db->get_compiled_select();
    //     }
        
    //     $union_query = join(' UNION ',$union_queries); // UNION selects only distinct values. Use UNION ALL to also select duplicate values!
    //     if (!empty($order_by)) {
    //         $union_query .= " ORDER BY $order_by[0] $order_by[1]";
    //     }
        
    //     $query = $this->db->query($union_query);
    //     return $query->result();
    // }


}
