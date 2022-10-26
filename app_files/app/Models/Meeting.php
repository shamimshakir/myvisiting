<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    public function visitor(){
        return $this->belongsTo(Visitor::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function meeting_type(){
        return $this->belongsTo(MeetingType::class, 'meeting_type_id');
    }
}