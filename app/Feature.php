<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['name'];

    public function plans()
    {
        return $this->belongsToMany(Plan::class)->withPivot(['max_amount']);
    }
}
