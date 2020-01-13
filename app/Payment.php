<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'stripe_id', 'subtotal', 'tax', 'total'];
}
