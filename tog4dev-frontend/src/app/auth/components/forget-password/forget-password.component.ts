import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

import { StorageService } from 'app/core/storage/storage.service';

import { BasicInputComponent } from 'app/shared/components/inputs/components/basic-input/basic-input.component';
import {LoginService} from "../login/services/login.service";
import {TranslatePipe} from "@ngx-translate/core";
import {NgClass, NgIf} from "@angular/common";
import {ModalComponent} from "../../../shared/components/modal/modal.component";

@Component({
    selector: 'app-forget-password',
    imports: [
        BasicInputComponent,
        RouterLink,
        TranslatePipe,
        NgIf,
        ModalComponent,
        NgClass,
    ],
    templateUrl: './forget-password.component.html',
    styleUrl: './forget-password.component.scss'
})
export class ForgetPasswordComponent {
  forgetPasswordForm = {
    email: {
      value: '',
      errorMsg: '',
      isValid: false
    }
  };
  show_success_popup: boolean = false;
  isLoading: boolean = false;
  loginRoutes: Record<string, string> = {
    'ar': '/ar/تسجيل-الدخول',
    'en': '/en/login',
  };

  constructor(public storageService: StorageService, public loginService: LoginService) {}

  emailErrorHandler(errorMsg: string) {
    this.forgetPasswordForm.email.errorMsg = errorMsg;
    this.forgetPasswordForm.email.isValid = !errorMsg;
  }

  send_reset(){
    this.isLoading = true;
    this.loginService.forgetPassword(this.forgetPasswordForm.email.value).subscribe({
      next: (value) => {
        this.show_success_popup = true;
        this.isLoading = false;
      },
      error: (error) => {
        this.isLoading = false;
      }
    })
  }

  closePopup(value: boolean){
    this.show_success_popup = false;
  }
}
