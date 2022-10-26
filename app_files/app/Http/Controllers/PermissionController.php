<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Node;
use Auth;
use App\Http\Controllers\PermissionController;

class PermissionController extends Controller
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
    public function store(Request $request)
    {
        $profile_id = $request->profile_id;
        
        Permission::where('profile_id', $profile_id)->delete(); 
        foreach ($request['nodes'] as $node_key => $node_val) {
            $seve_node_arr = [
                'route_id' => $node_key,
                'profile_id' => $profile_id, 
                'created_by' => Auth::user()->id
            ];
            if (array_key_exists('0', $node_val)) {
                $seve_node_arr += ['view' => $node_val[0]];
            }
            if (array_key_exists('1', $node_val)) {
                $seve_node_arr += ['store' => $node_val[1]];
            }
            if (array_key_exists('2', $node_val)) {
                $seve_node_arr += ['edit' => $node_val[2]];
            } 
            Permission::create($seve_node_arr);
        }
        return response()->json([
            'success' => true,
            'message' => 'Profile Updated Successfully'
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
        
    }


    public static function getNodePermissionByProfile($node_name)
    {
        $node = Node::where('route_name', $node_name)->first();
        $node_id = $node->id;
        $permission = Permission::where('profile_id', Auth::user()->profile_id)->where('route_id', $node_id)->with('route')->first();
        return $permission;
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
        //
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