import { Injectable, Inject } from '@angular/core';
import { Location, DOCUMENT } from '@angular/common';

@Injectable({
  providedIn: 'root'
})
export class HttpsRedirectService {
  constructor(private location: Location,
              @Inject(DOCUMENT) public document: Document) {}

  enforceHttps(): void {
    const currentUrl = this.document.location.href;
    if (this.location.prepareExternalUrl(this.document.location.protocol) !== 'https:') {
      const httpsUrl = currentUrl.replace('http://', 'https://');
      this.document.location.href = httpsUrl;
    }
  }
}
