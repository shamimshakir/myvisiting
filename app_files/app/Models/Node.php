<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;

    protected $table = 'routes';
    
    public function permissions(){
        return $this->hasMany(Permission::class);
    }
}