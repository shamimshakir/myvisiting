<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Designation; 
use App\Models\Profile;
use App\Models\Country;
use App\Models\City;
use App\Models\Node;
use App\Models\Thana;
use App\Models\User;
use App\Models\Visitor;  
use App\Models\VisitorType;  
use App\Models\MeetingType;  
use App\Models\Meeting;  
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    { 
        if(Auth::check()){
            $user_id = Auth::user()->id;

            $todaysMeetingArr = Meeting::where('user_id', $user_id)
            ->whereDate('meeting_date', '2022-10-03')->get();

            $pendingMeetingArr = Meeting::where('user_id', $user_id)
            ->whereDate('meeting_date', '2022-10-03')
            ->where('meeting_status', 'new')->get();

            $todaysMeeting = count($todaysMeetingArr); 
            $todaysPendingMeeting = count($pendingMeetingArr); 
            
            $data = [
                'todays_meetings' => $todaysMeeting, 
                'todays_pending_meeting' => $todaysPendingMeeting
            ];
            
            return view('index', $data);
        }
    }

    public function getDepartments()
    {
        return Department::where('company_id', Auth::user()->company_id)->get();
    }

    public function getVisitorTypes()
    {
        return VisitorType::where('company_id', Auth::user()->company_id)->get();
    }

    public function getMeetingTypes()
    {
        return MeetingType::where('company_id', Auth::user()->company_id)->get();
    }
    
    public function getVisitors()
    {
        return Visitor::where('company_id', Auth::user()->company_id)->get();
    }

    public function getDesignations()
    {
        return Designation::where('company_id', Auth::user()->company_id)->get();
    }
    
    public function logUserLevel()
    {
        return Profile::find(Auth::user()->profile_id)->level_sl;
    }

    public function getProfiles()
    { 
        $auth_level = Profile::find(Auth::user()->profile_id)->level_sl;
        return Profile::where('company_id', Auth::user()->company_id)->where('level_sl', '>=', $auth_level)->get();  
    }

    public function getNodes()
    { 
        return Node::all();
    }

    public function getCountries()
    {
        return Country::all();
    }

    public function getCities()
    {
        return City::all();
    }

    public function getThanas()
    {
        return Thana::all();
    }

    public function getCitiesById($id)
    {
        return Country::find($id)->cities;
    }
    
    public function getThanasById($id)
    { 
        return City::find($id)->thanas;
    }
}