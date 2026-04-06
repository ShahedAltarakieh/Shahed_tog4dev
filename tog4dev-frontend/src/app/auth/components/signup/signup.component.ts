import { Component } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import {Router, RouterLink} from '@angular/router';

import { AuthService } from 'app/auth/services/auth.service';
import { SignupService } from './services/signup.service';

import { BasicInputComponent } from 'app/shared/components/inputs/components/basic-input/basic-input.component';
import { DatepickerComponent } from 'app/shared/components/inputs/components/datepicker/datepicker.component';
import { SelectDropdownComponent } from 'app/shared/components/inputs/components/select-dropdown/select-dropdown.component';

import { LoginResponse, SignupForm } from 'app/auth/types/auth.types';
import { SelectedCountry } from './types/signup.types';
import {TranslatePipe} from "@ngx-translate/core";
import {StorageService} from "../../../core/storage/storage.service";
import {CookieService} from "ngx-cookie-service";
import {
  MobileNumberComponent
} from "../../../shared/components/inputs/components/mobile-number/mobile-number.component";

@Component({
    selector: 'app-signup',
    imports: [
        BasicInputComponent,
        SelectDropdownComponent,
        DatepickerComponent,
        TranslatePipe,
        MobileNumberComponent,
        RouterLink,
    ],
    templateUrl: './signup.component.html',
    styleUrl: './signup.component.scss'
})
export class SignupComponent {
  countries_list: any;
  country_flag: string = "";
  country_selected: SelectedCountry = {} as unknown as SelectedCountry;
  country_name: string = "";
  isLoading = false;
  requestSuccessMsg = '';

  constructor(
      public httpClient: HttpClient,
      public router: Router,
      public signupService: SignupService,
      public storageService: StorageService,
      public authService: AuthService,
      public cookieService: CookieService
    ) {
    this.httpClient.get('/app/assets/json/countries.json').subscribe((res) => {
      this.countries_list = res;
      const country_code = this.cookieService.get('countryCode');
      var map_country_code = "JO";
      if(country_code){
        map_country_code = country_code
      }
      this.country_selected = this.countries_list.find((country: { country_code: string; }) => country.country_code == map_country_code);
      this.country_flag = this.country_selected.flag;
      this.country_name = this.country_selected.country_name_english;
      signupService.signupForm.country.value = this.country_name;
      signupService.signupForm.country.isValid = true;
      signupService.signupForm.phone_code.value = this.country_selected.phone_code;
      signupService.signupForm.phone_code.isValid = true;
    });
  }

  /**
   * Privacy policy check event handler
   * 
   * @param { Event } event 
   */
  onPrivacyPolicyCheck(event: Event) {
    this.signupService.privacyPolicyChecked = (event.target as HTMLInputElement).checked;
  }

  /**
   * Recieve email check event handler
   * 
   * @param { Event } event 
   */
  onReceiveEmailCheck(event: Event) {
    this.signupService.receiveEmailsChecked = (event.target as HTMLInputElement).checked;
  }

  firstNameErrorHandler(errorMsg: string) {
    this.signupService.signupForm.first_name.errorMsg = errorMsg;
    this.signupService.signupForm.first_name.isValid = !errorMsg;
  }

  lastNameErrorHandler(errorMsg: string) {
    this.signupService.signupForm.last_name.errorMsg = errorMsg;
    this.signupService.signupForm.last_name.isValid = !errorMsg;
  }

  emailErrorHandler(errorMsg: string) {
    this.signupService.signupForm.email.errorMsg = errorMsg;
    this.signupService.signupForm.email.isValid = !errorMsg;
  }

  passwordErrorHandler(errorMsg: string) {
    this.signupService.signupForm.password.errorMsg = errorMsg;
    this.signupService.signupForm.password.isValid = !errorMsg;
  }

  organizationNameErrorHandler(errorMsg: string) {
    this.signupService.signupForm.organizationName.errorMsg = errorMsg;
    this.signupService.signupForm.organizationName.isValid = !errorMsg;
  }

  cityErrorHandler(errorMsg: string) {
    this.signupService.signupForm.city.errorMsg = errorMsg;
    this.signupService.signupForm.city.isValid = !errorMsg;
  }

  birthDayErrorHandler(errorMsg: string) {
    this.signupService.signupForm.birthday.errorMsg = errorMsg;
    this.signupService.signupForm.birthday.isValid = !errorMsg;
  }

  countryErrorHandler(errorMsg: string) {
    this.signupService.signupForm.country.errorMsg = errorMsg;
    this.signupService.signupForm.country.isValid = !errorMsg;
  }

  mobileErrorHandler(errorMsg: string) {
    this.signupService.signupForm.phone.errorMsg = errorMsg;
    this.signupService.signupForm.phone.isValid = !errorMsg;
  }

  onFormSubmit() {
    this.isLoading = true;

    this.signupService.registerUser().subscribe({
      next: (value) => {
        this.requestSuccessMsg = 'User Created Successfully!';
        this.isLoading = false;
        this.cookieService.set('user', JSON.stringify(value), 20, "/");
        this.authService.loggedInUser = value;
        this.authService.is_loggedin = true;
        this.router.navigate(['/' + this.storageService.siteLanguage$.value]);
      },
      error: (err => {
        const errorsList: Record<string, string[]> = err.error.errors;

        this.isLoading = false;
        this.requestSuccessMsg = '';

        for (const key in errorsList) {
          this.signupService.signupForm[key as keyof SignupForm].errorMsg = errorsList[key][0];
          this.signupService.signupForm[key as keyof SignupForm].isValid = !errorsList[key][0];
        }
      })
    })
  }
}
