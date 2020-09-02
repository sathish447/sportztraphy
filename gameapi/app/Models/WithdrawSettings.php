<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class WithdrawSettings extends Eloquent
{   
    protected $connection = 'mongodb';
    protected $collection = 'withdraw_settings';
    
}
