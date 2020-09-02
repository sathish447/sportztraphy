import { Injectable } from '@angular/core';
import { CanActivate } from '@angular/router';
import { TokenService } from './token.service';
import { ActivatedRouteSnapshot, RouterStateSnapshot, Router} from '@angular/router';
import { Observable } from 'rxjs/internal/Observable';  
import { CommonService } from './common.service';

@Injectable({
  providedIn: 'root'
})
export class SecurityService implements CanActivate{

  path: import('@angular/router').ActivatedRouteSnapshot[];
  route: import('@angular/router').ActivatedRouteSnapshot; 
  
  googleStatus:any = 0;
  kyc:any = 0;

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean | Observable<boolean> | Promise<boolean> {
    if(this.Token.loggedIn())
    {
      this.Common.setValues();
      if(this.Token.getGoogleKey())
      {
        return true;
      }
      else
      {
        this.Router.navigateByUrl('/googletwofa');
      } 
    }
    else
    {
      return true;
    }
  }

  constructor(
    private Token:TokenService,
    private Common:CommonService,
    private Router:Router
    ) { }

   
}