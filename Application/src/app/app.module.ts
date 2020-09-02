import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { InnerHeaderComponent } from './inner-header/inner-header.component';
import { InnerFooterComponent } from './inner-footer/inner-footer.component';
import { AllMatchesComponent } from './components/all-matches/all-matches.component';
import { ContestsComponent } from './components/contests/contests.component';
import { ModalModule } from 'ngx-bootstrap/modal';
import { LoginComponent } from './components/login/login.component';
import { HeaderComponent } from './header/header.component';
import { FooterComponent } from './footer/footer.component';
import { OtpComponent } from './components/otp/otp.component';
import { RegisterComponent } from './components/register/register.component';
import { SocialloginComponent } from './components/sociallogin/sociallogin.component';
import { ToastrModule } from 'ngx-toastr';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ForgotpasswordComponent } from './components/forgotpassword/forgotpassword.component';
import { MobileNumberComponent } from './components/mobile-number/mobile-number.component';
import { ResetpasswordComponent } from './components/resetpassword/resetpassword.component';
import { PasswordComponent } from './components/password/password.component';
import { MyaccountComponent } from './components/myaccount/myaccount.component';
import { AuthInterceptorService } from './interceptors/auth-interceptor';
import { JwtInterceptor } from './interceptors/jwt-interceptor';
import { DatepickerModule, BsDatepickerModule } from 'ngx-bootstrap/datepicker';
import { AccountVerifyComponent } from './components/account-verify/account-verify.component';
import { ChangepasswordComponent } from './components/changepassword/changepassword.component';
import { PointsystemComponent } from './components/pointsystem/pointsystem.component';

import { PersonalInfoComponent } from './components/personal-info/personal-info.component';
import { PaymentCashFreeComponent } from './components/payment-cash-free/payment-cash-free.component';
import { TransactionsComponent } from './components/transactions/transactions.component';
import { WithdrawComponent } from './components/withdraw/withdraw.component';
import { PlayerCreditsComponent } from './components/player-credits/player-credits.component';
import { TeamCreateComponent } from './components/team-create/team-create.component';
import { CreateContestComponent } from './components/create-contest/create-contest.component';
import { ContestInfoComponent } from './components/contest-info/contest-info.component';
import { TeamcaptainComponent } from './components/teamcaptain/teamcaptain.component';
import { MymatchComponent } from './components/mymatch/mymatch.component';
import { NgxUiLoaderModule, NgxUiLoaderConfig, SPINNER, POSITION, PB_DIRECTION } from 'ngx-ui-loader';
import { ViewteamComponent } from './components/viewteam/viewteam.component';
import { TeampreviewComponent } from './components/teampreview/teampreview.component';
import { TeameditComponent } from './components/teamedit/teamedit.component';
import { MycontestComponent } from './components/mycontest/mycontest.component';
import { NgxPaginationModule } from 'ngx-pagination';
import { MyrankComponent } from './components/myrank/myrank.component';
import { InfiniteScrollModule } from 'ngx-infinite-scroll';
import { NgxSpinnerModule } from 'ngx-spinner';
import { CountdownModule } from 'ngx-countdown';

// <-- import the module

// import { TestcomponentComponent } from './components/testcomponent/testcomponent.component';

// import { LefMenuComponent } from './lef-menu/lef-menu.component';

const ngxUiLoaderConfig: NgxUiLoaderConfig = {

  "bgsColor": "#ff7c16",
  "bgsOpacity": 0.5,
  "bgsPosition": "center-center",
  "bgsSize": 60,
  "bgsType": "ball-spin-clockwise",
  "blur": 5,
  "delay": 0,
  "fgsColor": "#fff",
  "fgsPosition": "center-center",
  "fgsSize": 110,
  "fgsType": "circle",
  "gap": 24,
  "logoPosition": "center-center",
  "logoSize": 60,
  "logoUrl": "/assets/images/favicon.svg",
  "masterLoaderId": "master",
  "overlayBorderRadius": "0",
  "overlayColor": "rgba(10, 4, 28, 0.82)",
  "pbColor": "#ff7c16",
  "pbDirection": "ltr",
  "pbThickness": 3,
  "hasProgressBar": true,
  "text": "",
  "textColor": "#FFFFFF",
  "textPosition": "center-center",
  "maxTime": -1,
  "minTime": 500
};

@NgModule({
  declarations: [
    // InfiniteScrollModule,
    AppComponent,
    InnerHeaderComponent,
    InnerFooterComponent,
    AllMatchesComponent,
    ContestsComponent,
    RegisterComponent,
    LoginComponent,
    HeaderComponent,
    FooterComponent,
    OtpComponent,
    SocialloginComponent,
    ForgotpasswordComponent,
    MobileNumberComponent,
    ResetpasswordComponent,
    PasswordComponent,
    MyaccountComponent,
    AccountVerifyComponent,
    ChangepasswordComponent,
    PersonalInfoComponent,
    PaymentCashFreeComponent,
    TransactionsComponent,
    WithdrawComponent,
    PlayerCreditsComponent,
    TeamCreateComponent,
    CreateContestComponent,
    ContestInfoComponent,
    TeamcaptainComponent,
    MymatchComponent,
    ViewteamComponent,
    TeampreviewComponent,
    TeameditComponent,
    MycontestComponent,
    MyrankComponent,
    PointsystemComponent,

    // TestcomponentComponent

  ],
  imports: [
    CountdownModule,
    NgxSpinnerModule,
    InfiniteScrollModule,
    BrowserModule,
    NgxPaginationModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    BrowserAnimationsModule,
    ToastrModule.forRoot(),
    ModalModule.forRoot(),
    // NgbModule,
    BsDatepickerModule.forRoot(),
    DatepickerModule.forRoot(),
    NgxUiLoaderModule.forRoot(ngxUiLoaderConfig),
  ],
  providers: [
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptorService,
      multi: true,
    }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
