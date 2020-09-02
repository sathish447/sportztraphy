<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FantasyTeam;
use App\Models\Player;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class MatchController extends Controller
{
    public function matchList($status)
    {
        // dd($status);
        $stat = '';
        if ($status != '' && $status == 'upcoming') {
            $stat = 'notstarted';
        }
        if ($status != '' && $status == 'live') {
            $stat = 'started';
        }
        if ($status != '' && $status == 'complete') {
            $stat = 'completed';
        }

        if ($stat == '') {
            $stat = 'upcoming';
        }
        $list = Schedule::where('status', $stat)->orderBy('_id', 'desc')->paginate(20);
        // dd($list);
        return view('match.matchList', ['list' => $list, 'status' => $status]);
    }

    public function fantasyteam()
    {
        // dd('hai');
        $list = FantasyTeam::orderBy('_id', 'desc')->with('match')->with('contestinfo')->paginate();
        $lists = FantasyTeam::orderBy('_id', 'desc')->with('match')->with('contestinfo')->get();
        // dd($lists);
        return view('match.fantasyteams', ['list' => $list, 'lists' => $lists]);
    }

    public function matchStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if ($id != '' && $status != '') {
            $stat = ($status == 'disable') ? 0 : 1;
            $Schedule = Schedule::find($id);

            $Schedule->view_status = $stat;
            if ($Schedule->save()) {
                echo "<div class='alert alert-success'>Match was successfully " . $status . "d</div>";
            } else {
                echo "<div class='alert alert-danger'>Match status was not updated !</div>";
            }

            exit;
        }
    }
    public function playerList()
    {
        $list = Player::paginate(20);
        // dd($list);
        $counts = Player::count();
        return view('match.playerList', ['list' => $list, 'counts' => $counts]);
    }

    public function matchEdit($id)
    {
        if ($id != '') {
            $match = Schedule::where('_id', $id)->first();
            return view('match.matchEdit', ['match' => $match, 'page' => 'edit']);
        } else {
            return redirect('admin/match/upcoming');
        }
    }

    public function matchUpdate($id, Request $request)
    {
        if ($id != '' && $request->status != '') {
            $getmatch = Schedule::where('_id', $id)->first();
            if ($getmatch != '') {
                $getmatch->status = $request->status;
                if ($getmatch->save()) {
                    return redirect()->back()->with('machsuccess', 'Match was successfully updated');
                } else {
                    return redirect()->back()->with('matchfail', 'Match was successfully updated');
                }

            }
        } else {
            return redirect('admin/match/upcoming');
        }
    }

    public function playerEdit($id)
    {
        if ($id != '') {
            $match = Player::where('_id', $id)->first();
            return view('match.playerEdit', ['match' => $match, 'page' => 'edit']);
        } else {
            return redirect('admin/playerList');
        }
    }

    public function playerUpdate($id, Request $request)
    {
        if ($id != '') {

            if ($request->name != '' && $request->batting_style != '' && $request->bowling_style != '' && $request->role != '') {
                $player = Player::where('_id', $id)->first();

                if ($player != '') {

                    $rules = [
                        'profile' => 'required|mimes:jpeg,jpg,png|max:20480',
                    ];

                    $messages = [
                        'profile.required' => 'Upload Document (Back) is required.',
                    ];

                    $validator = \Validator::make($request->all(), $rules, $messages);
                    if ($validator->fails()) {
                        return redirect()->back()->with('playerfail', $Validator);
                    }

                    if ($this->imgvalidaion($_FILES['profile']['tmp_name']) == 1) {
                        $front = Input::File('profile');

                        $dir = 'profile/';
                        $path = 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $dir;
                        $location = 'public' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $dir;
                        $filenamewithextension = $front->getClientOriginalName();
                        $photnam = str_replace('.', '', microtime(true));
                        $filename = pathinfo($photnam, PATHINFO_FILENAME);
                        $extension = $front->getClientOriginalExtension();
                        $photo = $filename . '.' . $extension;
                        $front->move($path, $photo);

                        $prof_img = $path . $photo;
                        $player->profile = url($prof_img);
                        $player->name = $request->name;
                        $player->batting_style = $request->batting_style;
                        $player->bowling_style = $request->bowling_style;
                        $player->role = $request->role;

                        if ($player->save()) {
                            return redirect()->back()->with('playersuccess', 'Player was successfully updated');
                        } else {
                            return redirect()->back()->with('playerfail', 'Player was not updated');
                        }

                    } else {
                        return redirect()->back()->with('playerfail', 'Image not uploaded!');
                    }
                }
            } else {
                return redirect()->back()->with('playerfail', 'Please select all fields!');
            }
        } else {
            return redirect('admin/playerList');
        }
    }

    public function UploadProfileValidator(array $data)
    {
        $validator = Validator::make($data, [
            'profile' => 'required|mimes:jpeg,jpg,png|max:8192',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 200);
        }
    }

    public function imgvalidaion($img)
    {
        $myfile = fopen($img, "r") or die("Unable to open file!");
        $value = fread($myfile, filesize($img));
        if (strpos($value, "<?php") !== false) {
            $img = 0;
        } elseif (strpos($value, "<?=") !== false) {
            $img = 0;
        } elseif (strpos($value, "eval") !== false) {
            $img = 0;
        } elseif (strpos($value, "<script") !== false) {
            $img = 0;
        } else {
            $img = 1;
        }
        fclose($myfile);
        return $img;
    }

    public function getFantasyPlayers(Request $request)
    {
        $cat = FantasyTeam::where('_id', $request->id)->first();
        return json_encode($cat);
    }

    public function playerSearchList(Request $request)
    {
        // dd($request->all());
        $userSearchList = Player::searchList($request);
        // dd($userSearchList);
        $counts = Player::count();
        return view('match.playerList')->with(['list' => $userSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }
    public function matchSearchList(Request $request, $status)
    {
        // dd($status);
        $stat = '';
        if ($status != '' && $status == 'upcoming') {
            $stat = 'notstarted';
        }
        if ($status != '' && $status == 'live') {
            $stat = 'started';
        }
        if ($status != '' && $status == 'complete') {
            $stat = 'completed';
        }

        if ($stat == '') {
            $stat = 'upcoming';
        }
        // dd($request->all());
        $matchSearchList = Schedule::searchList($request);
        // dd($userSearchList);
        $counts = Schedule::count();
        return view('match.matchList')->with(['list' => $matchSearchList, 'counts' => $counts, 'term' => $request->searchitem, 'status' => $status]);
    }
    public function teamSearchList(Request $request)
    {
        // dd($request->all());
        $teamSearchList = FantasyTeam::searchList($request);
        $counts = FantasyTeam::count();
        return view('match.fantasyteams')->with(['list' => $teamSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }

}