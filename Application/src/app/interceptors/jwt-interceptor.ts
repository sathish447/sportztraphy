import 'rxjs/add/operator/do';
import { HttpInterceptor, HttpRequest, HttpHandler, HttpEvent, HttpResponse, HttpErrorResponse } from '@angular/common/http';
import { AuthService } from '../services/auth.service';
import { Observable } from 'rxjs';
import { CommonService } from '../services/common.service';

export class JwtInterceptor implements HttpInterceptor {
  constructor(
      public auth: AuthService,
      private Common: CommonService
      ) {}
  intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    return next.handle(request).do((event: HttpEvent<any>) => {
      if (event instanceof HttpResponse) {
      }
    }, (err: any) => {  
      if (err instanceof HttpErrorResponse) {
        //if (err.error.message === "Unauthenticated.") {
          console.log('session out');
          this.Common.logout();
       // }
      }
    });
  }
}