<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['uid', '2fa', 'otp'];

    public static function insert($value)
    {
        $profile = Profile::on('mysql2')->create($value);
        if ($profile) {
            return true;
        } else {
            return false;
        }
    }

    public static function modify($value, $where)
    {
        $profile = Profile::on('mysql2')->where($where)->update($value);

        if ($profile) {
            return true;
        } else {
            return false;
        }
    }


    public static function select(array $value)
    {
        $profile = Profile::on('mysql2')->where($value)->first();
        return $profile;
    }

    public static function EmailOtpCheck(array $value)
    {
          $check_email = Profile::on('mysql2')->where($value)->first();
          
          if($check_email){
            return 'success';  
          }else{
            return 'fail';
          }
    }

  

}
