import { Injectable } from '@angular/core'; 

@Injectable({
  providedIn: 'root'
})
export class TokenService {

  private iss = {
    //login : "https://consummo.com/sportztrophyapi/public/api/login",
      login : 'http://192.168.0.9:8000/api/login',

    //register : "private baseUrl = https://consummo.com/sportztrophyapi/public/api/register"
      register : 'http://192.168.0.9:8000/api/register',
  };

  constructor(
  ) { }


  handle(token)
  {
      this.set(token);
  }

  set(token)
  {
      localStorage.setItem('token',token);
  }

  setKyc(data)
  {
    localStorage.setItem('kyc',data);
  }

  setGoogle(data)
  {
    localStorage.setItem('google',data);
  }

  setLanguage()
  {
      localStorage.setItem('language', 'en');
  }

  get()
  {
     if(localStorage.getItem('token'))
     {
         return localStorage.getItem('token');
     }
     return '';
  }

  remove()
  {
     localStorage.removeItem('token');
     localStorage.removeItem('kyc');
     localStorage.removeItem('google');
  }

  isValid()
  {
      const token  = this.get();
      if(token)
      {
        const payload = this.payload(token);

        if(payload)
        {
           return Object.values(this.iss).indexOf(payload.iss) > -1 ? true:false;
        }
      }
      return false;
  }

  payload(token)
  {
      const payload =  token.split('.')[1];
      return this.decode(payload);
  }

  decode(payload)
  {
     return JSON.parse(atob(payload));
  }

  loggedIn()
  {
     return this.isValid();
  }

  getGoogleKey()
  {
     if(localStorage.getItem('google') == '1')
     {
        return true;
     }
     else
     {
       return false;
     }
  }

  getKycKey()
  {
    if(localStorage.getItem('kyc') == '2')
    {
      return true;
    }
    else
    {
      return false;
    }
  }
}
