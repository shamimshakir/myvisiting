<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use Auth;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitors = Visitor::where('company_id', Auth::user()->company_id)->get();
        return view('visitors/visitors', compact('visitors'));
    }


        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHtmlData()
    { 
        $visitors = Visitor::where('company_id', Auth::user()->company_id)->with('visitor_type')->get();
        $perm = PermissionController::getNodePermissionByProfile('visitors');
		$data = '';
        $i = 1;
        if(count($visitors)){
            foreach ($visitors as $visitor) {
                $status = '';
                if($visitor->activity == "active"){
                    $status = "<span class='badge badge-success'>Active</span>";
                }else{
                    $status = "<span class='badge badge-warning'>Inactive</span>";
                }
                $image = "/app_files/public/images/visitors/".$visitor['photo']; 
                $editbtn = "";
                if($perm->edit == $perm->route->id){
                    $editbtn = "<button type='button' data-id=".$visitor->id." id='edit-visitor-button' class='btn btn-sm btn-info mr-2'>
                        <i class='fas fa-edit'></i> Edit
                    </button>";
                }
                $vis_name = "";
                if($visitor->visitor_type){
                    $vis_name = $visitor->visitor_type->name;
                }
                $data.= "<tr>
                        <td>".$i."</td>
                        <td><img class='tdimg rounded img-thumbnail' src=".$image." /></td>
                        <td><strong>".$visitor->name."</strong></td>
                        <td>".$visitor->email."</td>
                        <td>".$visitor->phone."</td>
                        <td>".$vis_name."</td> 
                        <td>".$status."</td>
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
            'visitor_type_id' => 'required',
            'activity' => 'required', 
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try {
            $imageName = "";
            if($request->photo){
                $imageName = time().'.'.$request->photo->extension();  
                $request->photo->move(public_path('images/visitors/'), $imageName);
            }
            $visitor = new visitor;
            $visitor->name                 = $request->name;
            $visitor->phone                = $request->phone;
            $visitor->email                = $request->email; 
            $visitor->visitor_type_id         = $request->visitor_type_id;
            $visitor->visitor_company              = $request->visitor_company;
            $visitor->company_id              = Auth::user()->company_id;
            $visitor->nid                  = $request->nid;
            $visitor->activity               = $request->activity; 
            $visitor->created_by               = Auth::user()->id; 
            $visitor->last_update_by               = Auth::user()->id; 
            $visitor->address              = $request->address; 
            $visitor->photo                = $imageName;
            $visitor->save();
            return response()->json([
                'success' => true,
                'message' => 'Visitor Created Successfully'
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
        $visitor = Visitor::where('company_id', Auth::user()->company_id)->first();
        return $visitor;
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
            'visitor_type_id' => 'required',
            'activity' => 'required', 
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $visitor = Visitor::find($id);
            $visitor->name                 = $request->name;
            $visitor->phone                = $request->phone;
            $visitor->email                = $request->email; 
            $visitor->visitor_type_id      = $request->visitor_type_id;
            $visitor->visitor_company              = $request->visitor_company;
            $visitor->nid                  = $request->nid;
            $visitor->activity               = $request->activity; 
            $visitor->last_update_by               = Auth::user()->id; 
            $visitor->address              = $request->address; 
            if($request->photo){
                $imageName = "";
                $imageName = time().'.'.$request->photo->extension();  
                $request->photo->move(public_path('images/visitors/'), $imageName); 
                if ($visitor->photo) {
                    unlink("/app_files/public/images/visitors/".$visitor->photo);
                }
                $visitor->photo                = $imageName;
            }
            $visitor->save();
            return response()->json([
                'success' => true,
                'message' => 'Visitor Updated Successfully'
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
}