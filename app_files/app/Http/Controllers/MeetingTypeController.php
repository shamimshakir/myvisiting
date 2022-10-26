<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingType;
use Auth;

class MeetingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $meeting_types = MeetingType::where('company_id', Auth::user()->company_id)->get();
        return view('meeting_types/meeting_types', compact('meeting_types'));
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHtmlData()
    {
        $meeting_types = MeetingType::where('company_id', Auth::user()->company_id)->get();
        $perm = PermissionController::getNodePermissionByProfile('meeting_types');
		$data = '';
        $i = 1;
        if(count($meeting_types)){
            foreach ($meeting_types as $meeting_type) {
                $editbtn = "";
                if($perm->edit == $perm->route->id){
                    $editbtn = "<button type='button' data-id=".$meeting_type->id." id='edit-meeting-type-button' class='btn btn-sm btn-warning mr-2'>
                        <i class='fas fa-edit'></i> Edit
                    </button>";
                }
                $sts = "";
                if($meeting_type->activity == 'active'){
                    $sts = "<span class='badge badge-success'>Active</span>";
                }else{
                    $sts = "<span class='badge badge-warning'>Inactive</span>";
                }
                
                $data.= "<tr>
                    <td>".$i."</td>
                    <td>".$meeting_type->name."</td>
                    <td>".$sts."</td>
                    <td class='text-center'>
                        ".$editbtn."
                    </td>
                </tr>";
                $i++;
            }
        }else{
            $data.="<td colspan='4'><p class='notfound'>Item not found!</p></td>";
        }
        return $data;
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
        $validatedData = $request->validate([
            'name' => 'required|max:50',
        ]);
        $meeting_type = new MeetingType;
        $meeting_type->name = $request->name;
        $meeting_type->activity = $request->activity;
        $meeting_type->company_id = Auth::user()->company_id;
        $meeting_type->created_by = Auth::user()->id;
        $meeting_type->last_update_by = Auth::user()->id;
        $meeting_type->save();

        return response()->json([
            'success' => true,
            'message' => 'Meeting Type Created Successfully'
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
        $meeting_type = MeetingType::where('company_id', Auth::user()->company_id)->where('id', $id)->first();
        return $meeting_type;
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
            'name' => 'required|max:50',
        ]);
        $meeting_type = MeetingType::find($id);
        $meeting_type->name = $request->name;
        $meeting_type->activity = $request->activity; 
        $meeting_type->last_update_by = Auth::user()->id;
        $meeting_type->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Meeting Type Updated Successfully'
        ]);
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