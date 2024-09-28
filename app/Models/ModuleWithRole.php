<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleWithRole extends Model
{
    use HasFactory;


    public function roles() 
    {
        return $this->belongsToMany('App\Models\Role');
    }
}
