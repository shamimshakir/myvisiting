<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $users = User::where('company_id', Auth::user()->company_id)->get();
        return view('users/users', compact('users'));
    }


        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHtmlData()
    {
        $users = User::select("*")
            ->where('company_id', Auth::user()->company_id)
            ->where(function (Builder $query) {
                return $query->where('id', Auth::user()->id)
                             ->orWhere('created_by', Auth::user()->id);
            })
            ->with("designation")
            ->get(); 
        $perm = PermissionController::getNodePermissionByProfile('users');
		$data = '';
        $i = 1;
        if(count($users)){
            foreach ($users as $user) {
                $activity = '';
                if($user->activity == "active"){
                    $activity = "<span class='badge badge-success'>Active</span>";
                }else{
                    $activity = "<span class='badge badge-secondary'>Inactive</span>";
                }
                
                $editbtn = "";
                if($perm->edit == $perm->route->id){
                    $editbtn = "<button type='button' data-id=".$user->id." id='edit-user-button' class='btn btn-sm btn-info mr-2'>
                        <i class='fas fa-edit'></i> Edit
                    </button>";
                }
                
                $image = "/app_files/public/images/users/".$user['photo'];
                $des = $user->designation ? $user->designation->name : '';
                $data.= "<tr>
                        <td>".$i."</td>
                        <td><img class='tdimg rounded img-thumbnail' src=".$image." /></td>
                        <td><strong>".$user->name."</strong></td>
                         <td>".$user->profile->name."</td>
                        <td>".$user->email."</td>
                        <td>".$user->phone."</td>
                        <td>".$des."</td> 
                        <td>".$user->emp_status."</td>
                        <td>".$activity."</td>
                        <td class='text-center'>
                            ".$editbtn."
                        </td>
                    </tr>";
                $i++;
            }
        }else{
            $data.="<td colspan='8'><p class='notfound'>Item not found!</p></td>";
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
            'name' => 'required|max:50',
            'phone' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'profile_id' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try {
            $imageName = "";
            if($request->photo){
                $imageName = time().'.'.$request->photo->extension();  
                $request->photo->move(public_path('images/users/'), $imageName);
            }
            $user = new User;
            $user->name                 = $request->name;
            $user->phone                = $request->phone;
            $user->email                = $request->email; 
            $user->password             = Hash::make($request->password);
            $user->department_id        = $request->department_id;
            $user->designation_id       = $request->designation_id;
            $user->emp_status           = $request->emp_status;
            $user->country_id           = $request->country_id;
            $user->city_id              = $request->city_id;
            $user->thana_id             = $request->thana_id;
            $user->activity             = $request->activity;
            $user->company_id           = Auth::user()->company_id;
            $user->created_by           = Auth::user()->id;
            $user->last_update_by       = Auth::user()->id;
            $user->address              = $request->address;
            $user->profile_id           = $request->profile_id;
            $user->photo                = $imageName;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'User Created Successfully'
            ], 201);
        }catch(Exception $exception) {
            return response()->json([
                'error' => true,
                'message' => $exception->getMessage()
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $profile = User::where('company_id', Auth::user()->company_id)->where('id', $id)->first();
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
            'name' => 'required|max:50',
            'phone' => 'required',
            'email' => 'required|email', 
            'profile_id' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $user = User::find($id);
            $user->name                 = $request->name;
            $user->phone                = $request->phone;
            $user->email                = $request->email; 
            $user->department_id        = $request->department_id;
            $user->designation_id       = $request->designation_id;
            $user->emp_status           = $request->emp_status;
            $user->country_id           = $request->country_id;
            $user->city_id              = $request->city_id;
            $user->thana_id             = $request->thana_id;
            $user->activity           = $request->activity;
            $user->address              = $request->address;
            $user->profile_id           = $request->profile_id;
            $user->last_update_by =     Auth::user()->id;

            if($request->photo){
                $imageName = "";
                $imageName = time().'.'.$request->photo->extension();  
                $request->photo->move(public_path('images/users/'), $imageName); 
                if ($user->photo) {
                    unlink("/app_files/public/images/users/".$user->photo);
                }
                $user->photo                = $imageName;
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'User Updated Successfully'
            ], 201);
        }catch(Exception $exception) {
            return response()->json([
                'error' => true,
                'message' => $exception->getMessage()
            ], 404);
        }
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

    public function loginPage(){
        return view('auth/login');
    }


    public function loginCheck(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required|min:3"
        ]);
 
        if ($validator->fails()) {
            return redirect('login')->withErrors($validator)->withInput(); 
        }
        
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return redirect('/');
            }
            return redirect()->route('loginpage')->with('error','Failed! invalid credentials!');
        }
        catch(Exception $e) { 
            return redirect()->route('loginpage')->with('error', $e->getMessage());
        }
    }
    
    public function logout() 
    {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }

    public function changePassword(){ 
        return view('users/myprofile');
    }

    public function updateMyPassword(Request $request){ 
        
        $request->validate([
            'current_pass' => 'required',
            'new_pass_1' => 'required',
            'new_pass_2' => 'required',
        ]);
        
            
        if($request->new_pass_1 == $request->new_pass_2){
        
            #Match The Old Password
            if(!Hash::check($request->current_pass, auth()->user()->password)){
                return response()->json([
                    'error' => true,
                    'message' => "Old Password Doesn't match!"
                ]);
            }
    
            #Update the new Password
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_pass_2)
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Password Changed Successfully'
            ]);
            
        }
    }
    
    
    public function globalSetup(){
    
    }
    
}