<?php

namespace App\Models;

use App\Models\Kyc;
use App\Models\UserBtcAddress;
use App\Models\UserEthAddress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Eloquent implements Authenticatable
{
    protected $connection = 'mongodb';
    protected $table = 'users';

    use AuthenticableTrait;

    public static function dashboard()
    {
        $totalusers = User::all()->count();
        $todayusers = User::where('created_at', '>=', Carbon::today())->count();
        $deactivate_users = User::where('status', 0)->orWhere('status', 2)->count();

        $details = array(
            'totalusers' => $totalusers,
            'todayusers' => $todayusers,
            'deactivate_users' => $deactivate_users,
        );

        return $details;
    }

    public static function monthweekdata()
    {
        $users = User::all();
        $monthdata = [];
        foreach ($users as $user):
            $created_month = date("m", strtotime($user->created_at->toDateTimeString()));
            if (isset($monthdata[$created_month])) {
                $monthdata[$created_month]++;
            } else {
                $monthdata[$created_month] = 1;
            }
        endforeach;
        return $monthdata;
    }

    public static function index()
    {
        $users = User::orderBy('_id', 'desc')->paginate(15);

        return $users;
    }

    public static function find($id)
    {
        $user = User::where('_id', '=', $id)->first();

        return $user;
    }

    public static function list_deactive_user()
    {
        $user = User::where("status", 0)->orWhere('status', 2)->paginate(15);

        return $user;
    }

    public static function list_today_user()
    {
        $user = User::where('created_at', '>=', Carbon::today())->paginate(15);

        return $user;
    }

    public static function kyc_request_user()
    {
        $user = Kyc::where('status', '1')->orderBy('_id', 'DESC')->paginate();
        return $user;
    }

    public static function userUpdate($request)
    {
        $fname = $request->fname;
        $country = $request->country;
        $phone = $request->phone;
        $twofactor = $request->twofactor;
        $address = $request->address;
        $user_id = $request->id;

        if ($twofactor == 'disable') {
            $update = User::where('_id', $user_id)->update(['google2fa_secret' => null, 'email2fa_otp' => 0, 'email2fa_secret' => null]);
        }

        $update = User::where('_id', $user_id)->update(['name' => $fname, 'phone_no' => $phone, 'country' => $country]);

        $user = User::where('_id', '=', $user_id)->first();
        $crypt_id = Crypt::encrypt($user_id);

        return $crypt_id;
    }

    public static function userWalletDetails($id)
    {
        $btcAddress = UserBtcAddress::where('user_id', $id)->first();
        $ethAddress = UserEthAddress::where('user_id', $id)->first();
        $ltcAddress = UserLtcAddress::where('user_id', $id)->first();

        if ($btcAddress) {
            $btcAddress = $btcAddress->address;
        } else {
            $btcAddress = 'No Address Found';
        }

        if ($ethAddress) {
            $ethAddress = $ethAddress->address;
        } else {
            $ethAddress = 'No Address Found';
        }

        if ($ltcAddress) {
            $ltcAddress = $ltcAddress->address;
        } else {
            $ltcAddress = 'No Address Found';
        }

        $details = array(
            'BTC' => $btcAddress,
            'ETH' => $ethAddress,
            'LTC' => $ltcAddress,
        );

        return $details;

    }

    public static function searchList($request)
    {
        // $users_data = User::orderBy('_id', 'desc')->get();
        //  $users = User::orderBy('_id', 'desc')->paginate(15);
        $q = $request->searchitem;

        $searchValues = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $users = User::where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->orWhere('teamname', 'like', "%{$value}%");
                $q->orWhere('email', 'like', "%{$value}%");
            }
        })->paginate(15);

        return $users;
    }

    public static function userStatusChange($request)
    {
        $stat = ($request->status == 'disable') ? 0 : 1;
        $updateStatus = User::where('_id', $request->user)->first();
        $updateStatus->status = $stat;

        $stat = ($request->status == 'disable') ? 'Deactivated' : 'Activated';
        if ($updateStatus->save()) {
            $msg = "<div class='alert alert-success'> User was successfully " . $stat . "</div>";
        } else {
            $msg = "<div class='alert alert-danger'> User status was not updated !</div>";
        }

        return $msg;
    }

    public static function excelExport()
    {
        $instance = new User();
        $wallet_coin = array();
        $items = User::select('_id', 'name', 'email', 'phone')->with('WalletDetails')->get();

        if (count($items) > 0) {
            foreach ($items as $key => $value) {
                $data[$key]['id'] = $value->_id;
                $data[$key]['name'] = $value->name;
                $data[$key]['email'] = $value->email;
                $data[$key]['phone'] = $value->phone;

                $commission = Commission::get();
                foreach ($commission as $comm_key => $comm_value) {

                    $data[$key]['Deposit_' . $comm_value->source] = '0.00000000';
                    $data[$key]['Trade_' . $comm_value->source] = '0.00000000';
                    $data[$key]['Total_' . $comm_value->source] = '0.00000000';
                    $wallet_coin[] = $comm_value->source;
                }

                foreach ($value->WalletDetails as $user_wallet_key => $user_wallet_value) {

                    if (in_array($user_wallet_value->currency, $wallet_coin)) {
                        $call = $user_wallet_value->currency . 'DeopistAmount';
                        $data[$key]['Deposit_' . $user_wallet_value->currency]
                        = $instance->$call($value->id);
                        $data[$key]['Trade_' . $user_wallet_value->currency] = '0.0000000';
                        $data[$key]['Total_' . $user_wallet_value->currency] = '0.0000000';
                    } else {
                        $data[$key]['Deposit_' . $user_wallet_value->currency] = '0.0000000';
                        $data[$key]['Trade_' . $user_wallet_value->currency] = '0.0000000';
                        $data[$key]['Total_' . $user_wallet_value->currency] = '0.0000000';
                    }
                }
            }
        } else {
            $data[0] = 'No Records Found';
        }

        return $data;
    }

    public static function tradeAmount($uid)
    {

    }

    public static function getIndividualUser($uid)
    {
        $instance = new User();

        $user = User::where('_id', $uid)->with('transactions')->first();
        $userwithdraw = Transaction::where('uid', $uid)->where('type', 'withdraw')->get();
        $userdeposit = Transaction::where('uid', $uid)->where('type', 'deposit')->get();

        //$users['commission'] =$instance->commission_wallet();
        $users['id'] = $user->_id;
        $users['name'] = $user->name;
        $users['email'] = $user->email;
        $users['mobile_number'] = $user->phone;
        $users['dob'] = '';

        if ($user->bank != '') {

            $users['bank_name'] = $user->bank['bank_name'];
            $users['account_no'] = $user->bank['account_number'];

            $users['bank_branch'] = $user->bank['branch'];
            $users['bank_ifsc'] = $user->bank['ifsc'];
            $users['bank_proof'] = $user->bank['bank_proof'];
            $users['USD_address'] = "<p><span>Account Number : " . $user->bank['account_number'] . "</br></span><span>Bank Name : " . $user->bank['bank_name'] . "</br></span><span>Bank Branch : " . $user->bank['branch'] . "</br></span><span>Bank IFSC : " . $user->bank['ifsc'] . "</br></span></p>";

        } else {
            $users['bank_name'] = '';
            $users['account_no'] = '';
            $users['bank_branch'] = '';
            $users['bank_ifsc'] = '';
            $users['bank_proof'] = '';

            $users['USD_address'] = "<p> No Bank Details </p>";
        }
        $users['deposittransaction'] = $userdeposit;
        $users['transaction'] = $userwithdraw;
        return $users;

    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', '_id', 'uid');
    }

}