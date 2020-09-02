<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bank';

    public function bankdetails()
    {
      return $this->belongsTo('App\Modals\User', 'user_id');
    }
}
