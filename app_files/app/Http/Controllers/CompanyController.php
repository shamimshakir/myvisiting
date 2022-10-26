<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company; 
use Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function companyGetbyId()
    {  
        $company = Company::where('id', Auth::user()->company_id)->first();
        return $company;
    }

    /** 
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    { 
        $company = Company::where('id', Auth::user()->company_id)->first();
        return view('companies/company', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required',
            'comapny_address' => 'required',
            'phone' => 'required',
            'email' => 'required',  
        ]);

        try {
            $company = Company::find(1);
            $company->company_name = $request->input('company_name');
            $company->comapny_address = $request->input('comapny_address');
            $company->phone = $request->input('phone');
            $company->email = $request->input('email');
            $company->web_address = $request->input('web_address');
            $company->v_code = $request->input('v_code');
            $company->sode_sl = $request->input('sode_sl');
            
            if($request->logo){
                $imageName = "";
                $imageName = time().'.'.$request->logo->extension();  
                $request->logo->move(public_path('images/company/'), $imageName); 
                if ($company->logo) {
                    unlink("/app_files/public/images/company/".$company->logo);
                }
                $company->logo = $imageName;
            }
            
            $company->update();
            
            return redirect()->route('company_by_id')->with('success','Company updated successfully.');
        }catch(Exception $exception) {
            return redirect()->route('company_by_id')->with('error', $exception->getMessage());
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