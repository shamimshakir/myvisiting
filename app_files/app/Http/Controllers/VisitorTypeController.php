<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorType;
use Auth;

class VisitorTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitor_types = VisitorType::where('company_id', Auth::user()->company_id)->get();
        return view('visitor_types/visitor_types', compact('visitor_types'));
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHtmlData()
    {
        $visitor_types = VisitorType::where('company_id', Auth::user()->company_id)->get();
        $perm = PermissionController::getNodePermissionByProfile('visitor_types');
		$data = '';
        $i = 1;
        if(count($visitor_types)){
            foreach ($visitor_types as $visitor_type) {
                $editbtn = "";
                if($perm->edit == $perm->route->id){
                    $editbtn = "<button type='button' data-id=".$visitor_type->id." id='edit-visitor-type-button' class='btn btn-sm btn-warning mr-2'>
                        <i class='fas fa-edit'></i> Edit
                    </button>";
                }
                $sts = "";
                if($visitor_type->activity == 'active'){
                    $sts = "<span class='badge badge-success'>Active</span>";
                }else{
                    $sts = "<span class='badge badge-warning'>Inactive</span>";
                }
                
                $data.= "<tr>
                    <td>".$i."</td>
                    <td>".$visitor_type->name."</td>
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
        $visitor_type = new VisitorType;
        $visitor_type->name = $request->name;
        $visitor_type->activity = $request->activity;
        $visitor_type->company_id = Auth::user()->company_id;
        $visitor_type->created_by = Auth::user()->id;
        $visitor_type->last_update_by = Auth::user()->id;
        $visitor_type->save();

        return response()->json([
            'success' => true,
            'message' => 'Visitor Type Created Successfully'
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
        $visitor_type = VisitorType::where('company_id', Auth::user()->company_id)->where('id', $id)->first();
        return $visitor_type;
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
        $visitor_type = VisitorType::find($id);
        $visitor_type->name = $request->name;
        $visitor_type->activity = $request->activity; 
        $visitor_type->last_update_by = Auth::user()->id;
        $visitor_type->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Visitor Type Updated Successfully'
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