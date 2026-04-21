import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { map, Observable, of } from 'rxjs';
import {HttpClient} from "@angular/common/http";
import {AuthService} from "../../../auth/services/auth.service";
import {CookieService} from "ngx-cookie-service";
import {StorageService} from "../../../core/storage/storage.service";
import { environment } from 'environments/environment';

@Injectable({
  providedIn: 'root'
})
export class CartService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient,
              public apiService: ApiService,
              public authService: AuthService,
              public cookieService: CookieService,
              public storageService: StorageService) {
  }

  addToCart(data: any): Observable<any> {
    if(!data.temp_id){
      this.generateSessionID();
      data.temp_id = this.cookieService.get("session_id");
    }
    if(!data.temp_id){
      window.location.reload();
      return of(null); // Return an Observable for consistency
    }
    const loggedInUser = this.authService.loggedInUser;
    return this.httpClient.post(this.apiUrl + 'api/v1/cart/add', data, {
      headers: { 'Accept-Language': this.storageService.siteLanguage$.value, 'Authorization': 'Bearer ' + loggedInUser?.token, 'Content-Type': 'application/json' },
      withCredentials: true
    });
  }

  submitPayment(lang: string, userData: any = null){
    const loggedInUser = this.authService.loggedInUser;

    return this.httpClient.post(this.apiUrl + 'api/v1/payment/create', {
      "code": this.cookieService.get('t4d') ?? null,
      "first_name" : (userData && userData.first_name) ? userData.first_name : loggedInUser.user.first_name,
      "last_name" : (userData && userData.last_name) ? userData.last_name : loggedInUser.user.last_name,
      "email": (userData && userData.email) ? userData.email : loggedInUser.user.email,
      "phone_number": (userData && userData.phone) ? userData.phone :loggedInUser.user.phone,
      temp_id: this.cookieService.get('session_id') ?? '',
    }, {
      headers: { 'Accept-Language': lang, 'Authorization': 'Bearer ' + loggedInUser?.token, 'Content-Type': 'application/json' },
      withCredentials: true
    });
  }

  removeFromCart(lang: string, id: number){
    const loggedInUser = this.authService.loggedInUser;
    return this.httpClient.delete(this.apiUrl + 'api/v1/cart/remove/'+id, {
      headers: { 'Accept-Language': lang, 'Authorization': 'Bearer ' + loggedInUser?.token, 'Content-Type': 'application/json' },
      withCredentials: true
    });
  }

  getCart(lang: string){
    const loggedInUser = this.authService.loggedInUser;
    const additionalHeaders = {
      'Authorization': 'Bearer ' + loggedInUser?.token,
      'Accept-Language': lang,
    };

    return this.apiService.get<{ data: any }>(this.apiUrl + 'api/v1/cart', {
      temp_id: this.cookieService.get('session_id') ?? '',
      country_code: this.cookieService.get('countryCode') ?? '',
      '_': new Date().getTime().toString()
    }, additionalHeaders, true)
        .pipe(map(this.apiService.extractTypeFromMessage));
  }

  /**
   * Store dedication names (and optional dedication phrase ids) for cart items that have has_beneficiary.
   * Payload: { items: [ { cart_item_id: number, names: string[], dedication_phrase_ids?: number[] } ] }
   */
  storeDedicationNames(lang: string, payload: { items: { cart_item_id: number; names: string[]; dedication_phrase_ids?: number[] }[] }): Observable<any> {
    const loggedInUser = this.authService.loggedInUser;
    return this.httpClient.post(
      this.apiUrl + 'api/v1/cart/store-dedication-names',
      payload,
      {
        headers: {
          'Accept-Language': lang,
          'Authorization': 'Bearer ' + (loggedInUser?.token ?? ''),
          'Content-Type': 'application/json',
        },
        withCredentials: true,
      }
    );
  }

  generateSessionID() {
    const unique_id = crypto.randomUUID();
    this.cookieService.set('session_id', unique_id, 20, "/");
  }
}
