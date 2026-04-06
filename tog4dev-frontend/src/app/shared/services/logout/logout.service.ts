import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {AuthService} from "../../../auth/services/auth.service";
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class LogoutService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient, public authService: AuthService) { }

  logout() {
    const loggedInUser = this.authService.loggedInUser;
    return this.httpClient.post(this.apiUrl + 'api/v1/logout', null, {
      headers: { 'Authorization': 'Bearer ' + loggedInUser?.token, 'Content-Type': 'application/json' },
      withCredentials: true
    });
  }
}
