import { Injectable } from '@angular/core';
import { CanActivate } from '@angular/router';
import { TokenService } from './token.service';
import { ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs/internal/Observable';

@Injectable({
  providedIn: 'root'
})
export class AfterLoginService implements CanActivate {


  constructor(
    private Token: TokenService
  ) { }
  // tslint:disable-next-line: member-ordering
  path: import('@angular/router').ActivatedRouteSnapshot[];
  // tslint:disable-next-line: member-ordering
  route: import('@angular/router').ActivatedRouteSnapshot;

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean | Observable<boolean> | Promise<boolean> {
    return this.Token.loggedIn();
  }
}
