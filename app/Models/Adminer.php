<?php
namespace App\Models;

class Adminer extends Base{
    public function adminHasRole(){
        return $this->hasMany('App\Models\AdminHasRole','adminer_id','id');
    }
}