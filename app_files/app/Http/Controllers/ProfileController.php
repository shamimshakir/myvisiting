<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::where('company_id', Auth::user()->company_id)->get();
        return view('profiles/profiles', compact('profiles'));
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHtmlData()
    {  
        $auth_level = Profile::find(Auth::user()->profile_id)->level_sl;
        $profiles = Profile::where('company_id', Auth::user()->company_id)->where('level_sl', '>=', $auth_level)->get(); 
        $perm = PermissionController::getNodePermissionByProfile('profiles');
		$data = '';
        $i = 1;
        if(count($profiles)){
            foreach ($profiles as $profile) {
                $editbtn = "";
                if($perm->edit == $perm->route->id){
                    $editbtn = "<button type='button' data-id=".$profile->id." id='edit-profile-button' class='btn btn-sm btn-warning mr-2'>
                        <i class='fas fa-edit'></i> Edit
                    </button>";
                }
                $permBtn = "";
                if($perm->edit == $perm->route->id){
                    $permBtn = "<button type='button' data-id=".$profile->id." id='permission-button' class='btn btn-sm btn-info'>
                    <i class='fas fa-unlock-alt'></i> Permission</button>";
                }
                $sts = "";
                if($profile->activity == 'active'){
                    $sts = "<span class='badge badge-success'>Active</span>";
                }else{
                    $sts = "<span class='badge badge-warning'>Inactive</span>";
                }
                $data.= "<tr>
                    <td>".$i."</td>
                    <td>".$profile->level_sl."</td>
                    <td>".$profile->name."</td> 
                    <td>".$sts."</td>
                    <td class='text-center'>
                        ".$editbtn."
                        ".$permBtn."
                    </td>
                </tr>";
                $i++;
            }
        }else{
            $data.="<td colspan='5'><p class='notfound'>Item not found!</p></td>";
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
        $auth_level = Profile::find(Auth::user()->profile_id)->level_sl;
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'level_sl' => "required|numeric|min:$auth_level",
        ]); 
        $profile = new Profile;
        $profile->name = $request->name;
        $profile->activity = $request->activity;
        $profile->level_sl = $request->level_sl;
        $profile->company_id = Auth::user()->company_id;
        $profile->created_by = Auth::user()->id;
        $profile->last_update_by = Auth::user()->id;
        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile Created Successfully'
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
        $profile = Profile::where('company_id', Auth::user()->company_id)->where('id', $id)->with('permissions')->first();
        // $profile = Profile::where('company_id', Auth::user()->company_id)->where('id', $id)->with( array( 'permissions', 'permissions.route' ) )->first();
        return $profile;
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
        $auth_level = Profile::find(Auth::user()->profile_id)->level_sl;
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'level_sl' => "required|numeric|min:$auth_level",
        ]); 
        $profile = Profile::find($id); 
        $profile->name = $request->name;
        $profile->level_sl = $request->level_sl;
        $profile->activity = $request->activity;  
        $profile->last_update_by = Auth::user()->id;
        $profile->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Profile Updated Successfully'
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