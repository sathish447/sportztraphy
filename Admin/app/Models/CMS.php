<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Model;

class CMS extends Eloquent
{
	protected $connection = 'mongodb';
    protected $table = 'manage_cms';
}
