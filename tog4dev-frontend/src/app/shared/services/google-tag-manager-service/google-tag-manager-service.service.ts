import { Injectable } from '@angular/core';
import { environment } from 'environments/environment';

@Injectable({
  providedIn: 'root'
})
export class GoogleTagManagerService {
  private gtmId = environment.gtmId;

  constructor() {}

  public pushEvent(event: any) {    
    if ((window as any).dataLayer) {
      (window as any).dataLayer.push(event);
    } else {
      console.warn('GTM dataLayer not found');
    }
  }

}
