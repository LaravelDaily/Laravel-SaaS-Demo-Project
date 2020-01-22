<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'price', 'stripe_plan_id'];

    public function features()
    {
        return $this->belongsToMany(Feature::class)->withPivot(['max_amount']);
    }

}
