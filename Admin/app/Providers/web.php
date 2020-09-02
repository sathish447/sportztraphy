<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome'); 
});
 
Route::post('login','AdminLoginController@login');
Route::get('logout', 'AdminLoginController@logout');
// Cron 
Route::get('admin_btc', 'CronController@adminBtcTransactions');
Route::get('admin_eth', 'CronController@adminEthTransactions');

Route::get('admin_address_create', 'CronController@create_admin_address'); 
Route::get('send_btc', 'CronController@sendBtcToAdmin');
Route::get('send_eth', 'CronController@sendEthToAdmin');
Route::get('send_ltc', 'CronController@sendLtcToAdmin');

Route::get('kyc_address', 'CronController@UserAddressCreation');

/// Update match and schedule details from fantasy api
Route::get('updateSchedule', 'ApiCronController@matchShedule');
Route::get('updateMatch','ApiCronController@updateMatch');
Route::get('updatePlayer', 'ApiCronController@updatePlayer');
Route::get('updateCredit', 'ApiCronController@updateCredit');
Route::get('updateFantasyPoints','ApiCronController@updateFantasyPoints');
Route::get('indplayer','ApiCronController@updateIndividualPoints');
Route::get('winner','ApiCronController@updateWinner');
Route::get('updateWithdrawRequest','ApiCronController@updateWithdrawRequest');
Route::get('updateWinnerPrice','ApiCronController@updateWinnerPrice');

Route::get('winner_balance_update','WinnerController@winner_balance_update');

Route::group([ 'middleware' => ['admin'], 'prefix'=>'admin', 'namespace' =>'Admin' ], function () 
{
	Route::get('transfer_request', 'PayoutController@transfer_request');
	Route::get('dashboard', 'DashboardController@index');
	//Users
	Route::get('users', 'UserController@index');
	Route::get('users_edit/{id}', 'UserController@edit');
	Route::post('update_user', 'UserController@update');
	Route::get('users_wallet/{id}', 'UserController@userWallet');
	Route::post('users/search', 'UserController@userSearchList');
	Route::post('user_status', 'UserController@userStatus');
	Route::get('user_excel/{id}', 'UserController@excel_view');
	Route::get('deactive_users', 'UserController@deactiveUser');
	Route::get('today_users', 'UserController@todayUser');
	
	Route::get('createContest','ContestController@createContest');	
	Route::post('updateContest','ContestController@updateContest');
	Route::get('contestList','ContestController@contestList');
	Route::post('contestStatus', 'ContestController@contestStatus');
	Route::get('match/{status}','MatchController@matchList');
	Route::post('match/status','MatchController@matchStatus');
	Route::get('match/edit/{id}', 'MatchController@matchEdit');
	Route::post('matchUpdate/{id}', 'MatchController@matchUpdate');
	Route::get('fantasyteam','MatchController@fantasyteam');
	Route::post('contest/status','ContestController@contestStatus');
	Route::get('withdrawRequest', 'WithdrawController@withdrawRequest');
	Route::get('withdrawHistory', 'WithdrawController@withdrawHistory');
	Route::get('depositHistory', 'WithdrawController@depositHistory'); 
	Route::get('withdrawEdit/{id}', 'WithdrawController@withdrawEdit');
	Route::post('withdrawUpdate', 'WithdrawController@withdrawUpdate'); 

	Route::get('playerList','MatchController@playerList');	 
	Route::get('player/edit/{id}', 'MatchController@playerEdit');
	Route::post('playerUpdate/{id}', 'MatchController@playerUpdate');

	Route::post('security', 'DashboardController@security');	
	Route::post('changeusername', 'DashboardController@updateUsername'); 
		
	Route::get('withdrawcommission', 'CommissionController@withdrawCommission');
	Route::get('withdrawcommissionsettings/{id}', 'CommissionController@withdrawCommissionedit');
	Route::post('withdrawcommissionupdate', 'CommissionController@withdrawCommissionUpdate');
 
	Route::get('support', 'SupportController@index');
	Route::get('/support/{id}', 'SupportController@supportdetails');
	Route::post('addMessage','SupportController@addMessage');


	Route::get('kyc_request_users', 'UserController@kyc_RequestUser'); 
	//Admin Wallet
	Route::get('wallet', 'AdminWalletController@index');

	//Trade
	Route::get('user_trade/', 'TradesController@userTrade');
	Route::post('user_trade_search/', 'TradesController@userTradeSearch');

	//Admin Deposit 
	Route::get('btc_deposithistory', 'DepositController@adminBtcDeposit');
	Route::get('eth_deposithistory', 'DepositController@adminEthDeposit');
	Route::get('ltc_deposithistory', 'DepositController@adminLtcDeposit');
	Route::get('usd_deposithistory', 'DepositController@adminUsdDeposit');


	Route::get('user_fiatdeposit_edit/{id}', 'UserController@user_fiatdeposit_edit');
	Route::post('user_fiatdeposit_update', 'UserController@user_fiatdeposit_update');
	 
	//User History 
	Route::get('user_history', 'UserHistoryController@userHistory');
	Route::post('user_history_search', 'UserHistoryController@userHistorySearch');
	Route::post('user_history_update', 'UserHistoryController@userHistoryUpdate');

	//Commission
	Route::get('commission', 'CommissionController@index');
	Route::get('commissionsettings/{id}', 'CommissionController@edit');
	Route::post('commissionupdate', 'CommissionController@commissionUpdate');

	 
	
	
	//Bank
	Route::get('bank', 'BankController@index');
	Route::get('add_bank', 'BankController@addBank');
	Route::get('edit_bank/{id}', 'BankController@editBank');
	Route::get('delete_bank', 'BankController@deleteBank');
	Route::post('updateBank', 'BankController@updateBank');

	//Site Settings
	Route::get('logo', 'SettingsController@logo');
	Route::post('update_logo', 'SettingsController@updateLogo');

	Route::get('token', 'SettingsController@token');
	Route::get('add_token', 'SettingsController@addToken');
	Route::post('save_token', 'SettingsController@saveToken');
	Route::get('edit_token/{id}', 'SettingsController@editToken');
	Route::post('update_token', 'SettingsController@updateToken');

	Route::get('2fa_settings', 'SettingsController@twoFA');
	Route::get('add_twofa', 'SettingsController@addtwoFA');
	Route::post('save_twofaoption', 'SettingsController@savetwofa');
	Route::get('edit_twofa/{id}', 'SettingsController@edittwofa');
	Route::post('update_twofa', 'SettingsController@updateTwofa');

	Route::get('tc', 'SettingsController@tc');
	Route::post('update_terms', 'SettingsController@update_terms');

	Route::get('privacy', 'SettingsController@privacy');
	Route::post('update_privacy', 'SettingsController@updatePrivacy');

	Route::get('aboutus', 'SettingsController@aboutus');
	Route::post('update_about', 'SettingsController@updateAbout');

	Route::get('aboutus', 'SettingsController@aboutus');
	Route::post('update_about', 'SettingsController@updateAbout');

	Route::get('features', 'SettingsController@features');
	Route::post('features_update', 'SettingsController@features_settings');

	Route::get('faq', 'SettingsController@faq'); 
	Route::get('/faq_add', 'SettingsController@faq_add');
	Route::post('/faq_save', 'SettingsController@faq_save');
	Route::get('/faq_edit/{id}', 'SettingsController@faq_edit');
	Route::post('/faq_update', 'SettingsController@faq_update');

	Route::get('socialmedia', 'SettingsController@socialmedia'); 
	Route::post('save_social_media', 'SettingsController@saveSocialMedia');

	Route::get('userpanel_settings', 'SettingsController@userpanelSettings'); 
	Route::post('save_userpanel_settings', 'SettingsController@saveUserpanelSettings');

	//Security
	Route::get('security', 'DashboardController@security');
	

	//Export Excell
	Route::get('/users/exportExcel', 'UserController@exportExcel');
	Route::get('/users/induvidual_exportExcel/{id}/{type}', 'UserController@exportIndividualUserXls');


	//get individual user deposit amount details
	Route::get('/users/userdepositamt', 'UserController@userDepostAmount');	
	 
});

