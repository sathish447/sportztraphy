<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContestsCategory;
use Illuminate\Http\Request;

class ContestCategoryController extends Controller
{
    public function catList()
    {
        $cat = ContestsCategory::paginate();
        return view('contest.catlist', ['cat' => $cat]);
    }

    public function createCat()
    {
        return view('contest.add_cat');
    }

    public function catStatus(Request $request)
    {
        $id = $request->user;
        $status = $request->status;
        if ($id != '' && $status != '') {
            $Contest = ContestsCategory::find($id);
            $stat = ($status == 'disable') ? 0 : 1;
            $Contest->status = $stat;
            if ($Contest->save()) {
                echo "<div class='alert alert-success'> Contest Category was successfully " . $status . "d</div>";
            } else {
                echo "<div class='alert alert-danger'>Contest Category status was not updated !</div>";
            }

        }
        exit;
    }
    public function add_cat(Request $request)
    {
        // dd($request->all());
        $cat = new ContestsCategory;
        $cat->cat_name = $request->category;
        $cat->description = $request->description;
        $cat->save();
        \Session::flash('createcat', 'Contest category created successfully!');
                return redirect()->back();      
    }
    public function catSearchList(Request $request)
    {
        // dd($request->all());
        $userSearchList = ContestsCategory::searchList($request);
        // dd($userSearchList);
        $counts = ContestsCategory::count();
        return view('contest.catlist')->with(['cat' => $userSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }

}