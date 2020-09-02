import { Injectable } from '@angular/core';
import { CanActivate } from '@angular/router';
import { ActivatedRouteSnapshot, RouterStateSnapshot, Router } from '@angular/router';
import { Observable } from 'rxjs';
import { TokenService } from './token.service';

@Injectable({
  providedIn: 'root'
})
export class BeforeLoginService implements CanActivate{
  
  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean | Observable<boolean> | Promise<boolean> {
    if(!this.Token.loggedIn())
    { 
      return true;
    } 
    else
    {
      this.router.navigateByUrl('/login');
      return false;
    }
  }
  // tslint:disable-next-line: member-ordering
  path: import('@angular/router').ActivatedRouteSnapshot[];
  // tslint:disable-next-line: member-ordering
  route: import('@angular/router').ActivatedRouteSnapshot;

  constructor(private Token: TokenService, private router: Router) { }
}
