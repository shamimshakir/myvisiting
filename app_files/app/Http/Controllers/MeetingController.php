<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use Auth;
use Carbon\Carbon;
use DateTime;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $meetings = Meeting::where('company_id', Auth::user()->company_id)->get();
        return view('meetings/meetings', compact('meetings'));
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMeetingList(Request $request){  
        $perm = PermissionController::getNodePermissionByProfile('meetings');
        
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");  
    
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order'); 
    
        $columnIndex = $columnIndex_arr[0]['column'];  
        $columnName = $columnName_arr[$columnIndex]['data'];  
        $columnSortOrder = $order_arr[0]['dir'];  
        
        $where_arr = [ ['company_id', Auth::user()->company_id] ];
        if($request->visitor_id){ 
            array_push($where_arr, ["visitor_id", $request->visitor_id]);
        }
        $start_date = "1970-12-12";
        $end_date = "2423-12-12";
        if($request->from_date && $request->to_date){ 
            $start_date = $request->from_date;
            $end_date = $request->to_date; 
        }
        if($request->meeting_status){
            array_push($where_arr,  ["meeting_status", $request->meeting_status]);
        }
        if($request->meeting_type_id){
            array_push($where_arr,  ["meeting_type_id", $request->meeting_type_id]);
        }
        if($request->meeting_id){
            array_push($where_arr,  ["meeting_id", $request->meeting_id]);
        }
    
        $meetings = Meeting::where($where_arr)
            ->whereBetween('meeting_date', [$start_date, $end_date])
            ->orderBy($columnName,$columnSortOrder)
            ->select('meetings.*')
            ->skip($start)
            ->take($rowperpage)
            ->with('meeting_type')
            ->get();
            
        $totalRecords = Meeting::select('count(*) as allcount')->where($where_arr)->whereBetween('meeting_date', [$start_date, $end_date])->count(); 
       
         $data_arr = array();
         $i = 1;
         foreach($meetings as $meeting){
             
            $type_name = "";
            if($meeting->meeting_type){
                $type_name = $meeting->meeting_type->name;
            }
            
            $meeting_status = '';
            if($meeting->meeting_status == "new"){
                $meeting_status = "<span class='badge badge-primary'>New</span>";
            }else if($meeting->meeting_status == "approved"){
                $meeting_status = "<span class='badge badge-success'>Approved</span>";
            }else if($meeting->meeting_status == "rejected"){
                $meeting_status = "<span class='badge badge-danger'>Rejected</span>";
            }else if($meeting->meeting_status == "expired"){
                $meeting_status = "<span class='badge badge-dark'>Expired</span>";
            }else if($meeting->meeting_status == "checkin"){
                $meeting_status = "<span class='badge badge-info'>Checked In</span>";
            }else if($meeting->meeting_status == "checkout"){
                $meeting_status = "<span class='badge badge-info'>Checked out</span>";
            }
            
            $rejectbtn = "<button class='dropdown-item' type='button' data-id=".$meeting->id." id='reject-meeting-button'><i class='fas fa-user-times'></i> Reject</button>";
            $approvebtn = "<button class='dropdown-item' type='button' data-id=".$meeting->id." id='approve-meeting-button'><i class='fas fa-user-check'></i> Approve</button>";
            $sendbtn = "<button class='dropdown-item btn' type='button' data-id=".$meeting->id." id='send-code-button'><i class='fas fa-comment-alt'></i> Send Code</button>";
            
            $meeting_btn = '';
            if($meeting->meeting_status == "new"){
                $meeting_btn = $rejectbtn.$approvebtn.$sendbtn;
            }else if($meeting->meeting_status == "approved"){
                $meeting_btn = $rejectbtn;
            }else if($meeting->meeting_status == "rejected"){
                $meeting_btn = $approvebtn;
            }
            $editbtn = "";
            if($perm->edit == $perm->route->id){
                $editbtn = "<button class='dropdown-item' type='button' data-id=".$meeting->id." id='edit-meeting-button' class='btn btn-sm btn-primary mr-1'><i class='fas fa-edit'></i> Edit</button> ";
            }
            $action_btn = "";
            if($meeting->meeting_status != "checkout"){
                $action_btn.= "<div class='btn-group'>
                  <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    Actions
                  </button>
                  <div class='dropdown-menu'>
                    $meeting_btn $editbtn
                  </div>
                </div>";
            }
            
            $data_arr[] = array(
              "id" => $i++,
              "meeting_id" => $meeting->meeting_id,
              "visitor_name" => $meeting->visitor->name,
              "meeting_date" => $meeting->meeting_date,
              "meeting_time" => $meeting->meeting_time,
              "type_name" => $type_name, 
              "meeting_reason" => $meeting->meeting_reason,
              "meeting_status" => $meeting_status,
              "meeting_code" => $meeting->meeting_code,    
              "apply_date" => Carbon::parse($meeting->created_at)->format('Y-m-d'),   
              "action" => $action_btn,
            );
         }
    
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords, 
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data_arr
         );
    
         echo json_encode($response);
         exit;
    }
    
    public function getFrontDeskMeetings(Request $request){
        $draw = $request->get('draw');
        $start = $request->get("start");  

        $where_arr = [ ['company_id', Auth::user()->company_id] ];
        if($request->visitor_id){ 
            array_push($where_arr, ["visitor_id", $request->visitor_id]);
        }
        $start_date = "1970-12-12";
        $end_date = "2423-12-12";
        if($request->from_date && $request->to_date){ 
            $start_date = $request->from_date;
            $end_date = $request->to_date; 
        }
        if($request->meeting_status){
            array_push($where_arr,  ["meeting_status", $request->meeting_status]);
        }
        if($request->meeting_id){
            array_push($where_arr,  ["meeting_id", $request->meeting_id]);
        }
    
        $meetings = Meeting::select('meetings.*')
            ->where($where_arr)
            ->whereBetween('meeting_date', [$start_date, $end_date])  
            ->skip($start)
            ->take(50)
            ->with('meeting_type')
            ->get();
            
        $totalRecords = Meeting::select('count(*) as allcount')->where($where_arr)->whereBetween('meeting_date', [$start_date, $end_date])->count(); 
       
         $data_arr = array();
         $i = 1;
         foreach($meetings as $meeting){
             
            $type_name = "";
            if($meeting->meeting_type){
                $type_name = $meeting->meeting_type->name;
            }
            
            $meeting_status = '';
            if($meeting->meeting_status == "new"){
                $meeting_status = "<span class='badge badge-primary'>New</span>";
            }else if($meeting->meeting_status == "approved"){
                $meeting_status = "<span class='badge badge-success'>Approved</span>";
            }else if($meeting->meeting_status == "rejected"){
                $meeting_status = "<span class='badge badge-danger'>Rejected</span>";
            }else if($meeting->meeting_status == "expired"){
                $meeting_status = "<span class='badge badge-dark'>Expired</span>";
            }else if($meeting->meeting_status == "checkin"){
                $meeting_status = "<span class='badge badge-info'>Checked In</span>";
            }else if($meeting->meeting_status == "checkout"){
                $meeting_status = "<span class='badge badge-info'>Checked out</span>";
            }
            
            $action_btn = "";
            if($meeting->meeting_status == "approved"){
                $action_btn.= "<button type='button' data-id=".$meeting->id." id='checkin-button' class='btn btn-sm btn-info mr-1'>CheckIn</button>";
            }
            if($meeting->meeting_status == "checkin"){
                $action_btn.= "<button type='button' data-id=".$meeting->id." id='checkout-button' class='btn btn-sm btn-success mr-1'>CheckOut</button>";
            }
            
            $data_arr[] = array(
              "id" => $i++,
              "meeting_id" => $meeting->meeting_id,
              "visitor_name" => $meeting->visitor->name,
              "meeting_date" => $meeting->meeting_date,
              "meeting_time" => $meeting->meeting_time,
              "type_name" => $type_name, 
              "meeting_status" => $meeting_status,
              "meeting_code" => $meeting->meeting_code,     
              "action" => $action_btn,
            );
         }
    
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords, 
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data_arr
         );
    
         echo json_encode($response);
         exit;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $request;
    }
    
    
    public function AuthorizeToCheckIn(Request $request)
    {
        $validatedData = $request->validate([
            'meeting_code' => 'required',
        ]);
        $id = $request->id;
        $finded_meeting = Meeting::find($id); 
        if($finded_meeting->meeting_code == $request->meeting_code){
            $meeting = Meeting::find($id); 
            $meeting->meeting_status = 'checkin';  
            $meeting->save();
            return response()->json([
                'success' => true,
                'message' => 'Code Matched😊 Let him enter'
            ], 202);
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $generated_meeting_id = date("ymdhis").Auth::user()->company_id.Auth::user()->id;
        $validatedData = $request->validate([
            'meeting_code' => 'required|min:10',
            'meeting_date' => 'required|after_or_equal:today',
            'meeting_time' => 'required',
            'visitor_id' => 'required',
        ]);
        $meeting = new Meeting;
        $meeting->user_id = Auth::user()->id;
        $meeting->visitor_id = $request->visitor_id;
        $meeting->meeting_date = $request->meeting_date;
        $meeting->meeting_time = $request->meeting_time;
        $meeting->meeting_reason = $request->meeting_reason;
        $meeting->meeting_code = $request->meeting_code; 
        $meeting->meeting_type_id = $request->meeting_type_id;   
        $meeting->created_by = Auth::user()->id;  
        $meeting->last_update_by = Auth::user()->id;  
        $meeting->company_id = Auth::user()->company_id; 
        $meeting->meeting_id = $generated_meeting_id; 
        $meeting->meeting_status = 'new';  
        $meeting->save();

        return response()->json([
            'success' => true,
            'message' => 'Meeting Created Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $meeting = Meeting::where('company_id', Auth::user()->company_id)->where('id',$id)->with('user', 'visitor')->first();
        
        return $meeting;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'meeting_date' => 'required',
            'meeting_time' => 'required',
            'visitor_id' => 'required',
        ]);
        
        date_default_timezone_set('Asia/Dhaka'); 
        $current_date_time = date('Y-m-d H:i:s');   
        $m_date_time = strtotime($request->meeting_date." ".$request->meeting_time); 
        $meeting_date_time = date('Y-m-d H:i:s', $m_date_time);  
        
        $meeting = Meeting::find($id);
        $meeting->visitor_id = $request->visitor_id;
        $meeting->meeting_date = $request->meeting_date;
        $meeting->meeting_time = $request->meeting_time;
        $meeting->meeting_type_id = $request->meeting_type_id;     
        $meeting->last_update_by = Auth::user()->id;  
        $meeting->meeting_reason = $request->meeting_reason;
        if($meeting_date_time > $current_date_time){
            $meeting->meeting_status = 'new';  
        }
        $meeting->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Meeting Updated Successfully'
        ]);
    }


    
    public function changeStatus(Request $request){
        if($request->status == 'approved' || $request->status == 'rejected' || $request->status == 'checkout'){
            $meeting = Meeting::find($request->id);
            $meeting->meeting_status = $request->status; 
            $meeting->save();
            if($request->status == 'approved'){
                return response()->json([
                    'success' => true,
                    'message' => 'Meeting Approved Successfully'
                ]);
            }
            if($request->status == 'rejected'){
                return response()->json([
                    'success' => true,
                    'message' => 'Meeting Rejected Successfully'
                ]);
            } 
            if($request->status == 'checkout'){
                return response()->json([
                    'success' => true,
                    'message' => 'Meeting Completed Successfully'
                ]);
            } 
        }
    }

    
    public function frontDesk(){ 
        return view('meetings/frontdesk');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}