import {Component, Input, OnDestroy, OnInit} from '@angular/core';

import { ContactUsFormService } from './services/contact-us-form.service';

import { BasicInputComponent } from '../inputs/components/basic-input/basic-input.component';
import { MobileNumberComponent } from '../inputs/components/mobile-number/mobile-number.component';
import { TextareaComponent } from '../inputs/components/textarea/textarea.component';
import {TranslatePipe} from "@ngx-translate/core";
import {ModalComponent} from "../modal/modal.component";
import {SelectDropdownComponent} from "../inputs/components/select-dropdown/select-dropdown.component";
import {SelectedCountry} from "../../../auth/components/signup/types/signup.types";
import {HttpClient} from "@angular/common/http";
import {CookieService} from "ngx-cookie-service";

@Component({
    selector: 'app-contact-us-form',
    imports: [
        BasicInputComponent,
        MobileNumberComponent,
        TextareaComponent,
        TranslatePipe,
        ModalComponent,
        SelectDropdownComponent,
    ],
    templateUrl: './contact-us-form.component.html',
    styleUrl: './contact-us-form.component.scss'
})
export class ContactUsFormComponent implements OnInit ,OnDestroy {
  @Input() type!: number;
  show_popup: boolean = false;
  countries_list: any;
  country_flag: string = "";
  country_selected: SelectedCountry = {} as unknown as SelectedCountry;
  country_name: string = "";

  constructor(public contactUsFormService: ContactUsFormService,
              public cookieService: CookieService,
              public httpClient: HttpClient) {}

  submitForm() {
    this.contactUsFormService.submitContactUsForm(this.type).subscribe(value => {
      this.contactUsFormService.contactUsForm.firstName.value = '';
      this.contactUsFormService.contactUsForm.lastName.value = '';
      this.contactUsFormService.contactUsForm.country.value = '';
      this.contactUsFormService.contactUsForm.email.value = '';
      this.contactUsFormService.contactUsForm.organizationName.value = '';
      this.contactUsFormService.contactUsForm.phone.value = '';
      this.contactUsFormService.contactUsForm.message.value = '';
      this.show_popup = true;
    });
  }

  closePopup(value: boolean){
    this.show_popup = false;
  }

  firstNameErrorHandler(errorMsg: string) {
    this.contactUsFormService.contactUsForm.firstName.errorMsg = errorMsg;
    this.contactUsFormService.contactUsForm.firstName.isValid = !errorMsg;
  }

  lastNameErrorHandler(errorMsg: string) {
    this.contactUsFormService.contactUsForm.lastName.errorMsg = errorMsg;
    this.contactUsFormService.contactUsForm.lastName.isValid = !errorMsg;
  }

  countryErrorHandler(errorMsg: string) {
    this.contactUsFormService.contactUsForm.country.errorMsg = errorMsg;
    this.contactUsFormService.contactUsForm.country.isValid = !errorMsg;
  }

  organizationErrorHandler(errorMsg: string) {
    this.contactUsFormService.contactUsForm.organizationName.errorMsg = errorMsg;
    this.contactUsFormService.contactUsForm.organizationName.isValid = !!errorMsg;
  }

  emailErrorHandler(errorMsg: string) {
    this.contactUsFormService.contactUsForm.email.errorMsg = errorMsg;
    this.contactUsFormService.contactUsForm.email.isValid = !errorMsg;
  }

  messageErrorHandler(errorMsg: string) {
    this.contactUsFormService.contactUsForm.message.errorMsg = errorMsg;
    this.contactUsFormService.contactUsForm.message.isValid = !errorMsg;
  }

  mobileErrorHandler(errorMsg: string) {
    this.contactUsFormService.contactUsForm.phone.errorMsg = errorMsg;
    this.contactUsFormService.contactUsForm.phone.isValid = !errorMsg;
  }

  organizationNameErrorHandler(errorMsg: string) {
    this.contactUsFormService.contactUsForm.organizationName.errorMsg = errorMsg;
    this.contactUsFormService.contactUsForm.organizationName.isValid = !errorMsg;
  }

  /**
   * Angular onDestory lifecycle method
   */
  ngOnDestroy(): void {
    this.contactUsFormService.resetForm();
  }

  ngOnInit(): void {
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
      this.contactUsFormService.contactUsForm.country.value = this.country_name;
      this.contactUsFormService.contactUsForm.country.isValid = true;
    });
  }
}
