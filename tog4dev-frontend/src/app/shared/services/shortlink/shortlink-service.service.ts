import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { map } from 'rxjs';
import {AuthService} from "../../../auth/services/auth.service";
import {CookieService} from "ngx-cookie-service";
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class ShortlinkService {
  private apiUrl = environment.apiUrl;
  constructor(public apiService: ApiService,
              public authService: AuthService,
              public cookieService: CookieService) { }

  getUrl(code: string) {
    const loggedInUser = this.authService.loggedInUser;

    return this.apiService.get<{ data: any[] }>(this.apiUrl + `api/v1/shortlinks/${code}`, {
      '_': new Date().getTime().toString() // Add this to bypass caching
    }, {
      'Authorization': 'Bearer ' + loggedInUser?.token,
    }, true)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }
}
