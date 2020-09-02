import { NgModule } from '@angular/core';
import { Routes, CanActivate, RouterModule } from '@angular/router';
import { AllMatchesComponent } from './components/all-matches/all-matches.component';
import { ContestsComponent } from './components/contests//contests.component';
import { LoginComponent } from './components/login/login.component';
import { OtpComponent } from './components/otp//otp.component';
import { RegisterComponent } from './components/register/register.component';
import { SocialloginComponent } from './components/sociallogin/sociallogin.component';
import { from } from 'rxjs';
import { ForgotpasswordComponent } from './components/forgotpassword/forgotpassword.component';
import { MobileNumberComponent } from './components/mobile-number/mobile-number.component';
import { ResetpasswordComponent } from './components/resetpassword/resetpassword.component';
import { PasswordComponent } from './components/password/password.component';
import { MyaccountComponent } from './components/myaccount/myaccount.component';
import { AfterLoginService } from './services/after-login.service';
import { BeforeLoginService } from './services/before-login.service';
import { AccountVerifyComponent } from './components/account-verify/account-verify.component';
import { ChangepasswordComponent } from './components/changepassword/changepassword.component';
import { PersonalInfoComponent } from './components/personal-info/personal-info.component';
import { TransactionsComponent } from './components/transactions/transactions.component';
import { WithdrawComponent } from './components/withdraw/withdraw.component';
import { PlayerCreditsComponent } from './components/player-credits/player-credits.component';
import { TeamCreateComponent } from './components/team-create/team-create.component';
import { CreateContestComponent } from './components/create-contest/create-contest.component';
import { ContestInfoComponent } from './components/contest-info/contest-info.component';
import { TeamcaptainComponent } from './components/teamcaptain/teamcaptain.component';
import { MymatchComponent } from './components/mymatch/mymatch.component';
import { ViewteamComponent } from './components/viewteam/viewteam.component';
import { TeameditComponent } from './components/teamedit/teamedit.component';
import { MycontestComponent } from './components/mycontest/mycontest.component';
import { MyrankComponent } from './components/myrank/myrank.component'; 
import { PointsystemComponent } from './components/pointsystem/pointsystem.component'; 

// import { TestcomponentComponent } from './components/testcomponent/testcomponent.component';



const routes: Routes = [
  {
    path: '',
    component: RegisterComponent
  },
  {
    path: 'register',
    component: RegisterComponent,
    // canActivate: [BeforeLoginService],

  },
  {
    path: 'register/:invite_code',
    component: RegisterComponent,
    // canActivate: [BeforeLoginService],

  },
  {
    path: 'allmatch',
    component: AllMatchesComponent,
    // canActivate: [BeforeLoginService],
  },
  {
    path: 'myrank',
    component: MyrankComponent,
    // canActivate: [BeforeLoginService],
  },
  {
    path: 'mymatch',
    component: MymatchComponent,
    // canActivate: [BeforeLoginService],
  },
  {
    path: 'contests',
    component: ContestsComponent
  },
  {
    path: 'contests/:matchkey',
    component: ContestsComponent
  },
  {
    path: 'login',
    component: LoginComponent,
    // canActivate: [BeforeLoginService],
  },
  {
    path: 'forgotpassword',
    component: ForgotpasswordComponent
  },
  {
    path: 'otp/:msg_id',
    component: OtpComponent
  },
  {
    path: 'gmaillogin',
    component: RegisterComponent,
    // canActivate: [AfterLoginService],
  },
  {
    path: 'facebooklogin',
    component: RegisterComponent,
    // canActivate: [AfterLoginService],
  },

  /* Gmail url */
  {
    path: 'mobile-number/:id/:token/:name/:email/:image_url/:session_id',
    component: MobileNumberComponent
  },

  /* Facebook url */
  {
    path: 'mobile-number/:id/:first_name/:last_name/:email/:session_id',
    component: MobileNumberComponent
  },

  {
    path: 'resetpassword/:forgot_secret',
    component: ResetpasswordComponent,
  },

  {
    path: 'password/:email',
    component: PasswordComponent,
  },

  {
    path: 'myaccount',
    component: MyaccountComponent,
    // canActivate: [AfterLoginService]
  },
  {
    path: 'account-verify',
    component: AccountVerifyComponent,
    // canActivate: [AfterLoginService]
  },

  {
    path: 'changepassword',
    component: ChangepasswordComponent,
    // canActivate: [AfterLoginService]
  },
  {
    path: 'personalinfo',
    component: PersonalInfoComponent,
    // canActivate: [AfterLoginService]
  },
  {
    path: 'testcash',
    component: MyaccountComponent,
  },

  {
    path: 'transactions',
    component: TransactionsComponent,
    // canActivate: [AfterLoginService]
  },

  {
    path: 'withdraw',
    component: WithdrawComponent,
    // canActivate: [AfterLoginService]
  },
  {
    path: 'team-create',
    component: TeamCreateComponent
  },
  {
    path: 'team-create/:matchkey',
    component: TeamCreateComponent
  },

  {
		path: 'team-captain/:teamid',
		component: TeamcaptainComponent
  },

  {
    path: 'playercredits',
    component: PlayerCreditsComponent,
    // canActivate: [AfterLoginService]
  },
  {
    path: 'create-contest',
    component: CreateContestComponent,
    // canActivate: [AfterLoginService]
  },
  {
		path: 'contest-info/:matchkey/:id/:userid',
		component: ContestInfoComponent
  },
  {
		path: 'fantasypoints',
		component: ContestInfoComponent
  },
  {
		path: 'pointsystem',
		component: PointsystemComponent
  },
  {
		path: 'viewteam/:matchkey',
		component: ViewteamComponent
  },
  {
		path: 'editteam/:matchkey/:teamid',
		component: TeameditComponent
  },
  {
		path: 'mycontest/:matchkey',
		component: MycontestComponent
  },

  // {
	// 	path: 'test',
  //   component: TestcomponentComponent
  //   // canActivate: [AfterLoginService]
  // },




];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
