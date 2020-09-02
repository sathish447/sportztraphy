<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    //
    public function index()
    {
        // \DB::listen(function ($query) {
        //     dump($query);
        // });
        $usrs = \App\User::cursor();
        /* foreach ($usrs as $usr) {
        logger($usr->first_name);
        } */
        return view('pages.home', ['users' => $usrs]);
    }

    public function test()
    {
        $match_key = 'bplt20_2020_g26';
        $contest_id='5def40b3d59ef822b1ba756f';
        $userid = '5de2534256bee414fb639352';
          
        $allLists = \DB::table('fantasy_teams')->where(['match_key'=>$match_key,'contest_id'=>$contest_id,'paid_status'=>1])->orderBy('fantasy_points','desc')->get();  
        $notmydata=''; 
        $mydata=''; 
        $alldata='';
        $newdata ='';
        
        if($allLists->count() > 0) {

            foreach($allLists as $key=>$li){

               $commondata=''; 
                
               $user_id = isset($li['user_name']) ? $li['user_name'] : '';
               $id = isset($li['user_id']) ? $li['user_id'] : '';
               $fpoints = $li['fantasy_points'];
               $rank = $fpoints == 0 ? '-' : $key+1; 
               $paid_status = isset($li['paid_status']) ? $li['paid_status'] : 0;
               $keyid = $li['_id'];
               $players = $li['players'];  

               if($id != $userid){

                 $notmydata .= ' <div data-target=#open-' . $keyid.' data-toggle="collapse" class="leader-board-div white-box"> <div class="leader-div1">'.$user_id.'</div> <div class="leader-div2">'.$li['fantasy_points'].'</div> <div class="leader-div3">'.$rank.'</div></div>';  
                 $notmydata .= $this->getdata($players,$keyid,$alldata);
               }
               if($id !='' && $id == $userid){
                 $mydata .= ' <div data-target=#open-' . $keyid.' data-toggle="collapse" class="leader-board-div white-box showmyteam"> <div class="leader-div1">'.$user_id.'</div> <div class="leader-div2">'.$li['fantasy_points'].'</div> <div class="leader-div3">'.$rank.'</div></div>';
                 $mydata .= $this->getdata($players,$keyid,$alldata);
               } 
           }

           $newdata=$mydata.$notmydata; 
        }
        else{
            $newdata='';
        }

         
        $datas['json'] = $newdata;
        echo "<pre>";    
        print_r($newdata);
    }

    public function getdata($players,$keyid,$alldata)
    {
     
               $bowler1=[];
               $keeper1=[];
               $allrounder1=[];
               $batsman1=[];
  
               foreach ($players as $pl) { 
                 $players_role = isset($pl['role']) ? $pl['role'] : ''; 

                 if($players_role !='' && $pl['role'] == 'wk'){
                    $keeper1[] = $pl; 
                 }
                 else if($players_role !='' && $pl['role'] == 'bowl'){
                    $bowler1[] = $pl; 
                 }
                 else if($players_role !='' && $pl['role'] == 'bat'){
                    $batsman1[] = $pl; 
                 }
                 else if($players_role !='' && $pl['role'] == 'ar'){
                    $allrounder1[] = $pl; 
                 } 
               } 
              
               $img="https://fantasy.demozab.com/assets/images/ground-bg1.jpg";
               $alldata .='<div class="collapse" data-parent="#accordionExample" id="open-'.$keyid.'" >
                            <div class="socket white-box v-ground white-t" style="background-image:url('.$img.'); background-size: 100%; background-position: center center;">
                            <table class="select-table-player white-t">
                            <tbody>
                            <tr>
                            <td>
                            <h6> Wicket-Keepers </h6> 
                            <table>
                            <tbody>';
                           if(isset($keeper1) && count($keeper1) > 0) :
                            foreach ($keeper1 as $wk) { 
                                if($wk['caption']){
                                    $chigh = " captain";
                                } elseif($wk['v_caption']){
                                    $chigh = " vcaptain";
                                } else {
                                    $chigh = "";
                                }
                               $alldata .= ' <tr class="player-data-td '.$chigh.'">
                                <td class="player-pic"><img src="assets/images/player.svg"></td>
                                <td class="player-name h6">'.$wk['name'].'<br><span class="grey-t s-t"> WK - '.$wk['team_name'].'</span></td>
                                <td>'.$wk["fantasy_points"].'<br>
                                <span class="grey-t s-t">Points</span>
                                </td>
                                </tr> ';
                             }  
                             else :
                              $alldata .= ' <tr> <td colspan="4"> - </td> </tr> '; 

                            endif;  
                           
                   $alldata .=' </tbody>
                            </table>
                            </td>
                            <td>
                            <h6>Batsmen</h6>
                            <table>
                            <tbody>';
                        if(isset($batsman1) && count($batsman1) > 0) :
                            foreach ($batsman1 as $wk) { 
                                if($wk['caption']){
                                    $chigh = " captain";
                                } elseif($wk['v_caption']){
                                    $chigh = " vcaptain";
                                } else {
                                    $chigh = "";
                                }
                                $alldata .= ' <tr class="player-data-td'.$chigh.'">
                                <td class="player-pic"><img src="assets/images/player.svg"></td>
                                <td class="player-name h6">'.$wk['name'].'<br><span class="grey-t s-t"> BAT - '.$wk['team_name'].'</span></td>
                                <td>'.$wk["fantasy_points"].'<br>
                                <span class="grey-t s-t">Points</span>
                                </td>
                                </tr> ';
                             }  
                           else :
                              $alldata .= ' <tr> <td colspan="4">-</td> </tr> '; 
                           endif;  
                        $alldata .=' </tbody>
                            </table>
                            </td> 

                            <td>
                            <h6>All-Rounders</h6>
                            <table>
                            <tbody>'; 
                              if(isset($allrounder1) && count($allrounder1) > 0) :
                            foreach ($allrounder1 as $wk) { 
                                if($wk['caption']){
                                    $chigh = " captain";
                                } elseif($wk['v_caption']){
                                    $chigh = " vcaptain";
                                } else {
                                    $chigh = "";
                                }
                               $alldata .= ' <tr class="player-data-td'.$chigh.'">
                                <td class="player-pic"><img src="assets/images/player.svg"></td>
                                <td class="player-name h6">'.$wk['name'].'<br><span class="grey-t s-t"> AR - '.$wk['team_name'].'</span></td>
                                <td>'.$wk["fantasy_points"].'<br>
                                <span class="grey-t s-t">Points</span>
                                </td>
                                </tr> ';
                             }  
                              else :
                              $alldata .= ' <tr> <td colspan="4">-</td> </tr> '; 
                            endif;  
                   $alldata .=' </tbody>
                            </table>
                            </td>
                            <td>
                            <h6>Bowlers</h6>
                            <table>
                            <tbody>
                            ';
                       
                            if(isset($bowler1) && count($bowler1) > 0) :
                             foreach ($bowler1 as $wk) { 
                                if($wk['caption']){
                                    $chigh = " captain";
                                } elseif($wk['v_caption']){
                                    $chigh = " vcaptain";
                                } else {
                                    $chigh = "";
                                }  
                                $alldata .= ' <tr class="player-data-td'.$chigh.'">
                                <td class="player-pic"><img src="assets/images/player.svg"></td>
                                <td class="player-name h6">'.$wk['name'].'<br><span class="grey-t s-t"> BOWL - '.$wk['team_name'].'</span></td>
                                <td>'.$wk["fantasy_points"].'<br>
                                <span class="grey-t s-t">Points</span>
                                </td>
                                </tr> ';
                             } 
                              else :
                              $alldata .= ' <tr> <td colspan="4"> - </td> </tr> '; 
                            endif;  
                   $alldata .='</tbody>
                            </table>
                            </td>
                            </tr>
                            </tbody>
                            </table>
                            </div>
                            </div>'; 
            return $alldata;
    }
   
}