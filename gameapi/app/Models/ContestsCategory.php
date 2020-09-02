<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ContestsCategory extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'contests_categories';

    public function contest() {
        return $this->hasMany('App\Models\Contest','_id');

        // return $this->hasMany('App\Models\Contest','cat_id','_id');
    }
}
