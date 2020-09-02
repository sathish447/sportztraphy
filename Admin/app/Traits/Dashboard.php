<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use App\Models\UserWallet;
use App\Models\AdminWallet;
use DB;

trait Dashboard
{
	public function totalInvestment($currency)
	{
		$total = UserWallet::on('mysql2')->where('currency',$currency)->sum(DB::raw('balance + escrow_balance'));

		if($currency == 'ETH')
		{
			$eth = $this->convertToBtc($total,'ETH');
			$final = $total * $eth;

		}
		elseif($currency == 'BTC')
		{
			$final = $total;
		}

		elseif($currency == 'LTC')
		{
			$ltc = $this->convertToBtc($total,'LTC');
			$final = $total * $ltc;
		}

		elseif($currency == 'USD')
		{
			$usd = $this->convertToBtc($total,'USD');
			$final = $total * $usd;

		}else{
			dd('invalid currency');
		}

		return $final;
	}

	public function siteUserBalance()
	{
		$btc = $this->totalInvestment('BTC');
		$eth = $this->totalInvestment('ETH');
		$ltc = $this->totalInvestment('LTC');
		$usd = $this->totalInvestment('USD');

		return array(
			'BTC' => round($btc,8),
			'ETH' => round($eth,8),
			'LTC' => round($ltc,8),
			'USD' => round($usd,2)
			);
	}

	public function income()
	{
		return array(
			'BTC' => $this->adminIncome('BTC'),
			'ETH' => $this->adminIncome('ETH'),
			'LTC' => $this->adminIncome('LTC'),
			'USD' => $this->adminIncome('USD')
			);
	}

	public function adminIncome($coin)
	{
		$total = AdminWallet::on('mysql2')->where('currency',$coin)->sum(DB::raw('commission + withdraw'));

		return $total;
	}

	public function convertToBtc($total,$currency)
	{
		$url = "https://api.coinmarketcap.com/v2/ticker/?convert=$currency";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if (curl_errno($ch)) {
			$result = 'Error:' . curl_error($ch);
		} else {
			$result = curl_exec($ch);
		}
		$result = json_decode($result, true); 

		$final = $result['data'][1]['quotes'][$currency]['price'];
		return number_format(1/$final,8,'.','');
	}

	
}