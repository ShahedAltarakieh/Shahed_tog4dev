import {Component, Input, OnInit, SimpleChanges} from '@angular/core';
import {Category} from "../../../shared/services/types/categories.types";
import {Contribution} from "../../../shared/services/types/QuickContributions.types";
import {NgClass, NgIf} from '@angular/common';
import {CartService} from "../../../shared/services/cart/cart.service";
import {BasketService} from "../../../shared/services/basket/basket.service";
import {TranslatePipe, TranslateService} from "@ngx-translate/core";
import {StorageService} from "../../../core/storage/storage.service";
import {ModalComponent} from "../../../shared/components/modal/modal.component";
import {ShareComponent} from "../../../shared/components/share/share.component";
import {ConvertCurrencyService} from "../../../shared/services/convert-currency/convert-currency.service";
import {CookieService} from "ngx-cookie-service";
import { MetaPixelService } from 'app/shared/services/meta-pixel-service/meta-pixel.service';
import { CelebrationsService } from 'app/shared/services/celebrations/celebrations.service';

@Component({
    selector: 'app-hero-section',
    imports: [
        NgClass,
        NgIf,
        TranslatePipe,
        ModalComponent,
        ShareComponent
    ],
    templateUrl: './hero-section.component.html',
    styleUrl: './hero-section.component.scss'
})
export class HeroSectionComponent implements OnInit{
  @Input() details!: Category;
  @Input() quickContribution!: Contribution;

  show_share = false;
  type: string | null = null;
  amount: number = 0;
  contributeOptionIndex = -1;
  customAmount: string = '';
  show_add_popup: boolean = false;
  type_error: boolean = false;
  amount_error: boolean = false;
  currency: string = '';
  converted_price:number = 0;
  converted_currency_label:string = '';
  middle_amount: number = 0;
  url: string = '';
  calculatedPrice: string = '';

  constructor(
      public cartService: CartService,
      public storageService: StorageService,
      public translate: TranslateService,
      public cookieService: CookieService,
      public convertedCurrency: ConvertCurrencyService,
      private pixel: MetaPixelService,
      public basketService: BasketService,
      private celebrations: CelebrationsService){
    translate.get("JOD").subscribe((translation: string) => {
      this.currency = translation;
    });
    this.converted_currency_label = this.cookieService.get("currency");
  }

  ngOnInit() {
  }

  setContributeValue(value: number, index: number) {
    this.amount_error = false;
    this.customAmount = '';
    this.amount = value;
    this.contributeOptionIndex = index;
    this.getConvertedPrice(this.amount);
    this.getCalculatedPercantage();
  };

  enableCustomInput(index: number) {
    this.contributeOptionIndex = index;
  };

  setType(type: string) {
    this.type_error = false;
    this.type = type;
  };

  onAmountChange(event: Event) {
    this.amount_error = false;
    this.contributeOptionIndex = -1;
    const inputElement = event.target as HTMLInputElement;

    // Remove non-numeric characters and keep only digits
    const numericValue = inputElement.value.replace(/[^0-9]/g, '').slice(0, 6);
    // Update the model with the sanitized value and add `$` prefix
    if(numericValue){
      inputElement.value = `${numericValue}`;
    } else {
      inputElement.value = '';
    }
    this.amount = numericValue ? parseInt(numericValue) : 0;
    this.customAmount = inputElement.value = this.currency + numericValue;
    if(this.amount == 0){
      this.getConvertedPrice(this.middle_amount);
    }
    else{
      this.getConvertedPrice(this.amount);
    }
    if(numericValue == ''){
      inputElement.value = '';
      this.customAmount = '';
    }
    if(this.amount > 0 && this.amount < 5){
      this.amount_error = true;
    }
    this.getCalculatedPercantage();
  }

  submitContribute(event?: MouseEvent){
    const formData: any = {
      type: this.type,
      price: this.amount,
      item_id: this.quickContribution.id,
      option_id: null,
      model: 'QuickContribute',
      temp_id: this.cookieService.get('session_id')
    };
    if(this.type == '' || this.type == null){
      this.type_error = true;
      return;
    }
    if(this.amount < 5){
      this.amount_error = true;
      return;
    }
    else {
      if (event) {
        this.celebrations.spawnHearts(event);
      }
      this.cartService.addToCart(formData).subscribe({
        next: (value:any) => {
          this.basketService.quantity = value.count;
          this.show_add_popup = true;
          this.pixel.trackAddToCartProduct(
            {
              productId:  this.quickContribution.id,
              name: this.quickContribution.title,
              category: this.details.title,
              price: this.amount,
              quantity: 1
            },
            {
              type: "Individual Quick",
              payment_type: this.type,
            }
          );
          this.resetForm();
          setTimeout(() => {
            this.show_add_popup = false;
          }, 3000);
        },
        error: () => {
          // alert("there are error");
        }
      });
    }
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['quickContribution'] && changes['quickContribution'].currentValue) {
      this.contributeOptionIndex = -1;
      this.type = null;
      this.customAmount = '';
      this.amount = 0;
      if(this.quickContribution){
        this.middle_amount = this.quickContribution.price_list[1];
        this.getConvertedPrice(this.middle_amount);
        this.url = window.location.href;
        this.pixel.trackViewContentProduct(
          {
            productId: this.quickContribution.id,
            name: this.quickContribution.title,
            category: this.details.title,
            price: this.middle_amount
          },
          {
            type: "Individual Quick"
          }
        );
      }
    }
  }

  closePopup(value: boolean){
    this.show_add_popup = false;
    this.show_share = false;
  }

  resetForm(){
    this.type = null;
    this.amount = 0;
    this.contributeOptionIndex = -1;
    this.customAmount = '';
    this.calculatedPrice = '';
    this.getConvertedPrice(this.middle_amount);
  }

  getConvertedPrice(amount: number){
    if(amount != 0){
      const price = this.convertedCurrency.getConverted(amount);
      if(price){
        this.converted_price = price;
      }
    } else {
      this.converted_price = 0;
    }
  }

  getCalculatedPercantage(){
    if(this.quickContribution.single_price != null && this.amount > 0){
      const items = this.amount / this.quickContribution.single_price;
      this.calculatedPrice = Number.isInteger(items) ? `${items}`: `${items.toFixed(2)}`;
    } else {
      this.calculatedPrice = '';
    }
  }
}