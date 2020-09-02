@include('email.header')
<tr><td align='center'>&nbsp;</td><td style='text-align:left;font-size: 15px;color:#000;'>Welcome to {{ config('app.name') }}</td><td align='left'>&nbsp;</td></tr>

<tr><td colspan='3' align='center' height='1' style='padding:0px;'></td></tr>
<tr><td align='left' style='padding-top:0px;'>&nbsp;</td><td style='text-align:left;font-size:15px;color:#000;padding-top:0px;'>{{$username}} has invited you to join with fantasy app. you will get a bonus for joining the app.</td><td align='left' style='padding-top:0px;'>&nbsp;</td></tr>
<tr><td colspan='3' align='center' height='30' style='padding:0px;'></td></tr>

<tr><td align='center'>&nbsp;</td><td align='center'></td></tr>
<!-- <tr><td align='center'>&nbsp;</td><td align='center'><a href="https://fantasy.demozab.com/register/{{$invite_code}}" style='color:#fff;padding:14px 22px;text-decoration:none;background-color:#f35a24;text-transform:uppercase;font-size:15px;font-weight:600;'>Your Friend Invitation</a></td><td align='center'>&nbsp;</td></tr>
 --><tr><td align='center'>&nbsp;</td><td align='center'><a href="http://192.168.1.66:4201/register/{{$invite_code}}" style='color:#fff;padding:14px 22px;text-decoration:none;background-color:#f35a24;text-transform:uppercase;font-size:15px;font-weight:600;'>Your Friend Invitation</a></td><td align='center'>&nbsp;</td></tr>


<tr><td colspan='3' align='center' height='30' style='padding:0px;'></td></tr>
@include('email.footer')