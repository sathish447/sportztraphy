<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\Commission;
use App\Models\UserWallet;
use App\Models\AdminBtcAddress;
use App\Models\AdminEthAddress;
use App\Models\AdminLtcAddress;
use App\Models\AdminJadaxAddress;
use App\Traits\Bitcoin;

class AdminWalletController extends Controller
{
	use Bitcoin;

	public function index()
	{ 
		$btc_address = AdminBtcAddress::first();

		if($btc_address){
			$btc_balance = $this->btcBalance($btc_address->address);
			$btc_address = $btc_address->address;	
		}else{
			$btc_balance = 0.00;	
			$btc_address = 'No Address';	
		}		

		$eth_address = AdminEthAddress::first();
		if($eth_address){
			$eth_balance = $this->ethBalance($eth_address->address);	
			$eth_address = $eth_address->address;
		}else{
			$eth_balance = 0.00;
			$eth_address = 'No Address';	
		}


		$ltc_address = AdminLtcAddress::first();
		if($ltc_address){
			$ltc_balance = $this->ltcBalance($ltc_address->address);	
			$ltc_address = $ltc_address->address;
		}else{
			$ltc_balance = 0.00;
			$ltc_address = 'No Address';
		}
	

		$address = array(
			'BTC'=>$btc_address,
			'ETH'=>$eth_address,
			'LTC'=>$ltc_address
			);

		$balance = array(
			'BTC'=>$btc_balance,
			'ETH'=>$eth_balance,
			'LTC'=>$ltc_balance,
			);

		return view('wallet.list',[
			'address' => $address,
			'balance' => $balance
			]);
	} 

	public function btcBalance($address)
	{
		if(!empty($address)){
            $url_link = "https://chain.so/api/v2/get_address_balance/BTC/".$address;
            $balance = $this->execCurl($url_link);  
            return $balance['data']['confirmed_balance']; 
        }else{
            return 0;
        }
	}

	public function ethBalance($address)
	{
		$url = "https://api.etherscan.io/api?module=account&action=balance&address=".$address;
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
        return $dd->result/1000000000000000000;
	}

	public function jadaxBalance($address)
	{
		$url = "https://api.tokenbalance.com/balance/0x94190e84A62c4e1fb4CDE76407a0dec59354395D/".$address;

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
        // return $dd/1000000000000000000;
        return $dd;
	}

	public function ltcBalance($address)
	{
		if(!empty($address)){
            $url_link = "https://chain.so/api/v2/get_address_balance/LTC/".$address;
            $balance = $this->execCurl($url_link);  
            return $balance['data']['confirmed_balance']; 
        }else{
            return 0;
        }
	}

}