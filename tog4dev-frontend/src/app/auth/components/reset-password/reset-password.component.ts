import {Component, OnInit} from '@angular/core';

import { StorageService } from 'app/core/storage/storage.service';

import { BasicInputComponent } from 'app/shared/components/inputs/components/basic-input/basic-input.component';
import {ActivatedRoute, Router} from "@angular/router";
import {LoginService} from "../login/services/login.service";
import {TranslatePipe} from "@ngx-translate/core";
import {ModalComponent} from "../../../shared/components/modal/modal.component";
import {NgClass, NgIf} from "@angular/common";

@Component({
    selector: 'app-reset-password',
    imports: [
        BasicInputComponent,
        TranslatePipe,
        ModalComponent,
        NgIf,
        NgClass,
    ],
    templateUrl: './reset-password.component.html',
    styleUrl: './reset-password.component.scss'
})
export class ResetPasswordComponent implements OnInit{
  resetPasswordForm = {
    email:{
      value: '',
      errorMsg: '',
      isValid: true
    },
    token:{
      value: '',
      errorMsg: '',
      isValid: true
    },
    password: {
      value: '',
      errorMsg: '',
      isValid: false
    },
    confirmPassword: {
      value: '',
      errorMsg: '',
      isValid: false
    },
    isPasswordMatch: false,
  };
  error_message: string = '';
  show_changed_successful: boolean = false;
  isLoading: boolean = false;
  constructor(public storageService: StorageService,
              private route: ActivatedRoute,
              private loginService: LoginService,
              public router: Router,) {}

  ngOnInit() {
    this.route.queryParams.subscribe(params => {
      const email = params['email'];
      if(email){
        this.resetPasswordForm.email.value = email;
      } else {
        this.router.navigate(['/' + this.storageService.siteLanguage$.value]);
      }
    });
    this.route.paramMap.subscribe(params => {
      const token = params.get('token');
      if(token){
        this.resetPasswordForm.token.value = token;
      } else{
        this.router.navigate(['/' + this.storageService.siteLanguage$.value]);
      }
    });
  }

  passwordErrorHandler(errorMsg: string) {
    this.resetPasswordForm.password.errorMsg = errorMsg;
    this.resetPasswordForm.password.isValid = !errorMsg;

    if(this.resetPasswordForm.password.value.length < 8){
      this.resetPasswordForm.isPasswordMatch = false
      this.resetPasswordForm.password.isValid = false;
      this.resetPasswordForm.password.errorMsg = 'password should be more than 8 digits';
    }
    else if (this.resetPasswordForm.password.value === this.resetPasswordForm.confirmPassword.value) {
      this.resetPasswordForm.isPasswordMatch = true
    } else {
      this.resetPasswordForm.isPasswordMatch = false
    }
  }

  confirmPasswordHandler(errorMsg: string) {
    this.resetPasswordForm.confirmPassword.errorMsg = errorMsg;
    this.resetPasswordForm.confirmPassword.isValid = !errorMsg;
    
    if (this.resetPasswordForm.password.value === this.resetPasswordForm.confirmPassword.value) {
      this.resetPasswordForm.isPasswordMatch = true
    } else {
      this.resetPasswordForm.isPasswordMatch = false
    }
  }

  submitReset(){
    this.isLoading = true;
    this.loginService.resetPassword(this.resetPasswordForm).subscribe({
      next: (value) => {
        this.show_changed_successful = true;
        this.resetPasswordForm.password.value = '';
        this.resetPasswordForm.confirmPassword.value = '';
        this.isLoading = false;
      },
      error: (error) => {
        try{
          this.error_message = error.error.message;
          this.isLoading = false;
        } catch (e){
          this.isLoading = false;
        }
      }
    })
  }



  closePopup(value: boolean){
    this.show_changed_successful = false;
    var command = '';
    if(this.storageService.siteLanguage$.value == "ar"){
      command = "/en/login";
    } else {
      command = "/ar/تسجيل-الدخول";
    }
    this.router.navigate([command]);
  }
}
