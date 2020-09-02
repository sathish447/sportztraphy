@include('email.header')
<tr><td align='center'>&nbsp;</td><td style='text-align:left;font-size: 15px;color:#000;'>Welcome to {{ config('app.name') }}</td><td align='left'>&nbsp;</td></tr>

<tr><td colspan='3' align='center' height='1' style='padding:0px;'></td></tr>
<tr><td align='left' style='padding-top:0px;'>&nbsp;</td><td style='text-align:left;font-size:15px;color:#000;padding-top:0px;'> You are receiving this email because we received a password reset request for your account. </td><td align='left' style='padding-top:0px;'>&nbsp;</td></tr>
<tr><td colspan='3' align='center' height='30' style='padding:0px;'></td></tr>

<!-- <tr><td align='center'>&nbsp;</td><td align='center'><a style='color:#fff;padding:14px 22px;text-decoration:none;background-color:#f35a24;text-transform:uppercase;font-size:15px;font-weight:600;'>OTP: {{ $user->profile_otp }}</a></td><td align='center'>&nbsp;</td></tr> -->
<tr><td align='center'>&nbsp;</td><td align='center'></td></tr>
<tr><td align='center'>&nbsp;</td><td align='center'><a href="http://192.168.1.66:4201/resetpassword/{{$user->forgot_secrect}}" style='color:#fff;padding:14px 22px;text-decoration:none;background-color:#f35a24;text-transform:uppercase;font-size:15px;font-weight:600;'>Reset Password</a></td><td align='center'>&nbsp;</td></tr>
<!-- <tr><td align='center'>&nbsp;</td><td align='center'><a href="https://fantasy.demozab.com/resetpassword/{{$user->forgot_secrect}}" style='color:#fff;padding:14px 22px;text-decoration:none;background-color:#f35a24;text-transform:uppercase;font-size:15px;font-weight:600;'>Reset Password</a></td><td align='center'>&nbsp;</td></tr> -->


<tr><td colspan='3' align='center' height='30' style='padding:0px;'></td></tr>
@include('email.footer')