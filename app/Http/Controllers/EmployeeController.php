<?php

namespace App\Http\Controllers;

// use Excel;
use stdClass;
use Carbon\Carbon;
use App\Models\Employee;
use App\Mail\WelcomeMail;
use App\Mail\PasswordReminderMail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exports\EmployeeExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\EmployeeRequest;

class EmployeeController extends Controller
{
    // const MODEL = "App\Models\User";

    // private $statusCode = 200;
    // private $statusText = "OK";
    // private $response = array();

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_user(EmployeeRequest $request)
    {
        $user = new Employee();

        $usersHierachy =  new stdClass();
        $usersHierachy->c1 =  isset($request->users_hierachy_c1) ? $request->users_hierachy_c1 : '';
        $usersHierachy->c2 = isset($request->users_hierachy_c2) ? $request->users_hierachy_c2 : '';
        $usersHierachy->c3 =  isset($request->users_hierachy_c3) ? $request->users_hierachy_c3 : '';
        $usersHierachy->c4 =  isset($request->users_hierachy_c4) ? $request->users_hierachy_c4 : '';

        $Level1 = new stdClass();
        $Level1->userId = isset($request->level1_userid) ? $request->level1_userid : '';
        $Level1->name = isset($request->level1_name) ? $request->level1_name : '';
        $Level1->email = isset($request->level1_email) ? $request->level1_email : '';
        $Level1->userType = isset($request->level1_usertype) ? $request->level1_usertype : '';
        $Level1->userRole = isset($request->level1_userrole) ? $request->level1_userrole : '';
        $Level1->userStatus = isset($request->level1_userstatus) ? $request->level1_userstatus : '';

        $Level2 = new stdClass();
        $Level2->userId = isset($request->level2_userid) ? $request->level2_userid : '';
        $Level2->name = isset($request->level2_name) ? $request->level2_name : '';
        $Level2->email = isset($request->level2_email) ? $request->level2_email : '';
        $Level2->userType = isset($request->level2_usertype) ? $request->level2_usertype : '';
        $Level2->userRole = isset($request->level2_userrole) ? $request->level2_userrole : '';
        $Level2->userStatus = isset($request->level2_userstatus) ? $request->level2_userstatus : '';

        $Level3 = new stdClass();
        $Level3->userId = isset($request->level3_userid) ? $request->level3_userid : '';
        $Level3->name = isset($request->level3_name) ? $request->level3_name : '';
        $Level3->email = isset($request->level3_email) ? $request->level3_email : '';
        $Level3->userType = isset($request->level3_usertype) ? $request->level3_usertype : '';
        $Level3->userRole = isset($request->level3_userrole) ? $request->level3_userrole : '';
        $Level3->userStatus = isset($request->level3_userstatus) ? $request->level3_userstatus : '';

        $moduleAccess_arr = isset($request->ModuleAccess_modulename) ? $request->ModuleAccess_modulename : '';
        $accessType_arr = isset($request->ModuleAccess_accesstype) ? $request->ModuleAccess_accesstype : '';
        $accessLevel_arr = isset($request->ModuleAccess_accesslevel) ? $request->ModuleAccess_accesslevel : '';

        $module_array = array();
        if (!empty($moduleAccess_arr)) {
            for ($m = 0; $m < count($moduleAccess_arr); $m++) {
                $moduleAccess = new stdClass();
                $moduleAccess->ModuleName = $moduleAccess_arr[$m];
                $moduleAccess->AccessType = $accessType_arr[$m];
                $moduleAccess->AccessLevel = $accessLevel_arr[$m];
                array_push($module_array, $moduleAccess);
            }
        }

        $random_password = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $generate_password = substr(str_shuffle($random_password), 0, 8);
        $password_expired_on = date('Y-m-d', strtotime('+30 days', strtotime(date('Y-m-d'))));

        $users = array();
        $users["userId"] = isset($request->user_id) ? $request->user_id : '';
        $users["name"] = isset($request->name) ? $request->name : '';
        $users["username"] = isset($request->user_name) ? $request->user_name : '';
        $users["password"] = Hash::make($generate_password);
        $users["passwordExpiredOn"] = $password_expired_on;
        $users["passwordChangedOn"] = "";
        $users["passwordChangeStatus"] = '0';
        $users["userEmail"] = isset($request->email) ? $request->email : '';
        $users["userType"] = isset($request->user_type) ? $request->user_type : '';
        $users["userRole"] = isset($request->user_role) ? $request->user_role : '';
        $users["userCreatedAt"] = date('Y-m-d H:i:s');
        $users["userStatus"] = 'Active';
        $users["userDOJ"] = isset($request->user_date_join) ? $request->user_date_join : '';
        $users["userEffectiveDate"] = isset($request->user_effective_date) ? $request->user_effective_date : '';
        $users["LastUpdatedDate"] = date('Y-m-d H:i:s');
        $users["ProxyAccess"] = isset($request->proxy_access) ? $request->proxy_access : '';
        $users["ProxyUpdated"] = isset($request->proxy_updated_at) ? date('Y-m-d', strtotime($request->proxy_updated_at)) : '';
        $users["IsAuthorizer"] = isset($request->is_authorizer) ? $request->is_authorizer : '';
        $users["IsReviewer"] = isset($request->is_reviewer) ? $request->is_reviewer : '';
        $users["usersHierachy"] = $usersHierachy;
        $users["Level-1"] = $Level1;
        $users["Level-2"] = $Level2;
        $users["Level-3"] = $Level3;
        $users["ModuleAccess"] = $module_array;
        $users["updated_at"] = date('Y-m-d H:i:s');

        $user['data'] = $user->create($users);
        $user['message'] = 'User created successfully';

        if ($user['data']) {
            $mailData = [
                "user_id" => $request->user_id,
                "user_name" => $request->name,
                "password" => $generate_password,
                "link" => "https://centurylink.insightspro.io/index.php/login"
            ];
            Mail::to($request->email)->send(new WelcomeMail($mailData));
        }

        return response()
            ->json($user)
            ->setStatusCode(200, 'success');
    }

    /** 
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view_user(Request $request)
    {
        $user = new Employee();
        if ($request->id != null) {
            $user['data'] = $user->where('userId', $request->id)->first();
        } else {
            $user['data'] = $user->all();
        }
        $user['message'] = 'User data shown successfully';
        return response()
            ->json($user)
            ->setStatusCode(200, 'success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_user(EmployeeRequest $request)
    {
        $user = new Employee();

        $usersHierachy =  new stdClass();
        $usersHierachy->c1 =  isset($request->users_hierachy_c1) ? $request->users_hierachy_c1 : '';
        $usersHierachy->c2 = isset($request->users_hierachy_c2) ? $request->users_hierachy_c2 : '';
        $usersHierachy->c3 =  isset($request->users_hierachy_c3) ? $request->users_hierachy_c3 : '';
        $usersHierachy->c4 =  isset($request->users_hierachy_c4) ? $request->users_hierachy_c4 : '';

        $Level1 = new stdClass();
        $Level1->userId = isset($request->level1_userid) ? $request->level1_userid : '';
        $Level1->name = isset($request->level1_name) ? $request->level1_name : '';
        $Level1->email = isset($request->level1_email) ? $request->level1_email : '';
        $Level1->userType = isset($request->level1_usertype) ? $request->level1_usertype : '';
        $Level1->userRole = isset($request->level1_userrole) ? $request->level1_userrole : '';
        $Level1->userStatus = isset($request->level1_userstatus) ? $request->level1_userstatus : '';

        $Level2 = new stdClass();
        $Level2->userId = isset($request->level2_userid) ? $request->level2_userid : '';
        $Level2->name = isset($request->level2_name) ? $request->level2_name : '';
        $Level2->email = isset($request->level2_email) ? $request->level2_email : '';
        $Level2->userType = isset($request->level2_usertype) ? $request->level2_usertype : '';
        $Level2->userRole = isset($request->level2_userrole) ? $request->level2_userrole : '';
        $Level2->userStatus = isset($request->level2_userstatus) ? $request->level2_userstatus : '';

        $Level3 = new stdClass();
        $Level3->userId = isset($request->level3_userid) ? $request->level3_userid : '';
        $Level3->name = isset($request->level3_name) ? $request->level3_name : '';
        $Level3->email = isset($request->level3_email) ? $request->level3_email : '';
        $Level3->userType = isset($request->level3_usertype) ? $request->level3_usertype : '';
        $Level3->userRole = isset($request->level3_userrole) ? $request->level3_userrole : '';
        $Level3->userStatus = isset($request->level3_userstatus) ? $request->level3_userstatus : '';

        $moduleAccess_arr = isset($request->ModuleAccess_modulename) ? $request->ModuleAccess_modulename : '';
        $accessType_arr = isset($request->ModuleAccess_accesstype) ? $request->ModuleAccess_accesstype : '';
        $accessLevel_arr = isset($request->ModuleAccess_accesslevel) ? $request->ModuleAccess_accesslevel : '';

        $module_array = array();
        if (!empty($moduleAccess_arr)) {
            for ($m = 0; $m < count($moduleAccess_arr); $m++) {
                $moduleAccess = new stdClass();
                $moduleAccess->ModuleName = $moduleAccess_arr[$m];
                $moduleAccess->AccessType = $accessType_arr[$m];
                $moduleAccess->AccessLevel = $accessLevel_arr[$m];
                array_push($module_array, $moduleAccess);
            }
        }

        $users["userId"] = isset($request->user_id) ? $request->user_id : '';
        $users["userEmail"] = isset($request->email) ? $request->email : '';
        $users["name"] = isset($request->name) ? $request->name : '';
        $users["username"] = isset($request->user_name) ? $request->user_name : '';
        $users["userType"] = isset($request->user_type) ? $request->user_type : '';
        $users["userRole"] = isset($request->user_role) ? $request->user_role : '';
        $users["userStatus"] = isset($request->status) ? $request->status : '';
        $users["userDOJ"] = isset($request->user_date_join) ? $request->user_date_join : '';
        $users["userEffectiveDate"] = isset($request->user_effective_date) ? $request->user_effective_date : '';
        $users["LastUpdatedDate"] = date('Y-m-d H:i:s');
        $users["ProxyAccess"] = isset($request->proxy_access) ? $request->proxy_access : '';
        $users["ProxyUpdated"] = isset($request->proxy_updated_at) ? date('Y-m-d', strtotime($request->proxy_updated_at)) : '';
        $users["IsAuthorizer"] = isset($request->is_authorizer) ? $request->is_authorizer : '';
        $users["IsReviewer"] = isset($request->is_reviewer) ? $request->is_reviewer : '';
        $users["usersHierachy"] = $usersHierachy;
        $users["Level-1"] = $Level1;
        $users["Level-2"] = $Level2;
        $users["Level-3"] = $Level3;
        $users["ModuleAccess"] = $module_array;
        $users["updated_at"] = date('Y-m-d H:i:s');

        $user['data'] = $user->where('userId', $request->user_id)->update($users);
        $user['message'] = 'User details updated successfully';

        return response()
            ->json($user)
            ->setStatusCode(200, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_user(Request $request)
    {
        $user = new Employee();
        if ($request->id != null) {
            $delete_user = $user->where('userId', $request->id)->delete();
        }
        $user['message'] = 'User has been deleted successfully';
        return response()
            ->json($user)
            ->setStatusCode(200, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactive_user(Request $request)
    {
        $user = new Employee();
        if ($request->id != null) {
            $users["userStatus"] = isset($request->user_status) ? $request->user_status : '';
            $inactive_user = $user->where('userId', $request->user_id)->update($users);
        }
        $user['message'] = 'User status updated successfully';
        return response()
            ->json($user)
            ->setStatusCode(200, 'success');
    }

    public function user_bulk_upload(Request $request)
    {
        $file = $request->file('uploaded_file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $this->checkUploadedFileProperties($extension, $fileSize);
            $location = 'uploads';

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . "_" . strtotime(now()) . rand(0, 999);
            $file->move($location, $filename);
            $filepath = public_path($location . "/" . $filename);
            $file = fopen($filepath, "r");
            $importData_arr = array();
            $i = 0;
            while (($filedata = fgetcsv($file, 0, ",")) !== FALSE) {
                $num = count($filedata);
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            // return $importData_arr;
            fclose($file);
            $j = 0;
            foreach ($importData_arr as $importData) {
                $userId = $importData[0];
                $name = $importData[1];
                $username = $importData[2];
                $password = $importData[3];
                $userEmail = $importData[4];
                $userType = $importData[5];
                $userRole = $importData[6];
                $userStatus = $importData[7];
                $userDOJ = $importData[8];
                $userEffectiveDate = $importData[9];
                $ProxyAccess = $importData[10];
                $ProxyUpdated = $importData[11];
                $IsAuthorizer = $importData[12];
                $IsReviewer = $importData[13];
                $usersHierachy_c1 = $importData[14];
                $usersHierachy_c2 = $importData[15];
                $usersHierachy_c3 = $importData[16];
                $usersHierachy_c4 = $importData[17];
                $Level1_email = $importData[18];
                $Level1_name = $importData[19];
                $Level1_userId = $importData[20];
                $Level1_userRole = $importData[21];
                $Level1_userStatus = $importData[22];
                $Level1_userType = $importData[23];
                $Level2_email = $importData[24];
                $Level2_name = $importData[25];
                $Level2_userId = $importData[26];
                $Level2_userRole = $importData[27];
                $Level2_userStatus = $importData[28];
                $Level2_userType = $importData[29];
                $Level3_email = $importData[30];
                $Level3_name = $importData[31];
                $Level3_userId = $importData[32];
                $Level3_userRole = $importData[33];
                $Level3_userStatus = $importData[34];
                $Level3_userType = $importData[35];
                $ModuleAccess_modulename = $importData[36];
                $ModuleAccess_accesstype = $importData[37];
                $ModuleAccess_accesslevel = $importData[38];

                $ProxyAccess_arr = explode(";", $ProxyAccess);

                $usersHierachy =  new stdClass();
                $usersHierachy->c1 =  explode(";", $usersHierachy_c1);
                $usersHierachy->c2 = explode(";", $usersHierachy_c2);
                $usersHierachy->c3 =  explode(";", $usersHierachy_c3);
                $usersHierachy->c4 =  explode(";", $usersHierachy_c4);

                $Level1 = new stdClass();
                $Level1->userId = $Level1_userId;
                $Level1->name = $Level1_name;
                $Level1->email = $Level1_email;
                $Level1->userType = $Level1_userType;
                $Level1->userRole = $Level1_userRole;
                $Level1->userStatus = $Level1_userStatus;

                $Level2 = new stdClass();
                $Level2->userId = $Level2_userId;
                $Level2->name = $Level2_name;
                $Level2->email = $Level2_email;
                $Level2->userType = $Level2_userType;
                $Level2->userRole = $Level2_userRole;
                $Level2->userStatus = $Level2_userStatus;

                $Level3 = new stdClass();
                $Level3->userId = $Level3_userId;
                $Level3->name = $Level3_name;
                $Level3->email = $Level3_email;
                $Level3->userType = $Level3_userType;
                $Level3->userRole = $Level3_userRole;
                $Level3->userStatus = $Level3_userStatus;

                $moduleAccess_arr = explode(";", $ModuleAccess_modulename);
                $accessType_arr = explode(";", $ModuleAccess_accesstype);
                $accessLevel_arr = explode(";", $ModuleAccess_accesslevel);

                $module_array = array();
                for ($m = 0; $m < count($moduleAccess_arr); $m++) {
                    $moduleAccess = new stdClass();
                    $moduleAccess->ModuleName = $moduleAccess_arr[$m];
                    $moduleAccess->AccessType = $accessType_arr[$m];
                    $moduleAccess->AccessLevel = $accessLevel_arr[$m];
                    array_push($module_array, $moduleAccess);
                }

                $j++;
                try {
                    DB::beginTransaction();
                    $user = new Employee();
                    $users['userId'] = $userId;
                    $users['name'] = $name;
                    $users['username'] = $username;
                    $users['password'] = Hash::make($password);
                    $users["passwordExpiredOn"] = "";
                    $users["passwordChangedOn"] = "";
                    $users["passwordChangeStatus"] = "";
                    $users['userEmail'] = $userEmail;
                    $users['userType'] = $userType;
                    $users['userRole'] = $userRole;
                    $users['userCreatedAt'] = date('Y-m-d H:i:s');
                    $users['userStatus'] = $userStatus;
                    $users['userDOJ'] = $userDOJ;
                    $users['userEffectiveDate'] = $userEffectiveDate;
                    $users['ProxyAccess'] = $ProxyAccess_arr;
                    $users['ProxyUpdated'] = $ProxyUpdated;
                    $users['IsAuthorizer'] = $IsAuthorizer;
                    $users['IsReviewer'] = $IsReviewer;
                    $users['usersHierachy'] = $usersHierachy;
                    $users['Level-1'] = $Level1;
                    $users['Level-2'] = $Level2;
                    $users['Level-3'] = $Level3;
                    $users['ModuleAccess'] = $module_array;

                    $user->create($users);
                    DB::commit();
                } catch (\Exception $e) {
                    //throw $th;
                    return $e;
                    DB::rollBack();
                }
            }
            return response()->json([
                'message' => "$j records successfully uploaded"
            ]);
        } else {
            throw new \Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx");
        $maxFileSize = 2097152;
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
            }
        } else {
            throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }

    public function user_export()
    {
        return Excel::download(new EmployeeExport, 'User.csv');
    }

    public function add_module_access(Request $request)
    {
        $user = new Employee();
        $user_id = isset($request->user_id) ? $request->user_id : '';
        $moduleAccess_arr = isset($request->ModuleAccess_modulename) ? $request->ModuleAccess_modulename : '';
        $accessType_arr = isset($request->ModuleAccess_accesstype) ? $request->ModuleAccess_accesstype : '';
        $accessLevel_arr = isset($request->ModuleAccess_accesslevel) ? $request->ModuleAccess_accesslevel : '';

        $module_array = array();
        if (!empty($moduleAccess_arr)) {
            for ($m = 0; $m < count($moduleAccess_arr); $m++) {
                $moduleAccess = new stdClass();
                $moduleAccess->ModuleName = $moduleAccess_arr[$m];
                $moduleAccess->AccessType = $accessType_arr[$m];
                $moduleAccess->AccessLevel = $accessLevel_arr[$m];
                array_push($module_array, $moduleAccess);
            }
        }

        $users["ModuleAccess"] = $module_array;
        $users["updated_at"] = date('Y-m-d H:i:s');

        $user['data'] = $user->where('userId', $user_id)->update($users);
        $user['message'] = 'User details updated successfully';

        return response()
            ->json($user)
            ->setStatusCode(200, 'success');
    }

    public function change_password(Request $request)
    {
        $model = new Employee();
        $user_id = isset($request->user_id) ? $request->user_id : '';
        $old_password = isset($request->old_password) ? $request->old_password : '';
        $new_password = isset($request->new_password) ? $request->new_password : '';
        if ($user_id != '' && $old_password != '') {
            $encrypt_password = Hash::make($old_password);
            $query = $model->where('userId', $user_id)->where('password', $encrypt_password)->first();
            if (!empty($query['userId'])) {
                $password_status = isset($query['passwordChangeStatus']) ? $query['passwordChangeStatus'] : '';
                if ($password_status == 0) {
                    $users['passwordChangeStatus'] = 1;
                    $users['passwordChangedOn'] = date('Y-m-d H:i:s');
                }
                $users["password"] = Hash::make($new_password);
                $users["updated_at"] = date('Y-m-d H:i:s');

                $user['data'] = $model->where('userId', $user_id)->update($users);
                $user['message'] = 'Password changed successfully';
            } else {
                $user['data'] = "";
                $user['message'] = 'Invalid user details';
            }
        } else {
            $user['data'] = "";
            $user['message'] = 'Invalid request';
        }

        return response()
            ->json($user)
            ->setStatusCode(200, 'success');
    }
}
