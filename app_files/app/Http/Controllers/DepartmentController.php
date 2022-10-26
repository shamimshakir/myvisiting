<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $departments = Department::where('company_id', Auth::user()->company_id)->get();
        return view('departments/departments', compact('departments'));
    }


        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHtmlData()
    { 
        $departments = Department::where('company_id', Auth::user()->company_id)->get();
        $perm = PermissionController::getNodePermissionByProfile('departments');
		$data = '';
        $i = 1;
        if(count($departments)){
            foreach ($departments as $department) {
                $editbtn = "";
                if($perm->edit == $perm->route->id){
                    $editbtn = "<button type='button' data-id=".$department->id." id='edit-department-button' class='btn btn-sm btn-info mr-2'>
                        <i class='fas fa-edit'></i> Edit
                    </button>";
                }
                $sts = "";
                if($department->activity == 'active'){
                    $sts = "<span class='badge badge-success'>Active</span>";
                }else{
                    $sts = "<span class='badge badge-warning'>Inactive</span>";
                }
                
                $data.= "<tr>
                    <td>".$i."</td>
                    <td>".$department->name."</td>
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
        //
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
            'name' => 'required',
        ]);
        $department = new Department;
        $department->name = $request->name;
        $department->activity = $request->activity; 
        $department->company_id = Auth::user()->company_id;
        $department->created_by = Auth::user()->id;
        $department->last_update_by = Auth::user()->id;
        $department->save();

        return response()->json([
            'success' => true,
            'message' => 'Department Created Successfully'
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
        $departments = Department::where('company_id', Auth::user()->company_id)->where('id', $id)->get();
        return $department;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);
        $department = Department::find($id);
        $department->name = $request->name;
        $department->activity = $request->activity;  
        $department->last_update_by = Auth::user()->id;
        $department->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Department Updated Successfully'
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