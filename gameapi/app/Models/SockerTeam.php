<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class SockerTeam extends Eloquent
{
    protected $connection = 'mongodb'; 
    protected $collection = 'socker_teams';
}
 