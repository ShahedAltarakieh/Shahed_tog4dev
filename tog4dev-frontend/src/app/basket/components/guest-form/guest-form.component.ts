import {Component, EventEmitter, Input, Output} from '@angular/core';
import {StorageService} from "../../../core/storage/storage.service";
import {NgIf} from "@angular/common";
import {TranslatePipe} from "@ngx-translate/core";
import {BasicInputComponent} from "../../../shared/components/inputs/components/basic-input/basic-input.component";
import {HttpClient} from "@angular/common/http";
import { Router, RouterLinkWithHref } from "@angular/router";
import {GuestFormService} from "./services/guest-form.service";
import {BasketService} from "../../../shared/services/basket/basket.service";
import {guestForm} from "./types/guest-form.types";
import {CookieService} from "ngx-cookie-service";
import {AuthService} from "../../../auth/services/auth.service";
import {
  MobileNumberComponent
} from "../../../shared/components/inputs/components/mobile-number/mobile-number.component";

@Component({
    selector: 'app-guest-form',
    imports: [
    NgIf,
    TranslatePipe,
    BasicInputComponent,
    MobileNumberComponent,
    RouterLinkWithHref
],
    templateUrl: './guest-form.component.html',
    styleUrl: './guest-form.component.scss'
})
export class GuestFormComponent {
  @Output() userCreated = new EventEmitter<any>(); // Event emitter to notify parent
  @Output() closeModal = new EventEmitter<void>(); // Event emitter to notify parent
  @Input() show!: boolean;
  isLoading = false;
  requestSuccessMsg = '';

  constructor(
      public httpClient: HttpClient,
      public router: Router,
      public cookieService: CookieService,
      public authService: AuthService,
      public basketService: BasketService,
      public GuestFormService: GuestFormService,
      public storageService: StorageService
  ) {
    this.httpClient.get('/app/assets/json/countries.json').subscribe((res) => {
      const countries_list: any = res;
      const country_code = this.cookieService.get('countryCode');
      var map_country_code = "JO";
      if(country_code){
        map_country_code = country_code
      }
      var country_selected = countries_list.find((country: { country_code: string; }) => country.country_code == map_country_code);
        if(!country_selected){
          country_selected = countries_list.find((country: { country_code: string; }) => country.country_code == "JO");
        }
        GuestFormService.guestForm.phone_code.value = country_selected.phone_code;
        GuestFormService.guestForm.phone_code.isValid = true;
      });
  }

  firstNameErrorHandler(errorMsg: string) {
    this.GuestFormService.guestForm.first_name.errorMsg = errorMsg;
    this.GuestFormService.guestForm.first_name.isValid = !errorMsg;
  }

  lastNameErrorHandler(errorMsg: string) {
    this.GuestFormService.guestForm.last_name.errorMsg = errorMsg;
    this.GuestFormService.guestForm.last_name.isValid = !errorMsg;
  }

  emailErrorHandler(errorMsg: string) {
    this.GuestFormService.guestForm.email.errorMsg = errorMsg;
    this.GuestFormService.guestForm.email.isValid = !errorMsg;
  }


  mobileErrorHandler(errorMsg: string) {
    this.GuestFormService.guestForm.phone.errorMsg = errorMsg;
    this.GuestFormService.guestForm.phone.isValid = !errorMsg;
  }

  onFormSubmit() {
    this.isLoading = true;

    this.GuestFormService.createGuestAccount().subscribe({
      next: (value: any) => {
        this.requestSuccessMsg = 'Collection Created Successfully!';
        this.isLoading = false;
        this.show = false;
        this.userCreated.emit({first_name: value.first_name, last_name: value.last_name, email: value.email, phone: value.phone});
      },
      error: (err => {
        const errorsList: Record<string, string[]> = err.error.errors;
        this.isLoading = false;
        this.requestSuccessMsg = '';

        for (const key in errorsList) {
          this.GuestFormService.guestForm[key as keyof guestForm].errorMsg = errorsList[key][0];
          this.GuestFormService.guestForm[key as keyof guestForm].isValid = !errorsList[key][0];
        }
      })
    })
  }

  close_modal(){
    this.closeModal.emit();
  }
}
