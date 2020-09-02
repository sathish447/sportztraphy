<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'middleware' => 'api'

], function ($router) {

    Route::get('test','AuthController@check_mongo');
    Route::get('test1','MatchController@test1');
    Route::get('test2','MatchController@test2');

    Route::post('facebooklogin','SocialloginController@facebooklogin');
    Route::post('gmaillogin', 'SocialloginController@gmaillogin');
    Route::post('register', 'AuthController@register');
    Route::post('otpverify', 'AuthController@otpverify');
    Route::post('password', 'AuthController@password');
    Route::post('mobile_request', 'SocialloginController@mobile_request');
    Route::post('facebook_mobile_request', 'SocialloginController@facebook_mobile_request');
        
    Route::post('login', 'AuthController@login');    
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('forgotpassword_submit', 'AuthController@resetpassword');
    Route::post('resetpassword_submit', 'AuthController@changeresetpassword');
    Route::post('subscriber', 'AuthController@subscribeEmail');

    Route::post('me', 'AuthController@me');
    Route::get('/verify/{email}/{verifyToken}', 'EmailVerifyController@sendEmailDone')->name('sendEmailDone');
    Route::get('users','AuthController@users');
    Route::get('deleteusers','AuthController@delete_user');
    
    Route::post('response', 'CashfreeController@response');
    

    Route::get('all','MatchController@allmatch');   
        
    
    Route::get('listteam', 'MatchController@createdteam_list');     
    
    Route::post('playercredits','MatchController@playercredits');
    Route::post('fantasypoints','MatchController@fantasypoints');
    Route::post('playerdetails','MatchController@playerdetails');

    Route::get('winnersupdatecontest','ContestController@contest_win_price_update');    
        //profile upload
    Route::post('profile_image_upload', 'UserController@profile_upload');
    Route::group(['middleware' => 'auth:api'], function(){

        Route::post('testcash', 'CashfreeController@index');
        Route::get('getuser_balance','WalletController@getuser_balance');
        Route::post('adduserbalance', 'WalletController@adduserbalance');
        Route::get('user_data','UserController@userdata');
        Route::post('changepassword','UserController@changepassword');       
    
        //account verify

        Route::get('send_verification_mail','UserController@send_verification_mail');
        Route::post('pan_submit','UserController@pan_submit');
        Route::get('view_pan','UserController@view_pan_details');
        Route::post('profile_upload','UserController@upload_profile');
        Route::post('bank_submit','UserController@bank_submit');
        Route::get('view_bank','UserController@view_bank_details');
        Route::post('invite_friends','UserController@invite_friends');    

        Route::post('profile_info_submit','UserController@profile_info_submit');

        //withdraw 
        Route::post('Withdraw_update', 'CashfreeController@withdraw_update');
        Route::post('get_user_transaction', 'CashfreeController@transaction_history');
        Route::get('trans', 'CashfreeController@transaction_details');

        Route::post('rank', 'MatchController@rank_details');

        //team create
        Route::post('createTeam', 'MatchController@createTeam'); 
        Route::post('saveteam', 'MatchController@saveTeam'); 
        Route::post('selectteamdetail', 'MatchController@selectteamdetail'); 

        Route::post('selectcvcteam', 'MatchController@selectcvcteam'); 
        Route::post('savecvcteam', 'MatchController@savecvcteam'); 

        Route::post('selectmycreatedteam','MatchController@selectteamdetail');
        Route::post('myviewteam','MatchController@myviewteam');
        Route::post('selectviewteam','MatchController@selectviewteam');

        Route::post('editTeam', 'MatchController@editTeam'); 
        Route::post('updateteam', 'MatchController@updateTeam');
        
        Route::post('testcontest','RazorpayController@Contest_list');
        //contest details
        Route::post('allcontest','ContestController@Contest_list');
        Route::post('selectcontest','ContestController@selectcontest');
        Route::post('participatedetails','ContestController@participatedetails');
        Route::post('contest-info','ContestController@Contest_info');
        Route::post('joincontest','ContestController@joincontest');
        Route::post('leadercontest','ContestController@leaderboard_contest');
        Route::post('entryteamdetails','ContestController@entryteamdetails');
        Route::post('userContest','ContestController@userContest');
        Route::post('footuserContest','ContestController@footuserContest');
        Route::post('contestinfo','ContestController@Contest_info');
        
        Route::post('order_id_generation', 'CashfreeController@order_id_generation');    
        //match related details
        Route::get('allmatch','MatchController@allmatch');
        Route::post('allmatchlimit','MatchController@allmatchlimit');
        Route::post('matchdetails', 'MatchController@matchDetails');
        Route::post('getelevenplayers', 'MatchController@matchDetails');

        Route::post('mymatch','MatchController@mymatch');
        Route::get('mymatch','MatchController@mymatch');

        Route::get('mymatchdetails','MatchController@mymatchdetails'); 
        Route::get('mymatchcount','MatchController@mymatchcount');  
        
        Route::post('getplayerdetail','MatchController@getplayerdetail');
        
        //Support
        Route::post('add_ticket','SupportController@submitNewTicket');
        Route::post('userticketdetails','SupportController@newticket');
        Route::post('userchatdetails','SupportController@viewTicket');
        Route::post('sendMessage','SupportController@sendMessage');

        //Feecalculation
        Route::post('feecalculation','ContestController@feecalculation');


    /* ************************* Football urls ***********************  */

    Route::get('footmatches','MatchController@footmatches');   
    Route::get('footmymatches','MatchController@footmymatches');   
    Route::get('myfootballmatchdetails','FootballMatchController@myfootballmatchdetails');   

    //foot contest
    Route::post('footcontestlist','ContestController@footcontest_list');   
    
    //foot match details
    Route::post('footmatchdetails','FootballMatchController@footmatchDetails');   
       
    });

    //foot team create
    Route::post('footcreateTeam','FootballMatchController@footcreateTeam');   
    Route::post('footsaveteam', 'FootballMatchController@footsaveteam'); 
    Route::post('footselectcvcteam', 'FootballMatchController@footselectcvcteam'); 
    Route::post('footsavecvcteam', 'FootballMatchController@footsavecvcteam'); 
    Route::post('footmyviewteam','FootballMatchController@footmyviewteam');
    Route::post('footselectviewteam','FootballMatchController@footselectviewteam');
    Route::post('footeditTeam', 'FootballMatchController@footeditTeam'); 
    Route::post('footviewteam', 'FootballMatchController@footviewTeam'); 
    Route::post('footupdateteam', 'FootballMatchController@footupdateTeam'); 
    Route::get('footmymatch', 'FootballMatchController@footmymatch'); 
});
