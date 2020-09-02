import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { TokenService } from './token.service';
 
@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private loggedIn = new BehaviorSubject < boolean >(this.Token.isValid())

  authStatus = this.loggedIn.asObservable();

  changeStatus(value: boolean){
    this.loggedIn.next(value)
  }
  constructor(private Token:TokenService) { }}
