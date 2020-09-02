<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Deposit;
use App\Models\FantasyTeam;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBtcTransaction;
use App\Models\UserEthTransaction;
use App\Models\UserLtcTransaction;
use App\Models\Withdraw;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PDF;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $details = User::index();
        $counts = User::count();
        return view('user.users')->with(['details' => $details, 'counts' => $counts]);
    }

    public function edit(Request $request)
    {
        $user_id = Crypt::decrypt($request->id);
        // $wallet = User::userWalletDetails($user_id);

        if ($user_id) {
            $user = User::find($user_id);
            if ($user->invitecode != '') {
                $wherekyc = array('referral' => $user->invitecode);
                $userRef = User::where($wherekyc)->get();
            } else {
                $userRef = [];
            }
            $deposit_history = Transaction::where('uid', $user_id)->where('type', 'deposit')->get();
            $withdraw_history = Transaction::where('uid', $user_id)->where('type', 'withdraw')->get();
            $contests_joined = FantasyTeam::where('user_id', $user_id)->with('match')->with('contestinfo')->get();

            return view('user.user_edit',
                ['userdetails' => $user,
                    'wallet' => $user->wallet,
                    'userReferral' => $userRef,
                    'withdraw_history' => $withdraw_history,
                    'deposit_history' => $deposit_history,
                    'contests_joined' => $contests_joined,
                ]);
        }
    }

    public function update(Request $request)
    {
        $user = User::userUpdate($request);

        if ($user) {
            \Session::flash('updated_status', 'Profile Details Updated Successfully.');
        } else {
            \Session::flash('updated_status', 'Profile Details Updated Failed.');
        }

        return redirect()->back();
    }

    public function userSearchList(Request $request)
    {
        // dd($request->all());
        $userSearchList = User::searchList($request);
        // dd($userSearchList);
        $counts = User::count();
        return view('user.users')->with(['details' => $userSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }

    public function userStatus(Request $request)
    {
        $userSearchList = User::userStatusChange($request);

        return $userSearchList;
    }

    public function excel_view(Request $request)
    {
        $user_id = Crypt::decrypt($request->id);
        $user_details = User::getIndividualUser($user_id);

        if ($user_id) {
            return view('user.userexcelview')->with('user', $user_details);
        }
    }

    public function exportExcel()
    {
        $sitename = Admin::siteName();

        Excel::create($sitename . '_User_details', function ($excel) {
            $excel->sheet('Sheetname', function ($sheet) {
                // first row styling and writing content
                $sheet->mergeCells('A1:K1');
                $sheet->mergeCells('E2:G2');
                $sheet->mergeCells('H2:J2');
                $sheet->mergeCells('K2:M2');
                $sheet->mergeCells('N2:P2');

                $sheet->setCellValue('H2', '=SUM(F2:G2)');
                $sheet->setCellValue('K2', '=SUM(I2:J2)');
                $sheet->setCellValue('N2', '=SUM(L2:M2)');

                // $sheet->setMergeColumn(array(
                //     'columns' => array('A','B','C','D'),
                //     'rows' => array(
                //         array(2,3),
                //         array(5,11),
                //         )
                //     ));

                $sheet->cell('E2', function ($cell) {
                    // manipulate the cell
                    $cell->setValue('BTC Wallets');
                    $cell->setFontWeight('bold');

                });

                $sheet->cell('H2', function ($cell) {
                    // manipulate the cell
                    $cell->setValue('ETH Wallets');
                    $cell->setFontWeight('bold');

                });

                $sheet->cell('K2', function ($cell) {
                    // manipulate the cell
                    $cell->setValue('LTC Wallets');
                    $cell->setFontWeight('bold');
                });

                $sheet->cell('N2', function ($cell) {
                    // manipulate the cell
                    $cell->setValue('USD Wallets');
                    $cell->setFontWeight('bold');
                });

                $sheet->row(1, function ($row) {
                    // $row->setFontFamily('Comic Sans MS');
                    // $row->setFontSize(30);
                });

                $sitename = Admin::siteName();

                $sheet->row(1, array($sitename));
                // second row styling and writing content
                // $sheet->row(2, function ($row) {
                //    // call cell manipulation methods
                //     // $row->setFontFamily('Comic Sans MS');
                //     // $row->setFontSize(15);
                //     // $row->setFontWeight('bold');
                // });
                // $sheet->row(2, array('Something else here'));
                // getting data to display - in my case only one record
                $users = User::excelExport();

                // setting column names for data - you can of course set it manually
                $sheet->appendRow(array_keys($users[0])); // column names

                // getting last row number (the one we already filled and setting it to bold
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                });
                // putting users data as next rows
                foreach ($users as $user) {

                    $sheet->appendRow($user);
                }
            });
        })->export('xls');
        return Excel::download(new User, 'user' . date('dMY') . '.xlsx');
    }

    public function exportIndividualUserXls(Request $request)
    {
        $user_id = $request->segment(4);
        $type = $request->segment(5);
        $users = User::getIndividualUser($user_id);

        if ($type == 'pdf') {
            $pdf = PDF::loadView('user.test', array('user' => $users));
            return $pdf->download('user.pdf');
        } else {
            Excel::create('user', function ($excel) use ($users) {
                $excel->sheet('sheet1', function ($sheet) use ($users) {
                    $sheet->loadView('user.convertexcel')->with('user', $users);
                });
            })->download($type);
        }
    }

    public function deactiveUser()
    {
        $details = User::list_deactive_user();
        return view('user.users')->with('details', $details);
    }

    public function todayUser()
    {
        $details = User::list_today_user();
        return view('user.users')->with('details', $details);
    }

    public function kyc_RequestUser()
    {
        $details = User::kyc_request_user();

        return view('user.userskyc')->with('details', $details);
    }

    public function user_fiatdeposit_edit($id)
    {
        $depositList = Deposit::edit(Crypt::decrypt($id));

        return view('user.user_fiat_deposit_edit')->with(['deposit' => $depositList]);
    }

    public function user_fiatdeposit_update(Request $request)
    {
        $depositUpdate = Deposit::statusUpdate($request);

        return back()->with('success', 'Deposit Updated Successfully');
    }

    public function fiat_withdraw_edit($id)
    {
        $crypto_trasnaction = Withdraw::edit(\Crypt::decrypt($id));

        // dd($crypto_trasnaction);

        return view('user.user_fiat_withdraw_edit', ['withdraw' => $crypto_trasnaction]);
    }

    public function fiat_withdraw_update(Request $request)
    {

        $crypto_trasnaction = Withdraw::withdrawUpdate($request);

        return back()->with('status', 'Withdraw Updated Successfully');
    }

    public function crypto_withdraw_edit($id, $coin)
    {

        if ($coin == 'BTC') {

            $crypto_trasnaction = UserBtcTransaction::withdrawEdit(\Crypt::decrypt($id));
            $coin = 'BTC';

        } elseif ($coin == 'ETH') {

            $crypto_trasnaction = UserEthTransaction::withdrawEdit(\Crypt::decrypt($id));
            $coin = 'ETH';

        } elseif ($coin == 'LTC') {

            $crypto_trasnaction = UserLtcTransaction::withdrawEdit(\Crypt::decrypt($id));
            $coin = 'LTC';
        }

        return view('user.user_crypto_withdraw_edit', ['withdraw' => $crypto_trasnaction, 'coin' => $coin]);
    }

    public function crypto_withdraw_update(Request $request)
    {
        $coin = $request->coin;
        $id = $request->id;
        $status = $request->status;

        if ($coin == 'BTC') {
            $crypto_trasnaction = UserBtcTransaction::withdrawUpdate($request);
        } elseif ($coin == 'ETH') {
            $crypto_trasnaction = UserEthTransaction::withdrawUpdate($request);
        } elseif ($coin == 'LTC') {
            $crypto_trasnaction = UserLtcTransaction::withdrawUpdate($request);
        }

        return back()->with('status', 'Withdraw Updated Successfully');
    }
  
}