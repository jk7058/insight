<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RolesRequest;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
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
    public function store(RolesRequest $request)
    {
        $role = new Role;
        $alreadyAddedRole = $role->where(["user_type"=>$request->user_type, "user_role"=>$request->user_role])->get();
        
        
        if(sizeof($alreadyAddedRole)){
            return response()->json(['status' => 201,'message'=>'User Role and User Type Combination is already added.'], 400);
        }

        $addRole = $role->create([
            "role_id"=>$request->role_id,
            "user_type"=>$request->user_type,
            "user_role"=>$request->user_role,
            "created_by"=>$request->created_by,           
            "status"=>1,           
        ]);
        return response()->json(['status' => 200,'message'=>'Role created successfully.', 'data'=> $addRole], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
   public function show(Request $request)
    {
        $role = new Role;
        if($request->id !=null)  {        
        $Data = $role->find($request->id);
        }else{
            $Data = $role->all();
        }
        return response()->json(['status' => 200,'message'=>'Roles Data.', 'data'=> $Data], 400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',           
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }


        $required = ['user_type','user_role'];

        foreach($required as $requires){
            if ($request->has($requires) && !$request->filled($requires)) {
              return response()->json(['status' => 401,'message'=>$requires.' is required.'], 400);
            }
        }
        

        $alreadyAddedRole = $role->where(["user_type"=>$request->user_type, "user_role"=>$request->user_role,])->where("_id","!=",$request->id)->get();        

        
        if(sizeof($alreadyAddedRole)){
            return response()->json(['status' => 201,'message'=>'User Role and User Type Combination is already added for some other ID.'], 400);
        }

        $role = new Role;        
        $updateRole = $role->find($request->id)->update(
           $request->except($request->id)           
        );
        $updatedData = $role->find($request->id);
        return response()->json(['status' => 200,'message'=>'Role updated successfully.', 'data'=> $updatedData], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
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

        $role = new Role;     
        
        $updateStatus = $role->where("_id",$request->id)->update(['status'=>0]);
        $deleteRole = $role->find($request->id)->delete() ;
        
        return response()->json(['status' => 200,'message'=>'Role deleted successfully.', 'data'=> []], 400);
    }
}
