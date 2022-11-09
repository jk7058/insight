<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Http\Requests\AgentCreateRequest;
use App\Helpers\Common;
use App\Http\Requests\AgentBulkRequest;
use App\Http\Resources\AgentListResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }

    /**
     * Display a listing of the agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $query = Agent::with('hierarchy');
        if (isset($request->custom1)) {
            $custom1Arr = json_decode($request->custom1, 1);
            if (count($custom1Arr) > 0) {
                $query->whereHas('hierarchy', function ($q) use ($custom1Arr) {
                    $q->whereIn('c1', $custom1Arr);
                });
            }
        }
        if (isset($request->supervisor_id)) {
            $supArr = json_decode($request->supervisor_id, 1);
            if (count($supArr) > 0) {
                $query->whereIn('Level_1.userId', $supArr);
            }
        }
        if (isset($request->status)) {
            $statusArr = json_decode($request->status, 1);
            if (count($statusArr) > 0) {
                $query->whereIn('Status', $statusArr);
            }
        }
        $agent = $query->latest()->get();
        $agents = AgentListResource::collection($agent);
        return response()->json([
            'status'   => '200',
            'message'  => 'Agents fetch successfully.',
            'data'     => $agents
        ], 200);
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
     * Store a newly created agent in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AgentCreateRequest $request)
    {
        $hierarchy = Common::getDistinctWhereSelectRow('hierarchy', ['c1' => $request->custom1, 'c2' => $request->custom2, 'c3' => $request->custom3, 'c4' => $request->custom4]);
        $agents = Common::getDistinctWhereSelectRow('agents', ['agent_id' => $request->user_id, 'Status' => 'Active']);
        $chk_sup = Common::getDistinctWhereSelectRow('user', ['userId' => trim($request->supervisor_id), 'userStatus' => 'Active']);
        if (empty($hierarchy)) {
            return response()->json([
                'status'   => '422',
                'message'  => 'Hierarchy mismatched',
                'data'     => (object)[]
            ], 422);
        } elseif (!empty($agents)) {
            return response()->json([
                'status'   => '422',
                'message'  => 'Agent already exist',
                'data'     => (object)[]
            ], 422);
        } elseif ($request->effective_date < $request->doj) {
            return response()->json([
                'status'   => '422',
                'message'  => 'Effective date cannot less then hire date.',
                'data'     => (object)[]
            ], 422);
        } else {
            $manager1 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($request->manager1_id)]);
            $manager2 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($request->manager2_id)]);
            $manager3 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($request->manager3_id)]);
            $agent = new Agent();
            $agent->agent_id = $request->user_id;
            $agent->agent_name = ucwords(str_replace("'", "&apos;", $request->user_name));
            $agent->agent_email = strtolower($request->user_email);
            $agent->hierarchy_id = $hierarchy->_id;
            $agent->password = Hash::make('Welcome@1234');
            $agent->is_login = 0;
            $agent->Level_1 = [
                'userId' => $chk_sup->userId,
                'name' => $chk_sup->name,
                'email' => $chk_sup->userEmail,
                'userType' => $chk_sup->userType,
                'userRole' => $chk_sup->userRole,
                'userStatus' => $chk_sup->userStatus
            ];
            $agent->Level_2 = [
                'userId' => $manager1->userId,
                'name' => $manager1->name,
                'email' => $manager1->userEmail,
                'userType' => $manager1->userType,
                'userRole' => $manager1->userRole,
                'userStatus' => $manager1->userStatus
            ];
            $agent->Level_3 = [
                'userId' => $manager2->userId,
                'name' => $manager2->name,
                'email' => $manager2->userEmail,
                'userType' => $manager2->userType,
                'userRole' => $manager2->userRole,
                'userStatus' => $manager2->userStatus
            ];
            $agent->Level_4 = [
                'userId' => $manager3->userId,
                'name' => $manager3->name,
                'email' => $manager3->userEmail,
                'userType' => $manager3->userType,
                'userRole' => $manager3->userRole,
                'userStatus' => $manager3->userStatus
            ];
            $agent->Status = 'Active';
            $agent->doj = date('Y-m-d', strtotime($request->doj));
            $agent->AddedOn = date('Y-m-d H:i:s');
            $agent->EffectiveDate = date('Y-m-d', strtotime($request->effective_date));
            $agent->save();
            return response()->json([
                'status'   => '200',
                'message'  => 'Operation Agent Added Successfully!',
                'data'     => (object)[]
            ], 200);
        }
    }

    /**
     * Bulk Agent Upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulk(AgentBulkRequest $request)
    {
        if ($request->hasFile('roster_file')) {
            if (is_uploaded_file($request->file('roster_file')->getPathname())) {
                $open = fopen($request->file('roster_file')->getPathname(), 'r');
                $data = fgetcsv($open, 1000, ",");
                //READING FILE DATA
                if (($open = fopen($request->file('roster_file')->getPathname(), 'r')) !== FALSE) {
                    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                        $sheet_data[] = $data;
                    }
                    fclose($open);
                }
                //MATCH ROW COUNT
                if (count($sheet_data) > 50) {
                    return response()->json([
                        'status'   => '422',
                        'message'  => 'You can not add more than 50 agents in a sheet.',
                        'data'     => (object)[]
                    ], 422);
                }
                //MATCH COLUM COUNT
                else if (count($sheet_data[0]) !== 17) {
                    return response()->json([
                        'status'   => '422',
                        'message'  => "Colum count desen't match.",
                        'data'     => (object)[]
                    ], 422);
                } else {
                    //REMOVE FIRST ROW IMAM
                    unset($sheet_data[0]);
                    $error_arr = [];
                    $error_cnt = 0;
                    $sucss_cnt = 0;
                    $csvrw_cnt = 1;
                    foreach ($sheet_data as $sheet_val) {
                        $emp_id         = trim($sheet_val[0]);
                        $name           = trim($sheet_val[1]);
                        $email          = trim($sheet_val[2]);
                        $custom4        = trim($sheet_val[6]);
                        $custom1        = trim($sheet_val[3]);
                        $custom2        = trim($sheet_val[5]);
                        $custom3        = trim($sheet_val[4]);
                        $sup_id         = trim($sheet_val[7]);
                        $sup_name       = trim($sheet_val[8]);
                        $manager1_id    = trim($sheet_val[9]);
                        $manager2_id    = trim($sheet_val[11]);
                        $manager3_id    = trim($sheet_val[13]);
                        $doj            = trim($sheet_val[16]);
                        $effective_date = trim($sheet_val[15]);
                        //VALIDATION QUERY
                        $chk_empId  = Common::getDistinctWhereSelectRow('agents', ['agent_id' => $emp_id, 'Status' => 'Active']);
                        $chk_email  = Common::getDistinctWhereSelectRow('agents', ['email' => $email]);
                        $hierarchy = Common::getDistinctWhereSelectRow('hierarchy', ['c1' => $custom1, 'c2' => $custom2, 'c3' => $custom3, 'c4' => $custom4]);
                        $chk_sup = Common::getDistinctWhereSelectRow('user', ['userId' => $sup_id, 'userStatus' => 'Active']);

                        //CHECK DUPLICATE SUP ON SAME LOB
                        $data1[] = array(
                            'custom4' => $custom1,
                            'sup_id' => $sup_id,
                        );
                        $data2 = array_unique($data1, SORT_REGULAR);

                        //EMPLOYEE ID VALIDATION
                        if (strlen($emp_id) < 6) {
                            array_push($error_arr, $emp_id . ' is sholud be minimum 6 character in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        if (!empty($chk_empId)) {

                            array_push($error_arr, $emp_id . ' EmpID is already exist in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //NAME VALIDATION
                        elseif (strlen($name) == 0 || strlen($name) >= 255) {
                            array_push($error_arr, $name . 'should not empty and less than 255 character in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //EMAIL VALIDATION
                        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            array_push($error_arr, $email . ' is not a valid Email in row ' . $csvrw_cnt);
                            $error_cnt++;
                        } elseif (!empty($chk_email)) {
                            array_push($error_arr, $email . ' Email is already exist in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //HIERARCHY VALIDATION
                        elseif (empty($hierarchy)) {
                            array_push($error_arr, 'Hierarchy mismatched ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //SUPERVISOR VALIDATION
                        elseif (empty($chk_sup)) {
                            array_push($error_arr, 'Supervisor must be active and should be on the same LOB in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //CHECK DUPLICATE SUP ON SAME LOB
                        elseif (count($data1) <> count($data2)) {
                            array_push($error_arr, 'Duplicate Supervisor found on same Location & LOB in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //DATE OF JOINING VALIDATION
                        elseif (Common::isValidDate($doj) == 'false') {
                            array_push($error_arr, $doj . ' please Enter valid date format (YYYY-mm-dd) in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //EFFECTIVE DATE VALIDATION
                        elseif (Common::isValidDate($effective_date) == 'false') {
                            array_push($error_arr, $effective_date . ' please Enter valid date format (YYYY-mm-dd) in row ' . $csvrw_cnt);
                            $error_cnt++;
                        } elseif (strtotime(date('Y-m-d')) < strtotime($doj)) {
                            array_push($error_arr, $doj . ' Date of joining should be less than current date in row ' . $csvrw_cnt);
                            $error_cnt++;
                        } elseif ($effective_date < $doj) {
                            array_push($error_arr, $effective_date . ' Effective date cannot less then hire date. ' . $csvrw_cnt);
                            $error_cnt++;
                        } else {

                            $manager1 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($manager1_id)]);
                            $manager2 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($manager2_id)]);
                            $manager3 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($manager3_id)]);
                            $agent = new Agent();
                            $agent->agent_id = $emp_id;
                            $agent->agent_name = ucwords($name);
                            $agent->agent_email = strtolower($email);
                            $agent->hierarchy_id = $hierarchy->_id;
                            $agent->password = Hash::make('Welcome@1234');
                            $agent->is_login = 0;
                            $agent->Level_1 = [
                                'userId' => $chk_sup->userId,
                                'name' => $chk_sup->name,
                                'email' => $chk_sup->userEmail,
                                'userType' => $chk_sup->userType,
                                'userRole' => $chk_sup->userRole,
                                'userStatus' => $chk_sup->userStatus
                            ];
                            $agent->Level_2 = [
                                'userId' => $manager1->userId,
                                'name' => $manager1->name,
                                'email' => $manager1->userEmail,
                                'userType' => $manager1->userType,
                                'userRole' => $manager1->userRole,
                                'userStatus' => $manager1->userStatus
                            ];
                            $agent->Level_3 = [
                                'userId' => $manager2->userId,
                                'name' => $manager2->name,
                                'email' => $manager2->userEmail,
                                'userType' => $manager2->userType,
                                'userRole' => $manager2->userRole,
                                'userStatus' => $manager2->userStatus
                            ];
                            $agent->Level_4 = [
                                'userId' => $manager3->userId,
                                'name' => $manager3->name,
                                'email' => $manager3->userEmail,
                                'userType' => $manager3->userType,
                                'userRole' => $manager3->userRole,
                                'userStatus' => $manager3->userStatus
                            ];
                            $agent->Status = 'Active';
                            $agent->doj = date('Y-m-d', strtotime($doj));
                            $agent->AddedOn = date('Y-m-d H:i:s');
                            $agent->EffectiveDate = date('Y-m-d', strtotime($effective_date));
                            $agent->save();
                            $sucss_cnt++;
                        }
                        $csvrw_cnt++;
                    }
                    if ($error_cnt == 0) {
                        return response()->json([
                            'status'   => '200',
                            'message'  => "$sucss_cnt Agent Added sucessfully.",
                            'data'     => (object)[]
                        ], 200);
                    } else {
                        $error_msg = implode('<br>', $error_arr);
                        if ($sucss_cnt > 0)
                            $error_msg = $error_msg . '<br>' . "$sucss_cnt Agent Added sucessfully";
                        return response()->json([
                            'status'   => '200',
                            'message'  => $error_msg,
                            'data'     => (object)[]
                        ], 200);
                    }
                }
            }
        }
    }

    /**
     * Display the specified agent.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chk_empId  = Common::getDistinctWhereSelectRow('agents', ['agent_id' => $id, 'Status' => 'Active']);
        if (!empty($chk_empId)) {
            return response()->json([
                'status'   => '200',
                'message'  => 'Agent fetch successfully',
                'data'     => $chk_empId
            ], 200);
        } else {
            return response()->json([
                'status'   => '400',
                'message'  => 'Agent does not exist.',
                'data'     => (object)[]
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified agent in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AgentCreateRequest $request, $id)
    {
        $hierarchy = Common::getDistinctWhereSelectRow('hierarchy', ['c1' => $request->custom1, 'c2' => $request->custom2, 'c3' => $request->custom3, 'c4' => $request->custom4]);

        $chk_sup = Common::getDistinctWhereSelectRow('user', ['userId' => trim($request->supervisor_id), 'userStatus' => 'Active']);
        if (empty($hierarchy)) {
            return response()->json([
                'status'   => '422',
                'message'  => 'Hierarchy mismatched',
                'data'     => (object)[]
            ], 422);
        } elseif ($request->effective_date < $request->doj) {
            return response()->json([
                'status'   => '422',
                'message'  => 'Effective date cannot less then hire date.',
                'data'     => (object)[]
            ], 422);
        } else {
            $manager1 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($request->manager1_id)]);
            $manager2 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($request->manager2_id)]);
            $manager3 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($request->manager3_id)]);

            $agent = Agent::where('agent_id', $id)->where('Status', 'Active')->first();
            if (isset($agent)) {
                $agent->Status = 'Inactive';
                $agent->InactiveDate = date('Y-m-d H:i:s');
                $agent->save();
            }
            $agents = Common::getDistinctWhereSelectRow('agents', ['agent_id' => $request->user_id, 'Status' => 'Active']);
            if (!empty($agents)) {
                return response()->json([
                    'status'   => '422',
                    'message'  => 'Agent already exist',
                    'data'     => (object)[]
                ], 422);
            }
            $agent = new Agent();
            $agent->agent_id = $request->user_id;
            $agent->agent_name = ucwords(str_replace("'", "&apos;", $request->user_name));
            $agent->agent_email = strtolower($request->user_email);
            $agent->hierarchy_id = $hierarchy->_id;
            $agent->Level_1 = [
                'userId' => $chk_sup->userId,
                'name' => $chk_sup->name,
                'email' => $chk_sup->userEmail,
                'userType' => $chk_sup->userType,
                'userRole' => $chk_sup->userRole,
                'userStatus' => $chk_sup->userStatus
            ];
            $agent->Level_2 = [
                'userId' => $manager1->userId,
                'name' => $manager1->name,
                'email' => $manager1->userEmail,
                'userType' => $manager1->userType,
                'userRole' => $manager1->userRole,
                'userStatus' => $manager1->userStatus
            ];
            $agent->Level_3 = [
                'userId' => $manager2->userId,
                'name' => $manager2->name,
                'email' => $manager2->userEmail,
                'userType' => $manager2->userType,
                'userRole' => $manager2->userRole,
                'userStatus' => $manager2->userStatus
            ];
            $agent->Level_4 = [
                'userId' => $manager3->userId,
                'name' => $manager3->name,
                'email' => $manager3->userEmail,
                'userType' => $manager3->userType,
                'userRole' => $manager3->userRole,
                'userStatus' => $manager3->userStatus
            ];
            $agent->Status = 'Active';
            $agent->doj = date('Y-m-d', strtotime($request->doj));
            $agent->EffectiveDate = date('Y-m-d', strtotime($request->effective_date));
            $agent->save();
            return response()->json([
                'status'   => '200',
                'message'  => 'Operation Agent Updated Successfully!',
                'data'     => (object)[]
            ], 200);
        }
    }

    /**
     * Remove the specified agent from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chk_empId  = Common::getDistinctWhereSelectRow('agents', ['agent_id' => $id, 'Status' => 'Active']);
        if (!empty($chk_empId)) {
            $chk_empId->Status = 'Inactive';
            $chk_empId->InactiveDate = date('Y-m-d H:i:s');
            $chk_empId->save();
            return response()->json([
                'status'   => '200',
                'message'  => 'Agent deleted successfully',
                'data'     => (object)[]
            ], 200);
        } else {
            return response()->json([
                'status'   => '400',
                'message'  => 'Agent does not exist.',
                'data'     => (object)[]
            ], 400);
        }
    }

    /**
     * Agent Export.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $agents  = Agent::with('hierarchy')->where('Status', 'Active')->get();
        if (!empty($agents)) {
            $filename = "all_agents.csv";
            $fp = fopen('php://output', 'w');
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            $header = ['Emp ID', 'Name', 'Email', 'LOB', 'Campaign', 'Vendor', 'Location', 'Supervisor ID', 'Supervisor Name', 'Manager1 ID', 'Manager1 Name', 'Manager2 ID', 'Manager2 Name', 'Manager3 ID', 'Manager3 Name', 'Effective Date', 'Date of Joining'];
            fputcsv($fp, $header);
            foreach ($agents as $a) {
                $row = [$a->agent_id, $a->agent_name, $a->agent_email, $a->hierarchy->c1, $a->hierarchy->c3, $a->hierarchy->c2, $a->hierarchy->c4, $a->Level_1['userId'], $a->Level_1['name'], $a->Level_2['userId'], $a->Level_2['name'], $a->Level_3['userId'], $a->Level_3['name'], $a->Level_4['userId'], $a->Level_4['name'], $a->EffectiveDate, $a->doj];
                fputcsv($fp, $row);
            }
            fclose($fp);
            exit;
        } else {
            return response()->json([
                'status'   => '400',
                'message'  => 'No agent found.',
                'data'     => (object)[]
            ], 400);
        }
    }

    /**
     * Bulk Agent Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkUpdate(AgentBulkRequest $request)
    {
        if ($request->hasFile('roster_file')) {
            if (is_uploaded_file($request->file('roster_file')->getPathname())) {
                $open = fopen($request->file('roster_file')->getPathname(), 'r');
                $data = fgetcsv($open, 1000, ",");
                //READING FILE DATA
                if (($open = fopen($request->file('roster_file')->getPathname(), 'r')) !== FALSE) {
                    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                        $sheet_data[] = $data;
                    }
                    fclose($open);
                }
                //MATCH ROW COUNT
                if (count($sheet_data) > 50) {
                    return response()->json([
                        'status'   => '422',
                        'message'  => 'You can not add more than 50 agents in a sheet.',
                        'data'     => (object)[]
                    ], 422);
                }
                //MATCH COLUM COUNT
                else if (count($sheet_data[0]) !== 17) {
                    return response()->json([
                        'status'   => '422',
                        'message'  => "Colum count desen't match.",
                        'data'     => (object)[]
                    ], 422);
                } else {
                    //REMOVE FIRST ROW IMAM
                    unset($sheet_data[0]);
                    $error_arr = [];
                    $error_cnt = 0;
                    $sucss_cnt = 0;
                    $csvrw_cnt = 1;
                    foreach ($sheet_data as $sheet_val) {
                        $emp_id         = trim($sheet_val[0]);
                        $name           = trim($sheet_val[1]);
                        $email          = trim($sheet_val[2]);
                        $custom4        = trim($sheet_val[6]);
                        $custom1        = trim($sheet_val[3]);
                        $custom2        = trim($sheet_val[5]);
                        $custom3        = trim($sheet_val[4]);
                        $sup_id         = trim($sheet_val[7]);
                        $sup_name       = trim($sheet_val[8]);
                        $manager1_id    = trim($sheet_val[9]);
                        $manager2_id    = trim($sheet_val[11]);
                        $manager3_id    = trim($sheet_val[13]);
                        $doj            = trim($sheet_val[16]);
                        $effective_date = trim($sheet_val[15]);
                        //VALIDATION QUERY

                        $chk_email  = Common::getDistinctWhereSelectRow('agents', ['email' => $email]);
                        $hierarchy = Common::getDistinctWhereSelectRow('hierarchy', ['c1' => $custom1, 'c2' => $custom2, 'c3' => $custom3, 'c4' => $custom4]);
                        $chk_sup = Common::getDistinctWhereSelectRow('user', ['userId' => $sup_id, 'userStatus' => 'Active']);

                        //CHECK DUPLICATE SUP ON SAME LOB
                        $data1[] = array(
                            'custom4' => $custom1,
                            'sup_id' => $sup_id,
                        );
                        $data2 = array_unique($data1, SORT_REGULAR);

                        //EMPLOYEE ID VALIDATION
                        if (strlen($emp_id) < 6) {
                            array_push($error_arr, $emp_id . ' is sholud be minimum 6 character in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }

                        //NAME VALIDATION
                        elseif (strlen($name) == 0 || strlen($name) >= 255) {
                            array_push($error_arr, $name . 'should not empty and less than 255 character in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //EMAIL VALIDATION
                        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            array_push($error_arr, $email . ' is not a valid Email in row ' . $csvrw_cnt);
                            $error_cnt++;
                        } elseif (!empty($chk_email)) {
                            array_push($error_arr, $email . ' Email is already exist in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //HIERARCHY VALIDATION
                        elseif (empty($hierarchy)) {
                            array_push($error_arr, 'Hierarchy mismatched ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //SUPERVISOR VALIDATION
                        elseif (empty($chk_sup)) {
                            array_push($error_arr, 'Supervisor must be active and should be on the same LOB in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //CHECK DUPLICATE SUP ON SAME LOB
                        elseif (count($data1) <> count($data2)) {
                            array_push($error_arr, 'Duplicate Supervisor found on same Location & LOB in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //DATE OF JOINING VALIDATION
                        elseif (Common::isValidDate($doj) == 'false') {
                            array_push($error_arr, $doj . ' please Enter valid date format (YYYY-mm-dd) in row ' . $csvrw_cnt);
                            $error_cnt++;
                        }
                        //EFFECTIVE DATE VALIDATION
                        elseif (Common::isValidDate($effective_date) == 'false') {
                            array_push($error_arr, $effective_date . ' please Enter valid date format (YYYY-mm-dd) in row ' . $csvrw_cnt);
                            $error_cnt++;
                        } elseif (strtotime(date('Y-m-d')) < strtotime($doj)) {
                            array_push($error_arr, $doj . ' Date of joining should be less than current date in row ' . $csvrw_cnt);
                            $error_cnt++;
                        } elseif ($effective_date < $doj) {
                            array_push($error_arr, $effective_date . ' Effective date cannot less then hire date. ' . $csvrw_cnt);
                            $error_cnt++;
                        } else {

                            $manager1 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($manager1_id)]);
                            $manager2 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($manager2_id)]);
                            $manager3 = Common::getDistinctWhereSelectRow('user', ['userId' => trim($manager3_id)]);
                            $agent = Agent::where('agent_id', $emp_id)->where('Status', 'Active')->first();
                            if (isset($agent)) {
                                $agent->Status = 'Inactive';
                                $agent->InactiveDate = date('Y-m-d H:i:s');
                                $agent->save();
                            }
                            $chk_empId  = Common::getDistinctWhereSelectRow('agents', ['agent_id' => $emp_id, 'Status' => 'Active']);
                            if (!empty($chk_empId)) {

                                array_push($error_arr, $emp_id . ' EmpID is already exist in row ' . $csvrw_cnt);
                                $error_cnt++;
                            } else {
                                $agent = new Agent();
                                $agent->agent_id = $emp_id;
                                $agent->agent_name = ucwords($name);
                                $agent->agent_email = strtolower($email);
                                $agent->hierarchy_id = $hierarchy->_id;
                                $agent->password = Hash::make('Welcome@1234');
                                $agent->is_login = 0;
                                $agent->Level_1 = [
                                    'userId' => $chk_sup->userId,
                                    'name' => $chk_sup->name,
                                    'email' => $chk_sup->userEmail,
                                    'userType' => $chk_sup->userType,
                                    'userRole' => $chk_sup->userRole,
                                    'userStatus' => $chk_sup->userStatus
                                ];
                                $agent->Level_2 = [
                                    'userId' => $manager1->userId,
                                    'name' => $manager1->name,
                                    'email' => $manager1->userEmail,
                                    'userType' => $manager1->userType,
                                    'userRole' => $manager1->userRole,
                                    'userStatus' => $manager1->userStatus
                                ];
                                $agent->Level_3 = [
                                    'userId' => $manager2->userId,
                                    'name' => $manager2->name,
                                    'email' => $manager2->userEmail,
                                    'userType' => $manager2->userType,
                                    'userRole' => $manager2->userRole,
                                    'userStatus' => $manager2->userStatus
                                ];
                                $agent->Level_4 = [
                                    'userId' => $manager3->userId,
                                    'name' => $manager3->name,
                                    'email' => $manager3->userEmail,
                                    'userType' => $manager3->userType,
                                    'userRole' => $manager3->userRole,
                                    'userStatus' => $manager3->userStatus
                                ];
                                $agent->Status = 'Active';
                                $agent->doj = date('Y-m-d', strtotime($doj));
                                $agent->AddedOn = date('Y-m-d H:i:s');
                                $agent->EffectiveDate = date('Y-m-d', strtotime($effective_date));
                                $agent->save();
                                $sucss_cnt++;
                            }
                        }
                        $csvrw_cnt++;
                    }
                    if ($error_cnt == 0) {
                        return response()->json([
                            'status'   => '200',
                            'message'  => "$sucss_cnt Agent Added sucessfully.",
                            'data'     => (object)[]
                        ], 200);
                    } else {
                        $error_msg = implode('<br>', $error_arr);
                        if ($sucss_cnt > 0)
                            $error_msg = $error_msg . '<br>' . "$sucss_cnt Agent Added sucessfully";
                        return response()->json([
                            'status'   => '200',
                            'message'  => $error_msg,
                            'data'     => (object)[]
                        ], 200);
                    }
                }
            }
        }
    }

    /**
     * Bulk Agent Delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(AgentBulkRequest $request)
    {
        if ($request->hasFile('roster_file')) {
            if (is_uploaded_file($request->file('roster_file')->getPathname())) {
                $open = fopen($request->file('roster_file')->getPathname(), 'r');
                $data = fgetcsv($open, 1000, ",");
                //READING FILE DATA
                if (($open = fopen($request->file('roster_file')->getPathname(), 'r')) !== FALSE) {
                    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                        $sheet_data[] = $data;
                    }
                    fclose($open);
                }
                //MATCH ROW COUNT
                if (count($sheet_data) > 50) {
                    return response()->json([
                        'status'   => '422',
                        'message'  => 'You can not add more than 50 agents in a sheet.',
                        'data'     => (object)[]
                    ], 422);
                }
                //MATCH COLUM COUNT
                else if (count($sheet_data[0]) !== 17) {
                    return response()->json([
                        'status'   => '422',
                        'message'  => "Colum count desen't match.",
                        'data'     => (object)[]
                    ], 422);
                } else {
                    //REMOVE FIRST ROW IMAM
                    unset($sheet_data[0]);
                    $error_arr = [];
                    $error_cnt = 0;
                    $sucss_cnt = 0;
                    $csvrw_cnt = 1;
                    foreach ($sheet_data as $sheet_val) {
                        $emp_id         = trim($sheet_val[0]);
                        //VALIDATION QUERY
                        $chk_empId  = Common::getDistinctWhereSelectRow('agents', ['agent_id' => $emp_id, 'Status' => 'Active']);
                        //EMPLOYEE ID VALIDATION
                        if (empty($chk_empId)) {
                            array_push($error_arr, $emp_id . ' is does not exist ' . $csvrw_cnt);
                            $error_cnt++;
                        } else {
                            if (isset($chk_empId)) {
                                $chk_empId->Status = 'Inactive';
                                $chk_empId->InactiveDate = date('Y-m-d H:i:s');
                                $chk_empId->save();
                            }
                        }
                        $csvrw_cnt++;
                    }
                    if ($error_cnt == 0) {
                        return response()->json([
                            'status'   => '200',
                            'message'  => "$sucss_cnt Agent deleted sucessfully.",
                            'data'     => (object)[]
                        ], 200);
                    } else {
                        $error_msg = implode('<br>', $error_arr);
                        if ($sucss_cnt > 0)
                            $error_msg = $error_msg . '<br>' . "$sucss_cnt Agent deleted sucessfully";
                        return response()->json([
                            'status'   => '200',
                            'message'  => $error_msg,
                            'data'     => (object)[]
                        ], 200);
                    }
                }
            }
        }
    }

    /**
     * Agent History.
     *
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => '422',
                'message'  => "Validation errors",
                'data'     => $validator->messages()
            ], 422);
        }
        $agents = Agent::where('agent_id', $request->agent_id)->orderBy('created_at')->get();
        if(!$agents->isEmpty()){
            return response()->json([
                'status'   => '200',
                'message'  => 'Agents history fetch successfully.',
                'data'     => $agents
            ], 200);
        } else {
            return response()->json([
                'status'   => '400',
                'message'  => 'Agent does not exist.',
                'data'     => (object)[]
            ], 400);
        }
    }
}
