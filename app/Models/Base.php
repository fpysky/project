<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    //表明模型是否应该被打上时间戳
    public $timestamps = false;

    //设置表名
    public function getTable(){
        return $this->table ? $this->table : strtolower(snake_case(class_basename($this)));
    }
}
