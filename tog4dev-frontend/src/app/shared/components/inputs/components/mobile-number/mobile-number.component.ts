import { Component, ElementRef, EventEmitter, HostListener, Input, Output } from '@angular/core';
import {LowerCasePipe, NgClass, NgForOf} from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { PhoneCountrySanitizerService } from './services/phone-country-sanitizer.service';

import { AutoFillDirective } from 'app/shared/directives/auto-fill.directive';
import { Countries } from './types/mobile-number.types';
import {FormsModule} from "@angular/forms";
import {TranslatePipe} from "@ngx-translate/core";
import {CookieService} from "ngx-cookie-service";
import {StorageService} from "../../../../../core/storage/storage.service";

@Component({
    selector: 'app-mobile-number',
    imports: [
        NgClass,
        AutoFillDirective,
        FormsModule,
        NgForOf,
        TranslatePipe
    ],
    templateUrl: './mobile-number.component.html',
    styleUrls: ['../basic-input/basic-input.component.scss', './mobile-number.component.scss']
})
export class MobileNumberComponent {
  @Input({ required: true }) value = '';
  @Input({ required: true }) errorMsg = '';
  @Input() isRequired = false;
  jsonDataResult: any;
  @Output() valueChanged: EventEmitter<string> = new EventEmitter<string>();
  @Output() phoneCodeChanged: EventEmitter<string> = new EventEmitter<string>();
  @Output() errorChanged: EventEmitter<string> = new EventEmitter<string>();

  @HostListener('document:click', ['$event.target'])
  onOutsideClick(target: HTMLElement) {
    if (!(this.elementRef.nativeElement as HTMLElement).contains(target)) {
      this.phoneCountrySanitizer.isDropdownExpanded = false;
    }
  }

  country_selected: any;
  country_flag: string = "";
  placeholder: string = "";
  searchQuery: string = '';
  phone_code: string = '';
  @Input() additionalClass: string = '';

  constructor(
    public phoneCountrySanitizer: PhoneCountrySanitizerService,
    public elementRef: ElementRef,
    public httpClient: HttpClient,
    public cookieService: CookieService,
    public storageService: StorageService,
  ) {
    this.httpClient.get('/app/assets/json/countries.json').subscribe((res) => {
      this.jsonDataResult = res;
      const country_code = this.cookieService.get('countryCode');
      var map_country_code = "JO";
      if(country_code){
        map_country_code = country_code
      }     
      this.country_selected = this.jsonDataResult.find((country: { country_code: string; }) => country.country_code == map_country_code);
      if(!this.country_selected){
        this.country_selected = this.jsonDataResult.find((country: { country_code: string; }) => country.country_code == "JO");
      }
      this.placeholder = this.country_selected.phone_placeholder;
      this.country_flag = this.country_selected.flag;
      this.phone_code = this.country_selected.phone_code;
    });
  }

  /**
   * Mobile number input change handler
   * 
   * @param event 
   */
  onInputChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;

    if (value.length > 10) {
      (event.target as HTMLInputElement).value = value.slice(0, 10);
    }
    value = (event.target as HTMLInputElement).value;

    // Allow only numbers
    value = value.replace(/[^0-9]/g, '');
    this.value = value;
    this.valueChanged.next(value);
    // Validate input length
    if (!value && this.isRequired) {
      this.errorChanged.next('empty error');
    } else if (value.length < 9) {
      this.errorChanged.next('minimum of 9 digits is required');
    } else if (value.length > 10) {
      this.errorChanged.next('maximum 10 digits allowed');
    } else {
      this.errorChanged.next('');
    }
  }

  onPhoneCountryClick(event: Event) {
    event.stopPropagation();
    this.phoneCountrySanitizer.isDropdownExpanded = !this.phoneCountrySanitizer.isDropdownExpanded;
  }

  onCountryClick(event: Event, country: any) {
    this.country_flag = country.flag;
    this.placeholder = country.phone_placeholder;
    this.phoneCountrySanitizer.activeCountry = country;
    this.phoneCountrySanitizer.isDropdownExpanded = false;
    this.phone_code = country.phone_code;
    this.phoneCodeChanged.next(this.phone_code);
  }

  filteredCountries() {
    if(this.storageService.siteLanguage$.value === 'ar'){
      return this.jsonDataResult.filter((item: { country_name_arabic: string; }) =>
          item.country_name_arabic.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    } else {
      return this.jsonDataResult.filter((item: { country_name_english: string; }) =>
          item.country_name_english.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    }
  }
}
