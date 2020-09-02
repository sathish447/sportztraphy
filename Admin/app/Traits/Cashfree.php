<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Response;
use Auth; 
use DB;

trait cashfree
{ 
    public function Auth_API(){  
        
        $ch = curl_init();
        $header = array(
            "X-Client-Id: CF10554D0KIWPV02LAQ2Q2",
            "X-Client-Secret: 2b52fc4c542418b759bf077961b3c0b4998b1fce",
            "cache-control: no-cache"
          );

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL,"https://payout-gamma.cashfree.com/payout/v1/authorize");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header); 
        
        $response = curl_exec($ch);
        $err = curl_error($ch); 
         if ($err) {
          echo "cURL Error #:" . $err;
        } else {
         curl_close($ch);
         $res = json_decode($response, true);  
         return $res;
        }
    }
 
    public function cashfree_curl($url, $data){
       
        
        $baseUrls = 'https://payout-gamma.cashfree.com/'; 
        $finalUrl = $baseUrls.$url;
        $response = $this->Auth_API(); 
        $token = $response['data']['token'];
 
        $headers = array( 
        'Authorization: Bearer '.$token,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        if(!is_null($data)) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
        
        $r = curl_exec($ch);
        
        if(curl_errno($ch)){
        print('error in posting');
        print(curl_error($ch));
        die();
        }
        curl_close($ch);
        $rObj = json_decode($r, true);    
        return $rObj;
    }
  
 
    public function cashfree_getcurl($url){

        $baseUrls = 'https://payout-gamma.cashfree.com/'; 
        $finalUrl = $baseUrls.$url;
        $response = $this->Auth_API(); 
        $token = $response['data']['token'];
 
        $headers = array( 
        'Authorization: Bearer '.$token,
        ); 
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        $r = curl_exec($ch);
        
        if(curl_errno($ch)){
            print('error in posting');
            print(curl_error($ch));
            die();
        }
        curl_close($ch);
        $rObj = json_decode($r, true);     
        return $rObj;
    }

} 
 
?>