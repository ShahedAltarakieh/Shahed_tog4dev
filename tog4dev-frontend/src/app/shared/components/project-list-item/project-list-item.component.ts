import {DecimalPipe, NgClass, NgForOf, NgIf} from '@angular/common';
import {Component, Input, OnDestroy, OnInit} from '@angular/core';
import { ProjectItem } from './types/project-list-item.types';
import {CartService} from "../../services/cart/cart.service";
import {Router, RouterLink} from "@angular/router";
import {BasketService} from "../../services/basket/basket.service";
import {FormsModule} from "@angular/forms";
import {TranslatePipe, TranslateService} from "@ngx-translate/core";
import {StorageService} from "../../../core/storage/storage.service";
import {ModalComponent} from "../modal/modal.component";
import {Subject, Subscription} from "rxjs";
import {ShareComponent} from "../share/share.component";
import {ConvertCurrencyService} from "../../services/convert-currency/convert-currency.service";
import {CookieService} from "ngx-cookie-service";
import { MetaPixelService } from 'app/shared/services/meta-pixel-service/meta-pixel.service';
import { CelebrationsService } from 'app/shared/services/celebrations/celebrations.service';

@Component({
    selector: 'app-project-list-item',
    imports: [
        NgClass,
        NgIf,
        NgForOf,
        DecimalPipe,
        FormsModule,
        TranslatePipe,
        RouterLink,
        ModalComponent,
        ShareComponent
    ],
    templateUrl: './project-list-item.component.html',
    styleUrl: './project-list-item.component.scss'
})
export class ProjectListItemComponent implements OnInit, OnDestroy{
  @Input({ required: true }) project: ProjectItem = {} as unknown as ProjectItem;

  detailsProjectsRoutes: Record<'ar' | 'en' , string> = {
    ar: '/ar/المشاريع-الفردية/',
    en: '/en/individual-projects/'
  };
  detailsOrganizationsRoutes: Record<'ar' | 'en' , string> = {
    ar: '/ar/مشاريع-المنظمات/',
    en: '/en/organizations-projects/'
  };
  detailsCrowdFundingsRoutes: Record<'ar' | 'en' , string> = {
    ar: '/ar/التمويل-الجماعي/',
    en: '/en/crowdfunding/'
  };

  show_share = false;
  destory$ = new Subject<void>;
  type: string | null = null;
  amount: number = 0;
  total_price: number = 0;
  single_price: number = 0;
  contributeOptionIndex = -1;
  customAmount: string = '';
  default_quantity = 1;
  contributeValue = 0;
  selected_option: number = 0;
  options: any;
  dropdown1: string[] = [];
  dropdown2: string[] = [];
  selected_d1: string = '';
  selected_d2: string = '';
  show_add_popup: boolean = false;
  private routeSub!: Subscription;
  amount_error: boolean = false;
  currency: string = '';
  converted_price:number = 0;
  converted_currency_label:string = '';
  middle_amount: number = 0;
  url: string = '';
  percentage_amount_int: number = 0;
  calculatedPrice: string = '';

  ngOnInit() {
    this.converted_currency_label = this.cookieService.get("currency");
    if(this.project.type_id == 1){
      this.url = window.location.origin + this.detailsOrganizationsRoutes[this.storageService.siteLanguage$.value] + this.project.category.slug + "/" + this.project.slug;
    }
    else if(this.project.type_id == 3){
      this.type = "one_time";
      if(this.project.percentage_amount >= 100){
        this.project.percentage_amount = 100;
      }
      this.percentage_amount_int = parseInt(String(this.project.percentage_amount));
      if(this.project.percentage_amount > 0 && this.project.percentage_amount <= 1){
        this.percentage_amount_int = 1;
      }
      this.middle_amount = this.project.price_list[1];
      this.getConvertedPrice(this.project.price_list[1]);
      this.url = window.location.origin + this.detailsCrowdFundingsRoutes[this.storageService.siteLanguage$.value] + this.project.category.slug + "/" + this.project.slug;
    } else if(this.project.type_id == 2){
      this.options = this.project?.dropdown;
        for (let counter = 0; counter < this.options.options.length; counter++) {
          if(this.options.options[counter].title_d1 != null){
            this.dropdown1.push(this.options.options[counter].title_d1);
          }
          if(this.options.options[counter].title_d2 != null){
            this.dropdown2.push(this.options.options[counter].title_d2);
          }
          if(this.options.options[counter].is_default == 1){
            this.selected_option = this.options.options[counter].id;
            this.selected_d1 = this.options.options[counter].title_d1;
            this.selected_d2 = this.options.options[counter].title_d2;
          }
        }

      this.dropdown1 = [...new Set(this.dropdown1)];
      this.dropdown2 = [...new Set(this.dropdown2)];

      this.single_price = this.amount = this.project?.price;
      this.getConvertedPrice(this.amount);
      if(this.project.payment_type == "Subscription"){
        this.type = "monthly";
      } else {
        this.type = "one_time";
      }
      this.url = window.location.origin + this.detailsProjectsRoutes[this.storageService.siteLanguage$.value] + this.project.category.slug + "/" + this.project.slug;
    }
  }

  constructor(public cartService: CartService,
              public storageService: StorageService,
              public translate: TranslateService,
              public pixel: MetaPixelService,
              public cookieService: CookieService,
              public basketService: BasketService,
              public convertedCurrency: ConvertCurrencyService,
              public router: Router,
              private celebrations: CelebrationsService) {
    translate.get("JOD").subscribe((translation: string) => {
      this.currency = translation;
    });
  }

  increase_quantity(){
    this.default_quantity++;
    this.amount = this.single_price * this.default_quantity;
    this.getConvertedPrice(this.amount);
  }

  decrease_quantity(){
    if(this.default_quantity > 1){
      this.default_quantity--;
      this.amount = this.single_price * this.default_quantity;
      this.getConvertedPrice(this.amount);
    }
  }

  /**
   * Contrubte change input event handler
   * 
   * (1) Allow numberes to be written in input.
   * 
   * @param event 
   */
  onContributeChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;
    value = value.replace(/[^0-9]/g, '');

    (event.target as HTMLInputElement).value = value;

    this.contributeValue = +value;
  };

  setContributeValue(value: number, index: number) {
    this.customAmount = '';
    this.amount = value;
    this.contributeOptionIndex = index;
    this.amount_error = false;
    this.getConvertedPrice(this.amount);
    this.getCalculatedPercantage();
  };

  enableCustomInput(index: number) {
    this.contributeOptionIndex = index;
  };

  setType(type: string) {
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

  onSelectionChange(){
    if(this.project.dropdown != null){
      var foundItems = this.options.options.filter((item: { title_d1: string; title_d2: string; }) => item.title_d1 === this.selected_d1 && item.title_d2 === this.selected_d2);
      if(foundItems){
        this.selected_option = foundItems[0].id;
        this.single_price = foundItems[0].price;
        this.amount = this.single_price * this.default_quantity;
        this.getConvertedPrice(this.amount);
      }
    }
  }
  submitContribute(event?: MouseEvent){
    const formData: any = {
      type: this.type,
      price: this.amount,
      quantity: this.default_quantity,
      item_id: this.project.id,
      option_id: this.selected_option,
      temp_id: this.cookieService.get('session_id')
    };
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

          var project_type = '';
          if(this.project.type_id == 1){
            project_type = "B2B";
          } else if(this.project.type_id == 2){
            project_type = "Individual";
          } else if(this.project.type_id == 3){
            project_type = "B2C";
          }
          this.pixel.trackAddToCartProduct(
            {
              productId:  this.project.id,
              name: this.project.title,
              category: this.project.category.title,
              price: this.amount,
              quantity: this.default_quantity
            },
            {
              type: project_type,
              payment_type: this.type,
            }
          );
          this.resetData()
          this.show_add_popup = true;
          setTimeout(() => {
            this.show_add_popup = false;
          }, 3000);
        },
        error: () => {
        }
      });
    }
  }

  resetData(){
    if(this.project.type_id == 3){
      this.amount = 0;
      this.customAmount = '';
      this.contributeOptionIndex = -1;
      this.getConvertedPrice(this.middle_amount);
    }
    else if(this.project.type_id == 2){
      this.amount = this.single_price;
      this.getConvertedPrice(this.amount);
      this.default_quantity = 1;
    }
    this.calculatedPrice = '';
  }

  closePopup(value: boolean){
    this.show_add_popup = false;
    this.show_share = false;
  }

  /**
   * Angular onDestory lifecycle method
   */
  ngOnDestroy(): void {
    if (this.routeSub) {
      this.routeSub.unsubscribe();
    }
    this.destory$.next();
    this.destory$.complete();
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
    if(this.project.single_price != null && this.amount > 0){
      const items = this.amount / this.project.single_price;
      this.calculatedPrice = Number.isInteger(items) ? `${items}`: `${items.toFixed(2)}`;
    } else {
      this.calculatedPrice = '';
    }
  }
}
