import { Injectable } from '@angular/core';
import { HttpInterceptor } from '@angular/common/http';
import { TokenService } from '../services/token.service';

@Injectable({
providedIn: 'root'
})
export class AuthInterceptorService implements HttpInterceptor {
    
intercept(req: import("@angular/common/http").HttpRequest<any>, next: import("@angular/common/http").HttpHandler): import("rxjs").Observable<import("@angular/common/http").HttpEvent<any>> {
const token = this.Token.get(); 
if(token){
const cloned =req.clone({
headers:req.headers.set("Authorization","Bearer "+token)
});
return next.handle(cloned);
} else {
return next.handle(req);
} 
}

constructor(private Token: TokenService) { }
}