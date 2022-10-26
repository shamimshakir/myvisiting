<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;
use Auth;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $designations = Designation::where('company_id', Auth::user()->company_id)->get();
        return view('designations/designations', compact('designations'));
    }


        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHtmlData()
    { 
        $designations = Designation::where('company_id', Auth::user()->company_id)->get();
        $perm = PermissionController::getNodePermissionByProfile('designations');
		$data = '';
        $i = 1;
        if(count($designations)){
            foreach ($designations as $designation) {
                $editbtn = "";
                if($perm->edit == $perm->route->id){
                    $editbtn = "<button type='button' data-id=".$designation->id." id='edit-designation-button' class='btn btn-sm btn-info mr-2'>
                    <i class='fas fa-edit'></i> Edit
                    </button>";
                }
                $sts = "";
                if($designation->activity == 'active'){
                    $sts = "<span class='badge badge-success'>Active</span>";
                }else{
                    $sts = "<span class='badge badge-warning'>Inactive</span>";
                }
                $data.= "<tr>
                    <td>".$i."</td>
                    <td>".$designation->name."</td>
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
        $designation = new Designation;
        $designation->name = $request->name;
        $designation->activity = $request->activity;
        $designation->company_id =  Auth::user()->company_id;
        $designation->created_by = Auth::user()->id;
        $designation->last_update_by = Auth::user()->id;
        $designation->save();

        return response()->json([
            'success' => true,
            'message' => 'Designation Created Successfully'
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
        $designations = Designation::where('company_id', Auth::user()->company_id)->where('id', $id)->get();
        return $designation;
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
        $designation = Designation::find($id);
        $designation->name = $request->name;
        $designation->activity = $request->activity; 
        $designation->last_update_by = Auth::user()->id;
        $designation->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Designation Updated Successfully'
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