import { Injectable } from '@angular/core';
import {CookieService} from "ngx-cookie-service";
@Injectable({
  providedIn: 'root'
})
export class ConvertCurrencyService {

  constructor(public cookieService: CookieService) { }

  getConverted(price: number): number | null {
    if(this.cookieService.get('currency') == "JOD"){
      return null;
    } else {
      const rate = this.cookieService.get('rate');
      if(rate){
        return parseFloat((price * parseFloat(rate)).toFixed(1));
      }
      return null;
    }
  }
}
