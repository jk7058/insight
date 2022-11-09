<?php

namespace App\Http\Controllers\Api;

use App\Models\Hierarchy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HierarchyRequest;
use Illuminate\Support\Facades\Validator;


class HierarchyController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HierarchyRequest $request)
    {
#print_r(json_decode(request()->getContent(), true));
   #     exit;
        $hierarchy = new Hierarchy;
        $alreadyAddedHierarchy = $hierarchy->where(["c1"=>$request->c1, "c2"=>$request->c2,"c3"=>$request->c3, "c4"=>$request->c4])->get();

        if(sizeof($alreadyAddedHierarchy)){
            return response()->json(['status' => 201,'message'=>'This combination of data is already added.'], 400);
        }

        $addHierarchy = $hierarchy->create([
            "c1"=>$request->c1,
            "c2"=>$request->c2,
            "c3"=>$request->c3,
            "c4"=>$request->c4,
            "status"=>1,
        ]);

        return response()->json(['status' => 200,'message'=>'Hierarchy created successfully.', 'data'=> $addHierarchy], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hierarchy  $hierarchy
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $hierarchy = new Hierarchy;
        if($request->id !=null)  {
        $Data = $hierarchy->find($request->id);
        }else{
            $Data = $hierarchy->all();
        }
        return response()->json(['status' => 200,'message'=>'Hierarchy Data.', 'data'=> $Data], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hierarchy  $hierarchy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $required = ['c1','c2','c3','c4'];

        foreach($required as $requires){
            if ($request->has($requires) && !$request->filled($requires)) {
              return response()->json(['status' => 401,'message'=>$requires.' is required.'], 400);
            }
        }


        $alreadyAddedHierarchy = $role->where(["c1"=>$request->c1, "c2"=>$request->c2,"c3"=>$request->c3, "c4"=>$request->c4])->where("_id","!=",$request->id)->get();

        if(sizeof($alreadyAddedHierarchy)){
            return response()->json(['status' => 201,'message'=>'This combination of data is already added for some other ID.'], 400);
        }


       /* $currentHierarchy = $role->where("_id","=",$request->id)->get();

        if($request->has("c1") && $request->filled("c1")) {
            $alreadyAddedHierarchy = $role->where(["c1"=>$request->c1, "c2"=>$currentHierarchy->c2,"c3"=>$currentHierarchy->c3, "c4"=>$currentHierarchy->c4])->where("_id","!=",$request->id)->get();
            if(sizeof($alreadyAddedHierarchy)){
              return response()->json(['status' => 201,'message'=>'This combination of data is already added for some other ID.'], 400);
            }
          }
        else if($request->has("c2") && $request->filled("c2")) {
            $alreadyAddedHierarchy = $role->where(["c1"=>$currentHierarchy->c1, "c2"=>$request->c2,"c3"=>$currentHierarchy->c3, "c4"=>$currentHierarchy->c4])->where("_id","!=",$request->id)->get();
            if(sizeof($alreadyAddedHierarchy)){
              return response()->json(['status' => 201,'message'=>'This combination of data is already added for some other ID.'], 400);
            }
          }
        else if($request->has("c3") && $request->filled("c3")) {
            $alreadyAddedHierarchy = $role->where(["c1"=>$currentHierarchy->c1, "c2"=>$currentHierarchy->c2,"c3"=>$request->c3, "c4"=>$currentHierarchy->c4])->where("_id","!=",$request->id)->get();
            if(sizeof($alreadyAddedHierarchy)){
              return response()->json(['status' => 201,'message'=>'This combination of data is already added for some other ID.'], 400);
            }
          }
        else if($request->has("c4") && $request->filled("c4")) {
            $alreadyAddedHierarchy = $role->where(["c1"=>$currentHierarchy->c1, "c2"=>$currentHierarchy->c2,"c3"=>$currentHierarchy->c3, "c4"=>$request->c4])->where("_id","!=",$request->id)->get();
            if(sizeof($alreadyAddedHierarchy)){
              return response()->json(['status' => 201,'message'=>'This combination of data is already added for some other ID.'], 400);
            }
          }    */


        $hierarchy = new Hierarchy;
        $updateHierarchy = $hierarchy->find($request->id)->update(
           $request->except($request->id)
        );
        $updatedData = $hierarchy->find($request->id);
        return response()->json(['status' => 200,'message'=>'Hierarchy updated successfully.', 'data'=> $updatedData], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hierarchy  $hierarchy
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

        $hierarchy = new Hierarchy;
        try {
            $post = $hierarchy->where("_id",$request->id)->first();
          } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 200,'message'=>'Hierarchy Data Not Found.', 'data'=> []], 400);
          }


        $updateStatus = $hierarchy->where("_id",$request->id)->update(['status'=>0]);
        $deleteHierarchy = $post->delete() ;

        return response()->json(['status' => 200,'message'=>'Hierarchy deleted successfully.', 'data'=> []], 400);
    }


    public function getcustom1(Request $request){

      $validator = Validator::make($request->all(), [
        'from_date' => 'required|date_format:Y-m-d',
        'to_date' => 'required|date_format:Y-m-d'
    ]);

    //Send failed response if request is not valid
    if ($validator->fails()) {
        return response()->json(['error' => $validator->messages()], 400);
    }

    $hierarchy = new Hierarchy;
    $lob = $hierarchy->whereBetween("created_at",[$request->from_date, $request->to_date])->where('deleted_at', NULL)->groupBy("c1")->orderBy("c1")->get()->pluck("c1");
    return response()->json(['status' => 200,'message'=>'Hierarchy Lob Data.', 'data'=> $lob], 400);
    }

    public function getcustom2(Request $request){
      $validator = Validator::make($request->all(), [
        'from_date' => 'required|date_format:Y-m-d',
        'to_date' => 'required|date_format:Y-m-d',
        'c1' => 'required'
    ]);

    //Send failed response if request is not valid
    if ($validator->fails()) {
        return response()->json(['error' => $validator->messages()], 400);
    }

    $hierarchy = new Hierarchy;
    $campaign = $hierarchy->where("c1",$request->c1)->whereBetween("created_at",[$request->from_date,$request->to_date])->where('deleted_at', NULL)->groupBy("c2")->orderBy("c2")->get()->pluck("c2");
    return response()->json(['status' => 200,'message'=>'Hierarchy Campaign Data.', 'data'=> $campaign], 400);
    }

    public function getcustom3(Request $request){
      $validator = Validator::make($request->all(), [
        'from_date' => 'required|date_format:Y-m-d',
        'to_date' => 'required|date_format:Y-m-d',
        'c1' => 'required',
        'c2' => 'required'
    ]);

    //Send failed response if request is not valid
    if ($validator->fails()) {
        return response()->json(['error' => $validator->messages()], 400);
    }

    $hierarchy = new Hierarchy;
    $vendor = $hierarchy->where([["c1","=",$request->c1],["c2","=",$request->c2]])->whereBetween("created_at",[$request->from_date,$request->to_date])->where('deleted_at', NULL)->groupBy("c3")->orderBy("c3")->get()->pluck("c3");
    return response()->json(['status' => 200,'message'=>'Hierarchy Vendor Data.', 'data'=> $vendor], 400);
    }

    public function getcustom4(Request $request){
      $validator = Validator::make($request->all(), [
        'from_date' => 'required|date_format:Y-m-d',
        'to_date' => 'required|date_format:Y-m-d',
        'c1' => 'required',
        'c2' => 'required',
        'c3' => 'required'
    ]);

    //Send failed response if request is not valid
    if ($validator->fails()) {
        return response()->json(['error' => $validator->messages()], 400);
    }

    $hierarchy = new Hierarchy;
    $location = $hierarchy->where([["c1","=",$request->c1],["c2","=",$request->c2],["c3","=",$request->c3]])->whereBetween("created_at",[$request->from_date,$request->to_date])->where('deleted_at', NULL)->groupBy("c4")->orderBy("c4")->get()->pluck("c4");
    return response()->json(['status' => 200,'message'=>'Hierarchy Location Data.', 'data'=> $location], 400);
    }

    public function cstomHierarchy(Request $request)
    {
        $hierarchy = new Hierarchy;
        if($request->id !=null)  {
        $Data = $hierarchy->find($request->id);
        }else{
            $Data = $hierarchy->all();
        }
        return response()->json(['status' => 200,'message'=>'Hierarchy Data.', 'data'=> $Data], 400);
    }

    // public function getAll(){
    //     $hierarchys = Hierarchy::all();
    //     foreach($hierarchys as $hierarchy){
    //         Hierarchy::create([
    //                 "c1"=>$hierarchy->c1,
    //                 "c2"=>$hierarchy->c3,
    //                 "c3"=>$hierarchy->c2,
    //                 "c4"=>$hierarchy->c4,
    //                 "status"=>1,
    //             ]);
    //             Hierarchy::where("_id",$hierarchy->_id)->delete();
    //     }
    //
    // }

}
