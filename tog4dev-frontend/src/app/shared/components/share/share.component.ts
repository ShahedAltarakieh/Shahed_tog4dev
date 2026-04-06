import {Component, EventEmitter, Input, OnInit, Output, SimpleChanges} from '@angular/core';

import {TranslatePipe} from "@ngx-translate/core";
import {NgClass, NgIf} from "@angular/common";
import {CookieService} from "ngx-cookie-service";

@Component({
    selector: 'app-share',
    imports: [
        TranslatePipe,
        NgIf,
        NgClass
    ],
    templateUrl: './share.component.html',
    styleUrl: './share.component.scss'
})
export class ShareComponent implements OnInit{
  @Output() valueEmitted = new EventEmitter<boolean>();
  link!: string;
  @Input() title!: string;
  @Input() description!: string;
  @Input() show!: boolean;
  copied: boolean = false;
  @Input() URL!: string;
  
  constructor(private cookieService: CookieService) {}

  ngOnInit() {
  }

  /**
   * Adds t4d query parameter from cookie to URL
   */
  addT4dParam(url: string): string {
    const t4dValue = this.cookieService.get('t4d');
    
    // Only add if cookie exists and URL doesn't already have t4d param
    if (!t4dValue) {
      return url;
    }
    
    try {
      const urlObj = new URL(url);
      // Only add if not already present
      if (!urlObj.searchParams.has('t4d')) {
        urlObj.searchParams.set('t4d', t4dValue);
      }
      return urlObj.toString();
    } catch (error) {
      // If URL parsing fails (relative URL), handle manually
      if (url.includes('t4d=')) {
        return url; // Already has t4d param
      }
      const separator = url.includes('?') ? '&' : '?';
      return `${url}${separator}t4d=${encodeURIComponent(t4dValue)}`;
    }
  }

  copyLink() {
    const urlToCopy = this.addT4dParam(this.URL);
    navigator.clipboard.writeText(urlToCopy).then(() => {
      this.copied = true;
      setTimeout(() => {
        this.copied = false;
      }, 2000);
    }).catch(err => {
    });
  }

  facebookLink(){
    const urlWithParams = this.addT4dParam(this.URL);
    return encodeURIComponent(urlWithParams);
  }

  /**
   * Gets the URL with t4d param for sharing
   */
  getShareUrl(): string {
    return this.addT4dParam(this.URL);
  }

  /**
   * Gets encoded URL with t4d param for sharing
   */
  getEncodedShareUrl(): string {
    return encodeURIComponent(this.getShareUrl());
  }

  /**
   * Gets WhatsApp share URL with text and t4d param
   */
  getWhatsAppUrl(): string {
    const urlWithParams = this.getShareUrl();
    const text = `${this.title}: ${this.description} ${urlWithParams}`;
    return `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
  }

  /**
   * Gets Twitter share URL with t4d param
   */
  getTwitterUrl(): string {
    const urlWithParams = this.getEncodedShareUrl();
    return `https://twitter.com/share?text=${encodeURIComponent(this.title + ': ' + this.description)}&url=${urlWithParams}`;
  }

  /**
   * Gets LinkedIn share URL with t4d param
   */
  getLinkedInUrl(): string {
    const urlWithParams = this.getEncodedShareUrl();
    return `https://www.linkedin.com/sharing/share-offsite/?url=${urlWithParams}&text=${encodeURIComponent(this.title + ': ' + this.description)}`;
  }

  /**
   * Gets the URL to display in copy section with t4d param
   */
  getDisplayUrl(): string {
    return this.addT4dParam(this.URL);
  }

  close(){
    this.valueEmitted.emit(true);
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['URL'] && changes['URL'].currentValue) {
      if(this.URL){
        this.URL = changes['URL'].currentValue;
      }
    }
  }
}
