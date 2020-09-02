<?php

function user($id)
{
$user = App\Models\User::on('mysql2')->where('id',$id)->first();
return $user;
}
function username($id)
{
$user = App\Models\User::on('mysql2')->where('id',$id)->first();

return $user->name;
}

function useremail($email)
{
$user = App\Models\User::on('mysql2')->where('email',$email)->first();

return $user;
}
function country()
{

$countries = App\Models\Countries::on('mysql2')->get();

return $countries;
}

function country_name($id)
{
$countries = App\Models\Countries::on('mysql2')->where('id',$id)->first();

return $countries;
}

function currency($type)
{
if($type == 4)
{
$currency = 'USD';
}else if($type == 5){
$currency = 'TRY';	
}else {
$currency = 'EUR';
}
return $currency;
}
	
function bank($id)
{
$bank = App\Models\Bank::on('mysql2')->where('id',$id)->first(); 

return $bank;
}

function Kyc($id)
{
$kyc = App\Models\Kyc::on('mysql2')->where('user_id',$id)->first(); 

return $kyc;
}

function humanTiming ($time)
{
$time = time() - $time;
$time = ($time < 1)? 1 : $time;
$tokens = array (
31536000 => 'year',
2592000 => 'month',
604800 => 'week',
86400 => 'day',
3600 => 'hour',
60 => 'min',
1 => 'sec'
);
foreach ($tokens as $unit => $text) {
if ($time < $unit) continue;
$numberOfUnits = floor($time / $unit);
return $numberOfUnits.' '.$text.(($numberOfUnits > 1) ? 's' : '');
}
}

function  userBalance($id,$type)
{
$wallet = App\Models\UserWallet::on('mysql2')->where('user_id',$id)->where('currency',$type)->first();
if(isset($wallet->balance))
{
return number_format($wallet->balance,8,'.','');
}
else
{
return number_format(0,8,'.','');
}

}

function adminCommissionIncome($type)
{
$commission = App\Models\AdminWallet::on('mysql2')->where('currency',$type)->first();

if(isset($commission->commission))
{
$commission = $commission->commission;
}
else
{
$commission = 0; 
}

return $commission;

}

function adminWithdrawIncome($type)
{
$withdraw = App\Models\AdminWallet::on('mysql2')->where('currency',$type)->first();

if(isset($withdraw->withdraw))
{
$withdraw = $withdraw->withdraw;
}
else
{
$withdraw = 0; 
}

return $withdraw;

}


function TransactionString($length = 15) {
$str = "";
$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
$max = count($characters) - 1;
for ($i = 0; $i < $length; $i++) {
$rand = mt_rand(0, $max);
$str .= $characters[$rand];
}
return $str;
}


function ncAdd($value1,$value2,$digit=8){
$value = bcadd(sprintf('%.10f',$value1), sprintf('%.10f',$value2), $digit);
return $value;
}
function ncSub($value1,$value2,$digit=8){
$value = bcsub(sprintf('%.10f',$value1), sprintf('%.10f',$value2), $digit);
return $value;
}
function ncMul($value1,$value2,$digit=8){
$value = bcmul(sprintf('%.10f',$value1), sprintf('%.10f',$value2), $digit);
return $value;
}

function ncDiv($value1,$value2,$digit=8){
$value = bcdiv(sprintf('%.10f',$value1), sprintf('%.10f',$value2), $digit);
return $value;
}


function pair_name($id)
    {
        $pairs = App\Models\Tradepair::pair_id($id);

        return $pairs->coinone.'/'.$pairs->coinone;
    }

