<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Database\Eloquent\Model;

class ContestsCategory extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'contests_categories';

    public function contest()
    {
        return $this->hasMany('App\Models\Contest','cat_id','_id'); 
    }
    public static function searchList($request)
    {
    	// dd($request->all());
        // $users_data = User::orderBy('_id', 'desc')->get();
        //  $users = User::orderBy('_id', 'desc')->paginate(15);
        $q = $request->searchitem;

        $searchValues = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $users = ContestsCategory::where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->orWhere('cat_name', 'like', "%{$value}%");
                $q->orWhere('description', 'like', "%{$value}%");
            }
        })->paginate(15);	
        return $users;
    }
}

