<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Supportticket extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'support_ticket';

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

}
