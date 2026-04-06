import { Injectable } from '@angular/core';

import { Countries } from '../types/mobile-number.types';

@Injectable({
  providedIn: 'root'
})
export class PhoneCountrySanitizerService {
  countriesList: Countries = {
    jordan: '+962',
    usa: '+1'
  };

  isDropdownExpanded = false;

  activeCountry: keyof Countries = 'usa';

  constructor() { }

  sanitizeMobileNumber(event: Event) {

    const inputElement = event.target as HTMLInputElement;
    let mobileNumber = inputElement.value.trim();

    // Remove any non-numeric characters
    mobileNumber = mobileNumber.replace(/\D/g, '');

    const countryCode = this.countriesList[this.activeCountry];

    // If the number already has the country code, don't add it again
    if (!mobileNumber.startsWith(countryCode.replace('+', ''))) {
      // If not, prepend the country code
      mobileNumber = countryCode + mobileNumber;
    }

    // Update the input value with the sanitized number
    inputElement.value = mobileNumber;

  }
}
