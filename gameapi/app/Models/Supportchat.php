<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Supportchat extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'support_chat';

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

}
