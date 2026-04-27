import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {ApiService} from "../../../core/api/api.service";
import {AuthService} from "../../../auth/services/auth.service";
import {StorageService} from "../../../core/storage/storage.service";
import {map} from "rxjs";
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class MyDonationService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient,
              public apiService: ApiService,
              public authService: AuthService,
              public storageService: StorageService) { }

  getList(lang: 'ar' | 'en'){
    const loggedInUser = this.authService.loggedInUser;
    const additionalHeaders = {
      'Authorization': 'Bearer ' + loggedInUser?.token,
      'Accept-Language': lang,
    };

    return this.apiService.get<{ data: any }>(this.apiUrl + 'api/v1/payment/history/subscriptions', {
      '_': new Date().getTime().toString()
    }, additionalHeaders, true)
        .pipe(map(this.apiService.extractTypeFromMessage));
  }

  unsubscribePayment(lang: 'ar' | 'en', id: number){
    const loggedInUser = this.authService.loggedInUser;

    return this.httpClient.put(this.apiUrl + 'api/v1/subscriptions/unsubscribe/' + id, {}, {
      headers: { 'Accept-Language': lang, 'Authorization': 'Bearer ' + loggedInUser?.token, 'Content-Type': 'application/json' },
      withCredentials: true
    });
  }
}
