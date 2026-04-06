import {Component, ElementRef, EventEmitter, Input, Output, ViewChild} from '@angular/core';
import {StorageService} from "../../../core/storage/storage.service";
import {NgFor, NgIf} from "@angular/common";
import {TranslatePipe} from "@ngx-translate/core";
import {BasicInputComponent} from "../../../shared/components/inputs/components/basic-input/basic-input.component";
import {HttpClient} from "@angular/common/http";
import {Router} from "@angular/router";
import { OrangeMoneyService } from './services/orange-money.service';
import {BasketService} from "../../../shared/services/basket/basket.service";
import { CookieService } from "ngx-cookie-service";
import { orangeMoneyForm } from './types/orange-money.types';
import { FormsModule } from '@angular/forms';

@Component({
    selector: 'app-orange-money',
    imports: [
        NgIf,
        TranslatePipe,
        BasicInputComponent,
        FormsModule
    ],
    templateUrl: './orange-money.component.html',
    styleUrl: './orange-money.component.scss'
})
export class OrangeMoneyComponent {
  @ViewChild('firstOtpInput') firstOtpInput!: ElementRef<HTMLInputElement>;
  @Output() itemRemoved = new EventEmitter<void>(); // Event emitter to notify parent
  @Output() closeModal = new EventEmitter<void>(); // Event emitter to notify parent
  @Input() show!: boolean;
  @Input()userData?: any;
  isLoading = false;
  requestSuccessMsg = '';
  error_message = '';
  success_response: any;
  show_otp: boolean = false;
  otp1: string = '';
  otp2: string = '';
  otp3: string = '';
  otp4: string = '';

  constructor(
      public httpClient: HttpClient,
      public router: Router,
      public basketService: BasketService,
      public orangeMoneyService: OrangeMoneyService,
      public storageService: StorageService,
      private cookieService: CookieService) {
  }

  cliqNumberErrorHandler(errorMsg: string) {
    this.error_message = '';
    this.orangeMoneyService.orangeMoneyForm.cliq_number.errorMsg = errorMsg;
    this.orangeMoneyService.orangeMoneyForm.cliq_number.isValid = !errorMsg;
  }

  onFormSubmit() {
    this.error_message = '';
    this.isLoading = true;    
    this.orangeMoneyService.send_verification_code(this.userData).subscribe({
      next: (value: any) => {        
        this.isLoading = false;
        if(value.success == true){
          this.success_response = value;
          this.show_otp = true;
          setTimeout(() => {
            this.firstOtpInput.nativeElement.focus();
          }, 500);
        }
      },
      error: (err => {    
        this.isLoading = false;    
        if(err.status == 422){
          this.error_message = err.error.message;
        }
      })
    })
  }

  generateSessionID() {
    const unique_id = crypto.randomUUID();
    this.cookieService.set('session_id', unique_id, 20, "/");
  }

  close_modal(){
    this.closeModal.emit();
  }

  moveToNext(event: Event, nextId: string): void {
    const input = event.target as HTMLInputElement;

    // Accept only English digits (0–9), ignore Arabic/Indic digits
    const value = input.value.replace(/[^0-9]/g, '').slice(0, 1);

    input.value = value;

    if (value && nextId) {
      const nextInput = document.getElementById(nextId) as HTMLInputElement;
      nextInput?.focus();
    }
  }

  moveToPrev(event: Event, prevId: string): void {
    const input = event.target as HTMLInputElement;

    if (!input.value && prevId) {
      const prevInput = document.getElementById(prevId) as HTMLInputElement;
      prevInput?.focus();
      event.preventDefault();
    }
  }

  getOtpValue(): string {
    return this.otp1 + this.otp2 + this.otp3 + this.otp4;
  }

  completeOrder() {
    this.error_message = '';
    this.isLoading = true;
    this.orangeMoneyService.complete_order(this.getOtpValue(), this.success_response.id).subscribe({
      next: (value: any) => {        
        this.isLoading = false;
        if(value.success == true){
          const cart_id = value.cart_id ?? '';
          this.basketService.quantity = 0;
          this.cookieService.delete('session_id');
          this.generateSessionID();
          this.router.navigate(['/' + this.storageService.siteLanguage$.value], { queryParams: { payment: 'success', cart_id: cart_id}});
        }
      },
      error: (err => {
        this.isLoading = false;
        if(err.status == 422){
          this.error_message = err.error.message;
        }
      })
    })
  }
}
