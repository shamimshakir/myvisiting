<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    public function visitor_type(){
        return $this->belongsTo(VisitorType::class, 'visitor_type_id');
    }
}