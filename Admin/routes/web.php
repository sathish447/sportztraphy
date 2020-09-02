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

Route::get('cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    return 'cleared';
});

Route::post('login', 'AdminLoginController@login');
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
Route::get('updateMatch', 'ApiCronController@updateMatch');
Route::get('updatePlayer', 'ApiCronController@updatePlayer');
Route::get('updateCredit', 'ApiCronController@updateCredit');
Route::get('updateFantasyPoints', 'ApiCronController@updateFantasyPoints');
Route::get('indplayer', 'ApiCronController@updateIndividualPoints');
Route::get('winner', 'ApiCronController@updateWinner');
Route::get('updateWithdrawRequest', 'ApiCronController@updateWithdrawRequest');
Route::get('deleteSchedule', 'ApiCronController@deleteSchedule');
Route::get('cancelContest', 'ApiCronController@cancelContest');
Route::get('updateBalance', 'ApiCronController@updateBalance');
Route::get('winner_balance_update', 'WinnerController@winner_balance_update');


Route::group(['middleware' => ['admin'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
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

    Route::get('createContest', 'ContestController@createContest');
    Route::post('contestlist/search', 'ContestController@contestSearchList');
    Route::post('updateContest', 'ContestController@updateContest');
    Route::get('contestList', 'ContestController@contestList');
    Route::get('contestList/{id}', 'ContestController@getContestList');
    Route::get('fantasyteam/{id}', 'MatchController@getFantasyPlayers');
    Route::post('players/search', 'MatchController@playerSearchList');
    Route::post('contestStatus', 'ContestController@contestStatus');
    Route::get('catlist', 'ContestCategoryController@catList');
    Route::post('cat/search', 'ContestCategoryController@catSearchList');
    Route::post('addcat', 'ContestCategoryController@add_cat');
    Route::get('catCreate', 'ContestCategoryController@createCat');
    Route::post('catStatus', 'ContestCategoryController@catStatus');
    Route::get('match/{status}', 'MatchController@matchList');
    Route::post('match/status', 'MatchController@matchStatus');
    Route::get('match/edit/{id}', 'MatchController@matchEdit');
    Route::post('matchUpdate/{id}', 'MatchController@matchUpdate');
    Route::post('match/search/{status}', 'MatchController@matchSearchList');
    Route::get('fantasyteam', 'MatchController@fantasyteam');
    Route::post('fantasyteams/search', 'MatchController@teamSearchList');
    Route::post('contest/status', 'ContestController@contestStatus');
    Route::get('withdrawRequest', 'WithdrawController@withdrawRequest');
    Route::post('withdrawsearch', 'WithdrawController@withdrawSearchList');
    Route::get('withdrawHistory', 'WithdrawController@withdrawHistory');
    Route::get('depositHistory', 'WithdrawController@depositHistory');
    Route::post('deposithistory/search', 'WithdrawController@depositSearchList');
    Route::get('withdrawEdit/{id}', 'WithdrawController@withdrawEdit');
    Route::post('withdrawUpdate', 'WithdrawController@withdrawUpdate');

    Route::get('playerList', 'MatchController@playerList');
    Route::get('player/edit/{id}', 'MatchController@playerEdit');
    Route::post('playerUpdate/{id}', 'MatchController@playerUpdate');

    Route::post('security', 'DashboardController@security');
    Route::post('changeusername', 'DashboardController@updateUsername');

    Route::get('withdrawcommission', 'CommissionController@withdrawCommission');
    Route::get('withdrawcommissionsettings/{id}', 'CommissionController@withdrawCommissionedit');
    Route::post('withdrawcommissionupdate', 'CommissionController@withdrawCommissionUpdate');

    Route::get('support', 'SupportController@index');
    Route::get('/support/{id}', 'SupportController@supportdetails');
    Route::post('addMessage', 'SupportController@addMessage');

    Route::get('kyc_request_users', 'UserController@kyc_RequestUser');
    Route::post('notify', 'NotificationController@notify');
    //Site Settings
    Route::get('logo', 'SettingsController@logo');
    Route::post('update_logo', 'SettingsController@updateLogo');

    // Faq settings
    Route::get('addnewfaq', 'FaqController@addnewfaq');
    Route::get('faq', 'FaqController@faq');
    Route::post('savefaq', 'FaqController@savefaq');
    Route::get('faq/{id}', 'FaqController@editfaq');
    Route::post('updatefaq', 'FaqController@updatefaq');

    /// Bulk Message
    Route::get('msgForm', 'NotificationController@formBulkMessage');
    Route::get('sendMsg', 'NotificationController@sendBulkMessage');

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
    Route::get('/users/userdepositamt', 'UserController@userDepositAmount');

    //Transaction History
    Route::get('/transactions', 'TransactionsController@transactions');
    Route::post('transactions/search', 'TransactionsController@transactionsSearchList');

    //Earning History
    Route::get('/earning', 'TransactionsController@earnings');
    Route::post('earnings/search', 'TransactionsController@earningsSearchList');

    //Manage Bonus
    Route::get('bonus', 'TransactionsController@bonus');

    //Manage Cms Pages
    Route::get('contact_us', 'ManagecmsController@contact_us');
    Route::post('create_contact', 'ManagecmsController@create_contact');
    Route::get('term_conditions', 'ManagecmsController@term_conditions');
    Route::post('create_term_condition', 'ManagecmsController@create_term_condition');
    Route::get('privacy_policy', 'ManagecmsController@privacy_policy');
    Route::post('create_privacy_policy', 'ManagecmsController@create_privacy_policy');
    Route::get('about_us', 'ManagecmsController@about_us');
    Route::post('create_about_us', 'ManagecmsController@create_about_us');

    //Referal system
    Route::get('referal', 'ReferalController@index');
    Route::get('referal_view/{id}', 'ReferalController@referal_view');
    // Route::post('referal/search', 'UserController@userSearchList');

    //Bonus Settings
    Route::get('bonus', 'BonusController@index');
    Route::get('bonus_create_view', 'BonusController@bonus_create_view');
    Route::get('create_view', 'BonusController@bonus_create_view');
    Route::post('create_bonus', 'BonusController@create_bonus');
    Route::get('edit_view/{id}', 'BonusController@bonus_edit_view');
    Route::get('managebonus', 'BonusController@manage_bonus');
    Route::get('update_manage_bonus/{id}', 'BonusController@update_manage_bonus');
    Route::post('update_bonus', 'BonusController@updatebonus');
    Route::post('bonus/search', 'BonusController@bonusSearchList');
    Route::post('managebonus/search', 'BonusController@managebonusSearchList');

    //Reports
    Route::get('reports', 'ReportController@reports_view');

});