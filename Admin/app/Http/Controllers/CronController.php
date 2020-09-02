<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminBtcAddress;
use App\Models\AdminBtcTransaction;
use App\Models\AdminEthAddress;
use App\Models\AdminEthTransaction;
use App\Models\AdminJadaxAddress;
use App\Models\User;
use App\Models\UserBtcAddress;
use App\Models\UserBtcTransaction;
use App\Models\UserEthAddress;
use App\Models\UserEthTransaction;
use App\Models\UserJadaxTransaction;
use App\Models\UserWallet;
use App\Traits\BtcClass;
use App\Traits\EthClass;
use App\Traits\LtcClass;
use Illuminate\Support\Facades\Crypt;

class CronController extends Controller
{
    use EthClass, BtcClass, LtcClass;

    public function adminBtcTransactions()
    {

        $admin_address = AdminBtcAddress::first();
        $address = $admin_address->address;
        $get_transaction = $this->getTransactions($address);

        if ($get_transaction && isset($get_transaction->txs) && count($get_transaction->txs) > 0) {
            foreach ($get_transaction->txs as $transaction) {
                $tx_hash = $transaction->txid;
                $sender = $transaction->vin[0]->addr;
                $confirm = $transaction->confirmations;
                $fees = $transaction->fees;
                $time = $transaction->time;
                foreach ($transaction->vin as $vin) {
                    if ($vin->addr === $address) {
                        break;
                    }
                }
                foreach ($transaction->vout as $vout) {
                    if (in_array($address, $vout->scriptPubKey->addresses)) {
                        $receiver = $address;
                        $total = $vout->value;
                        break;
                    }
                }

                $type = "send";

                if (isset($receiver) && $receiver == $address) {
                    $type = "received";
                }
                if (isset($receiver) && $receiver != $sender) {

                    $is_txn = AdminBtcTransaction::where('txid', $tx_hash)->count();

                    if ($is_txn == 0) {
                        $btctransaction = new AdminBtcTransaction;
                        $btctransaction->type = 'received';
                        $btctransaction->recipient = $receiver;
                        $btctransaction->sender = $sender;
                        $btctransaction->amount = $total;
                        $btctransaction->txid = $tx_hash;
                        $btctransaction->save();
                    }
                }
            }
        }
    }

    public function adminEthTransactions()
    {
        $admin_address = AdminEthAddress::first();
        $address = $admin_address->address;
        $balance = $this->getEthTransaction($address);

        if ($balance) {
            $count = count($balance['result']);
            if ($count > 0) {
                $result_data = $balance['result'];
                for ($i = 0; $i < $count; $i++) {
                    $data = $result_data[$i];
                    $tx_hash = $data['hash'];
                    $sender = $data['from'];
                    $receiver = $data['to'];
                    $total = $this->weitoeth($data['value']);
                    $confirmations = $this->weitoeth($data['confirmations']);

                    if ($address == $sender) {
                        $type = 'send';
                    } else {
                        $type = 'received';
                    }

                    $is_txn = AdminEthTransaction::where('txid', $tx_hash)->count();
                    if ($is_txn == 0) {
                        $his = new AdminEthTransaction;
                        $his->type = 'received';
                        $his->recipient = $receiver;
                        $his->sender = $sender;
                        $his->amount = $total;
                        $his->txid = $tx_hash;
                        $his->save();
                    }
                }
            }
        }
    }

    public function sendBtcToAdmin()
    {
        $btc = UserBtcTransaction::on('mysql2')->where('status', '1')->where('type', 'received')->get();

        foreach ($btc as $key => $value) {
            $this->btcCron($value->id);
        }

    }

    public function btcCron($id)
    {
        $user = UserBtcTransaction::on('mysql2')->where('status', '1')->where('type', 'received')->where('id', $id)->first();

        if (count($user) > 0) {
            $admin = AdminBtcAddress::first();
            $amount = $user->amount;
            $from_address = $user->recipient;
            $to_address = $admin->address;
            $fee = 0.00002;
            $final_amount = $amount - $fee;
            $user_details = UserBtcAddress::where('user_id', $user->user_id)->first();
            $credential = explode(',', $user_details->narcanru);
            $pvtk = Crypt::decryptString($credential[2]);
            $send_to_admin = $this->send($to_address, $final_amount, $from_address, $pvtk, $fee);
            if ($send_to_admin) {

                $update = UserWallet::on('mysql2')->where('user_id', $user->user_id)->where('currency', 'BTC')->first();
                $update->balance = $update->balance + $final_amount;
                $update->save();

                $user->status = 2;
                $user->save();
            } else {
                echo "Transaction Falied";
            }
        } else {
            echo 'No Records Found';
        }
    }

    public function sendEthToAdmin()
    {
        $eth = UserEthTransaction::on('mysql2')->where('status', '1')->where('type', 'received')->get();
        foreach ($eth as $key => $value) {
            $this->ethCron($value->id);
        }

    }

    public function ethCron($id)
    {
        $user = UserEthTransaction::on('mysql2')->where('type', 'received')->where('status', '1')->where('id', $id)->first();

        $admin = AdminEthAddress::first();

        $user_details = UserEthAddress::where('user_id', $user->user_id)->first();

        if (isset($user_details->address)) {
            $fee = 0.00042;
            $amount = $user->amount - $fee;
            if ($amount > 0.00052) {
                if ($user->user_id != 24) {
                    $from_address = $user->recipient;
                    $to_address = $admin->address;
                    $credential = explode(',', $user_details->narcanru);
                    $pvtk = Crypt::decryptString($credential[0]);

                    $send_to_admin = $this->ethSendTransaction($from_address, $to_address, $amount, $pvtk);
                    if (isset($send_to_admin)) {
                        $update = UserWallet::on('mysql2')->where('user_id', $user->user_id)->where('currency', 'ETH')->first();
                        $update->balance = $update->balance + $amount;
                        $update->save();

                        $user->status = 2;
                        $user->save();
                    } else {
                        print_r($send_to_admin);
                    }
                }
            }

        }
    }
    public function sendJadaxToAdmin()
    {
        $jadax = UserJadaxTransaction::on('mysql2')->where('status', '1')->where('type', 'received')->get();
        foreach ($jadax as $key => $value) {
            $this->jadaxCron($value->id);
        }
    }

    public function jadaxCron($id)
    {

        $move_amount = UserJadaxTransaction::on('mysql2')->where('id', $id)->where('status', '1')->first();

        if ($move_amount && $move_amount->count() > 0) {
            $user_id = $move_amount->user_id;

            $admin = AdminJadaxAddress::first();

            $user_details = UserEthAddress::where('user_id', $user_id)->first();

            if (isset($user_details->address)) {
                $get_user_address = $user_details->address;

                $find_real_amount = $this->ethBalance($get_user_address);

                if ($find_real_amount >= 0.001) {
                    if (count($user_details) > 0) {
                        $fee = 0.00080764155;
                        $amount = $move_amount->amount;
                        $total_send_amount = $amount;

                        $from_address = $get_user_address;
                        $credential = explode(',', $user_details->narcanru);
                        $pvtk = Crypt::decryptString($credential[0]);
                        $to_address = $admin->address;

                        if ($to_address != '' && $to_address != null) {

                            $contract = "0x94190e84A62c4e1fb4CDE76407a0dec59354395D";
                            $send_amount = $this->convert($total_send_amount);
                            $send = $this->jadaxSendTransaction($from_address, $to_address, $send_amount, $pvtk);

                            if (isset($send) || $send->txid != '') {
                                $update = UserWallet::on('mysql2')->where('user_id', $user_id)->where('currency', 'JADAX')->first();
                                $update->balance = $update->balance + $amount;
                                $update->save();

                                $move_amount->status = 2;
                                $move_amount->save();
                            } else {
                                print_r($send);
                            }
                        }
                    }

                } else {
                    $send_from_admin = $this->sendFromAdminUser($get_user_address);
                }

            }
        }
    }

    public function ethBalance($address)
    {
        $url = "https://api.etherscan.io/api?module=account&action=balance&address=" . $address;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (curl_errno($ch)) {
            $result = 'Error:' . curl_error($ch);
        } else {
            $result = curl_exec($ch);
        }
        curl_close($ch);
        $dd = json_decode($result);
        return $dd->result / 1000000000000000000;
    }

    public function convert($amount)
    {
        return number_format((1000000000000000000 * $amount), 8, '.', '');
    }

    public function sendFromAdminUser($toaddress)
    {
        $user_details = UserEthAddress::where('user_id', 24)->first();

        $fee = 0.00042;
        $amount = 0.00142 - $fee;
        $from_address = $user_details->address;
        $to_address = $toaddress;

        $credential = explode(',', $user_details->narcanru);
        $pvtk = Crypt::decryptString($credential[0]);
        $send_to_admin = $this->ethSendTransaction($from_address, $to_address, $amount, $pvtk);

        return $send_to_admin;
    }

    public function create_admin_address($value = '')
    {
        $btc_admin_address = $this->btc_admin_address_create();
        $ltc_admin_address = $this->ltc_admin_address_create();
        $eth_admin_address = $this->eth_admin_address_create();
        dd('ddd');
    }

    public function UserAddressCreation()
    {
        $users = User::where('kyc_id', null)->get();

        foreach ($users as $key => $value) {
            if (isset($value->id)) {
                $address = UserBtcAddress::where('user_id', $value->id)->first();

                if (count($address) == 0) {
                    $btcAddress = $this->create_user_btc($value->id);
                    $ethAddress = $this->create_user_eth($value->id);
                    $ethAddress = $this->create_user_ltc($value->id);
                    $usdWallet = $this->create_usd_wallet($value->id);
                    if (isset($btcAddress) && isset($ethAddress)) {
                        return 1;
                    } else {
                        return 0;
                    }
                }
            }
        }

    }

    public function create_usd_wallet($user_id)
    {
        $walletaddress = UserWallet::on('mysql2')->where(['user_id' => $user_id, 'currency' => 'USD'])->first();
        if (!$walletaddress) {
            $walletaddress = new UserWallet;
            $walletaddress->setConnection('mysql2');
            $walletaddress->user_id = $user_id;
            $walletaddress->currency = 'USD';
        }
        $walletaddress->balance = 0.00000000;
        $walletaddress->escrow_balance = 0.00000000;
        $walletaddress->created_at = date('Y-m-d H:i:s', time());
        $walletaddress->updated_at = date('Y-m-d H:i:s', time());
		$walletaddress->save();
		return true;
    }

}
