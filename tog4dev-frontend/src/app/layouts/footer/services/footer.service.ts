import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { NewsLetterResponse } from '../types/footer.types';
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class FooterService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient) { }

  newsLetterResponseMsg = '';

  /**
   * 
   * @param { string } email 
   * @returns 
   */
  postNewsLetter(email: string) {
    return this.httpClient.post<NewsLetterResponse>(this.apiUrl + 'api/v1/newsletter', { email: email } );
  }
}
