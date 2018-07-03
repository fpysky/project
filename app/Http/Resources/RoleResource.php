<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RoleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at?date('Y-m-d H-i-s',$this->created_at):'',
            'updated_at' => $this->updated_at?date('Y-m-d H-i-s',$this->updated_at):'',
        ];
    }
}
