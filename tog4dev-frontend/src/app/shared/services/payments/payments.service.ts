import { Injectable } from '@angular/core';
import {HttpClient, HttpParams} from "@angular/common/http";
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class PaymentsService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient) { }

  getPayment(lang: 'ar' | 'en', cart_id: string) {
    return this.httpClient.get<any>(this.apiUrl + 'api/v1/payments/status/'+cart_id, {
      headers: { 'Accept-Language': lang, 'Content-Type': 'application/json' },
      params: {
        '_': new Date().getTime().toString() // Add this to bypass caching
      }
    });
  }
  updatePurchaseOrderMeta(lang: 'ar' | 'en', cart_id: string) {
    return this.httpClient.get<any>(this.apiUrl + 'api/v1/payments/store-meta-purchase/'+cart_id, {
      headers: { 'Accept-Language': lang, 'Content-Type': 'application/json' },
      params: {
        '_': new Date().getTime().toString() // Add this to bypass caching
      }
    });
  }
}
