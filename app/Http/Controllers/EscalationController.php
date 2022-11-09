<?php

namespace App\Http\Controllers;

use stdClass;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Audits;
use App\Models\Escalation;
use App\Http\Requests\EscalationRequest;

class EscalationController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_escalation(EscalationRequest $request)
    {
        $model = new Escalation();

        $escalation = array();
        $escalation["escalation_by"] = isset($request->escalation_by) ? $request->escalation_by : '';
        $escalation["authorizer_level"] = isset($request->authorizer_level) ? $request->authorizer_level : '';
        $escalation["authorize_level_1"] = isset($request->authorize_level_1) ? $request->authorize_level_1 : '';
        $escalation["authorize_level_2"] = isset($request->authorize_level_2) ? $request->authorize_level_2 : '';
        $escalation["authorize_1_tat"] = isset($request->authorize_1_tat) ? $request->authorize_1_tat : '';
        $escalation["authorize_2_tat"] = isset($request->authorize_2_tat) ? $request->authorize_2_tat : '';
        $escalation["resolver_by"] = isset($request->resolver_by) ? $request->resolver_by : '';
        $escalation["resolution_tat"] = isset($request->resolution_tat) ? $request->resolution_tat : '';
        $escalation["reescalation_required"] = isset($request->reescalation_required) ? $request->reescalation_required : '';
        $escalation["reescalation_by"] = isset($request->reescalation_by) ? $request->reescalation_by : '';
        $escalation["reescalation_level"] = isset($request->reescalation_level) ? $request->reescalation_level : '';
        $escalation["reescalation_authorized"] = isset($request->reescalation_authorized) ? $request->reescalation_authorized : '';
        $escalation["reescalation_authorize_tat"] = isset($request->reescalation_authorize_tat) ? $request->reescalation_authorize_tat : '';
        $escalation["reescalation_resolver"] = isset($request->reescalation_resolver) ? $request->reescalation_resolver : '';
        $escalation["reescalation_resolution_tat"] = isset($request->reescalation_resolution_tat) ? $request->reescalation_resolution_tat : '';
        $escalation["escalation_created_at"] = date('Y-m-d H:i:s');
        $escalation["escalation_status"] = 1;

        $data['data'] = $model->create($escalation);
        $data['message'] = 'Escalation created successfully';

        return response()
            ->json($data)
            ->setStatusCode(200, 'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_escalation(EscalationRequest $request)
    {
        $model = new Escalation();

        $escalation = array();
        $escalation["escalation_by"] = isset($request->escalation_by) ? $request->escalation_by : '';
        $escalation["authorizer_level"] = isset($request->authorizer_level) ? $request->authorizer_level : '';
        $escalation["authorize_level_1"] = isset($request->authorize_level_1) ? $request->authorize_level_1 : '';
        $escalation["authorize_level_2"] = isset($request->authorize_level_2) ? $request->authorize_level_2 : '';
        $escalation["authorize_1_tat"] = isset($request->authorize_1_tat) ? $request->authorize_1_tat : '';
        $escalation["authorize_2_tat"] = isset($request->authorize_2_tat) ? $request->authorize_2_tat : '';
        $escalation["resolver_by"] = isset($request->resolver_by) ? $request->resolver_by : '';
        $escalation["resolution_tat"] = isset($request->resolution_tat) ? $request->resolution_tat : '';
        $escalation["reescalation_required"] = isset($request->reescalation_required) ? $request->reescalation_required : '';
        $escalation["reescalation_by"] = isset($request->reescalation_by) ? $request->reescalation_by : '';
        $escalation["reescalation_level"] = isset($request->reescalation_level) ? $request->reescalation_level : '';
        $escalation["reescalation_authorized"] = isset($request->reescalation_authorized) ? $request->reescalation_authorized : '';
        $escalation["reescalation_authorize_tat"] = isset($request->reescalation_authorize_tat) ? $request->reescalation_authorize_tat : '';
        $escalation["reescalation_resolver"] = isset($request->reescalation_resolver) ? $request->reescalation_resolver : '';
        $escalation["reescalation_resolution_tat"] = isset($request->reescalation_resolution_tat) ? $request->reescalation_resolution_tat : '';
        $escalation["escalation_created_at"] = date('Y-m-d H:i:s');
        $escalation["escalation_status"] = 1;

        $data['data'] = $model->where('_id', $request->esc_id)->update($escalation);
        $data['message'] = 'Escalation details updated successfully';

        return response()
            ->json($data)
            ->setStatusCode(200, 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view_escalation(Request $request)
    {
        $model = new Escalation();
        if ($request->escalation_by != '') {
            $data['data'] = $model->whereIn('escalation_by', $request->escalation_by)->first();
        } else {
            $data['data'] = $model->all();
        }
        $data['message'] = 'Escalation data shown successfully';
        return response()
            ->json($data)
            ->setStatusCode(200, 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_escalation(Request $request)
    {
        $model = new Escalation();
        if ($request->esc_id != null) {
            $delete_escalation = $model->where('_id', $request->esc_id)->delete();
        }
        $data['message'] = 'Escalation has been deleted successfully';
        return response()
            ->json($data)
            ->setStatusCode(200, 'success');
    }

    public function available_audits(Request $request)
    {
        $model = new Audits();
        if ($request->call_id != "") {
            $model->where('call.call_id', $request->call_id);
        }
        if ($request->agent_id != "") {
            $model->where('agent.id', $request->agent_id);
        }
        $audit_data = $model->where('evaluation_status', 'Completed')->get();
        $audit_arr = array();
        foreach ($audit_data as $call) {
            $audit_val['call_id'] = $call->call['call_id'];
            $audit_val['form_name'] = $call->form_name;
            $audit_val['agent_name'] = $call->agent['name'];
            $audit_val['agent_id'] = $call->agent['id'];
            $audit_val['evaluation_date'] = $call->evaluation_date;
            $audit_val['audit_status'] = $call->audit_status;
            $audit_val['total_score'] = $call->total_score;
            $audit_val['lob'] = $call->hierarchy['custom1'];
            $audit_val['campaign'] = $call->hierarchy['custom2'];
            $audit_val['vendor'] = $call->hierarchy['custom3'];
            $audit_val['location'] = $call->hierarchy['custom4'];
            $audit_val['evaluator_name'] = isset($call->evaluator) && $call->evaluator != '' ? $call->evaluator['name'] : '';
            $audit_val['evaluator_id'] = isset($call->evaluator) && $call->evaluator != '' ? $call->evaluator['id'] : '';
            $audit_val['assigned_name'] = $call->assigned_by_name;
            $audit_val['assigned_date'] = $call->assigned_to_date;
            array_push($audit_arr, $audit_val);
        }

        $audit['data'] = $audit_arr;
        $audit['message'] = 'Available audits data shown successfully';
        return response()
            ->json($audit)
            ->setStatusCode(200, 'success');
    }

    public function my_audits(Request $request)
    {
        $model = new Audits();
        if ($request->user_id != '') {
            $audit_data = $model::where('evaluator.id', $request->user_id)->get();
            $audit_arr = array();
            foreach ($audit_data as $call) {
                $audit_val['call_id'] = $call->call['call_id'];
                $audit_val['form_name'] = $call->form_name;
                $audit_val['agent_name'] = $call->agent['name'];
                $audit_val['agent_id'] = $call->agent['id'];
                $audit_val['evaluation_date'] = $call->evaluation_date;
                $audit_val['audit_status'] = $call->audit_status;
                $audit_val['total_score'] = $call->total_score;
                $audit_val['lob'] = $call->hierarchy['custom1'];
                $audit_val['campaign'] = $call->hierarchy['custom2'];
                $audit_val['vendor'] = $call->hierarchy['custom3'];
                $audit_val['location'] = $call->hierarchy['custom4'];
                $audit_val['evaluator_name'] = isset($call->evaluator) && $call->evaluator != '' ? $call->evaluator['name'] : '';
                $audit_val['evaluator_id'] = isset($call->evaluator) && $call->evaluator != '' ? $call->evaluator['id'] : '';
                $audit_val['assigned_name'] = $call->assigned_by_name;
                $audit_val['assigned_date'] = $call->assigned_to_date;
                array_push($audit_arr, $audit_val);
            }

            $audit['data'] = $audit_arr;
            $audit['message'] = 'My audits data shown successfully';
        } else {
            $audit['data'] = '';
            $audit['message'] = 'Invalid user id';
        }
        return response()
            ->json($audit)
            ->setStatusCode(200, 'success');
    }

    public function audits_history(Request $request)
    {
        $model = new Audits();
        if ($request->call_id != "") {
            $model->where('call.call_id', $request->call_id);
        }
        if ($request->agent_id != "") {
            $model->where('agent.id', $request->agent_id);
        }
        $audit_data = $model->where('audit_status', 'Completed')->get();
        $audit_arr = array();
        foreach ($audit_data as $call) {
            $audit_val['call_id'] = $call->call['call_id'];
            $audit_val['form_name'] = $call->form_name;
            $audit_val['agent_name'] = $call->agent['name'];
            $audit_val['agent_id'] = $call->agent['id'];
            $audit_val['audit_date'] = $call->audit_date;
            $audit_val['audit_status'] = $call->audit_status;
            $audit_val['total_score'] = $call->total_score;
            $audit_val['lob'] = $call->hierarchy['custom1'];
            $audit_val['campaign'] = $call->hierarchy['custom2'];
            $audit_val['vendor'] = $call->hierarchy['custom3'];
            $audit_val['location'] = $call->hierarchy['custom4'];
            $audit_val['evaluator_name'] = isset($call->evaluator) && $call->evaluator != '' ? $call->evaluator['name'] : '';
            $audit_val['evaluator_id'] = isset($call->evaluator) && $call->evaluator != '' ? $call->evaluator['id'] : '';
            $audit_val['assigned_by'] = $call->assigned_by_name;
            $audit_val['assigned_to'] = $call->assigned_to_date;
            array_push($audit_arr, $audit_val);
        }
        
        $audit['data'] = $audit_arr;
        $audit['message'] = 'Audits history data shown successfully';
        return response()
            ->json($audit)
            ->setStatusCode(200, 'success');
    }

    public function add_review(Request $request)
    {
        $model = new Audits();
        if ($request->id != "") {
            $exist_review_array = array();
            $exist_review = $model->where('_id', $request->id)->first();
            if ($exist_review['review'])
                $exist_review_array = $exist_review['review'];

            $review = array();
            $review['reviewer_id'] = isset($request->reviewer_id) ? $request->reviewer_id : '';
            $review['reviewer_name'] = isset($request->reviewer_name) ? $request->reviewer_name : '';
            $review['review_date'] = isset($request->review_date) ? $request->review_date : '';
            $review['queue_for_the_agent_that_received_contact'] = isset($request->queue_agent) ? $request->queue_agent : '';
            $review['call_chat_transferred_to_agent'] = isset($request->transferred_agent) ? $request->transferred_agent : '';
            $review['customer_type'] = isset($request->customer_type) ? $request->customer_type : '';
            $review['qfiber_recent_install_existing_customer'] = isset($request->qfiber_customer) ? $request->qfiber_customer : '';
            $review['repeat_call_chat'] = isset($request->repeat_call_chat) ? $request->repeat_call_chat : '';
            $review['call_primary_reason'] = isset($request->call_primary_reason) ? $request->call_primary_reason : '';
            $review['call_secondary_reason'] = isset($request->call_secondary_reason) ? $request->call_secondary_reason : '';
            $review['contact_internal_external'] = isset($request->contact_internal_external) ? $request->contact_internal_external : '';
            $review['agent_make_good_first_impression'] = isset($request->agent_first_impression) ? $request->agent_first_impression : '';
            $review['agent_restate_the_reason_for_customer_contact'] = isset($request->agent_restate_the_reason) ? $request->agent_restate_the_reason : '';
            $review['agent_verify_the_caller'] = isset($request->agent_verify_the_caller) ? $request->agent_verify_the_caller : '';
            $review['agent_verify_the_email_on_account'] = isset($request->agent_verify_the_email) ? $request->agent_verify_the_email : '';
            $review['asked_probing_questions_to_clarify_the_reason'] = isset($request->asked_probing_questions) ? $request->asked_probing_questions : '';
            $review['agent_confidently_demonstrated_the_knowledge_to_resolve_the_issue'] = isset($request->knowledge_to_resolve_the_issue) ? $request->knowledge_to_resolve_the_issue : '';
            $review['agent_attempt_to_save_the_customer_by_resolving_the_issue'] = isset($request->attempt_to_save_the_customer) ? $request->attempt_to_save_the_customer : '';
            $review['information_provided_accurate_complete_processes_followed'] = isset($request->complete_processes_followed) ? $request->complete_processes_followed : '';
            $review['there_were_no_opportunities_observed_on_the_contact'] = isset($request->no_opportunities_observed) ? $request->no_opportunities_observed : '';
            $review['case_created_with_all_required_components_reflecting_what_transpired_on_the_contact'] = isset($request->what_transpired_on_the_contact) ? $request->what_transpired_on_the_contact : '';
            $review['agent_follow_the_call_chat_transfer_process'] = isset($request->follow_the_transfer_process) ? $request->follow_the_transfer_process : '';
            $review['agent_follow_the_hold_process'] = isset($request->follow_the_hold_process) ? $request->follow_the_hold_process : '';
            $review['agent_status_the_customer_to_avoid_an_excessive_hold_time_as_outlined_in_the_scoring_matrix'] = isset($request->avoid_an_excessive_hold_time) ? $request->avoid_an_excessive_hold_time : '';
            $review['agent_educate_the_customer_about_self_service_options_available'] = isset($request->self_service_options_available) ? $request->self_service_options_available : '';
            $review['agent_respond_to_the_customer_using_correct_grammar_spelling_punctuation_and_capitalization'] = isset($request->customer_using_correct_grammar) ? $request->customer_using_correct_grammar : '';
            $review['agent_take_ownership_show_empathy_and_make_every_attempt_to_assist_the_customer_resolve_the_issue'] = isset($request->agent_take_ownership_show_empathy) ? $request->agent_take_ownership_show_empathy : '';
            $review['customer_leave_the_call_chat_understanding_what_transpired_on_the_call_and_next_steps'] = isset($request->what_transpired_on_the_call_and_next_steps) ? $request->what_transpired_on_the_call_and_next_steps : '';
            $review['outcomes_for_resolving_the_reason_for_the_contact'] = isset($request->outcomes_for_resolving_the_reason) ? $request->outcomes_for_resolving_the_reason : '';
            $review['agent_offer_further_assistance'] = isset($request->offer_further_assistance) ? $request->offer_further_assistance : '';
            $review['agent_advise_the_customer_of_the_NPS_survey'] = isset($request->advise_the_customer_of_NPS_survey) ? $request->advise_the_customer_of_NPS_survey : '';
            $review['agent_brand_at_the_close_of_the_contact'] = isset($request->brand_at_the_close_of_contact) ? $request->brand_at_the_close_of_contact : '';
            $review['agent_demonstrate_integrity_during_the_customer_contact'] = isset($request->demonstrate_integrity_during_the_customer_contact) ? $request->demonstrate_integrity_during_the_customer_contact : '';
            $review['agent_follow_all_applicable_regulatory_compliance'] = isset($request->follow_all_applicable_regulatory_compliance) ? $request->follow_all_applicable_regulatory_compliance : '';

            array_push($exist_review_array, $review);
            $update_review = $model->where('_id', $request->id)->update(["review" => $exist_review_array]);
            $result['data'] = $exist_review_array;
            $result['message'] = 'Review details updated successfully';
            return response()->json($result)->setStatusCode(200, 'success');
        }
    }

    public function evaluation_summary(Request $request)
    {
        $model = new Audits();
        if ($request->call_id != "") {
            $model->where('call.call_id', $request->call_id);
        }
        if ($request->agent_id != "") {
            $model->where('agent.id', $request->agent_id);
        }
        $audit_data = $model->where('audit_status', 'B')->get();
        $audit_arr = array();
        foreach ($audit_data as $call) {
            $last_rev = array_key_last($call->review);
            $audit_val['call_id'] = $call->call['call_id'];
            $audit_val['agent_name'] = $call->agent['name'];
            $audit_val['agent_id'] = $call->agent['id'];
            $audit_val['supervisor_name'] = $call->evaluator['supervisor']['name'];
            $audit_val['supervisor_id'] = $call->evaluator['supervisor']['id'];
            $audit_val['total_score'] = $call->total_score;
            $audit_val['call_date_time'] = $call->call['call_date'];
            $audit_val['call_duration'] = $call->call['call_duration'];
            $audit_val['evaluation_date_time'] = $call->evaluation_time;
            $audit_val['evaluation_duration'] = $call->audit_status;
            $audit_val['evaluator_name'] = isset($call->evaluator) && $call->evaluator != '' ? $call->evaluator['name'] : '';
            $audit_val['evaluator_id'] = isset($call->evaluator) && $call->evaluator != '' ? $call->evaluator['id'] : '';
            $audit_val['last_reviewed_by'] = $call->review[$last_rev]['reviewer_name'];
            $audit_val['last_reviewed_date'] = $call->review[$last_rev]['review_date'];
            $audit_val['audit_by'] = $call->audit_by;
            $audit_val['form_name'] = $call->form_name;
            $audit_val['lob'] = $call->hierarchy['custom1'];
            $audit_val['campaign'] = $call->hierarchy['custom2'];
            $audit_val['vendor'] = $call->hierarchy['custom3'];
            $audit_val['location'] = $call->hierarchy['custom4'];
            $audit_val['affiliation'] = $call->affiliation;
            $audit_val['ata_status'] = $call->ata_status;
            $audit_val['language'] = $call->language;
            $audit_val['conversation_duration'] = $call->conversation_recording;
            $audit_val['type_of_evaluations'] = $call->type_of_evaluations;
            $audit_val['call_type'] = $call->call_type;
            $audit_val['call_crm'] = $call->crm;
            $audit_val['evaluation_status'] = $call->evaluation_status;
            array_push($audit_arr, $audit_val);
        }

        $audit['data'] = $audit_arr;
        $audit['message'] = 'Evaluation summary data shown successfully';
        return response()->json($audit)->setStatusCode(200, 'success');
    }
}
