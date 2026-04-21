import { Component, OnInit, ViewChildren, QueryList } from '@angular/core';

import { AuthService } from 'app/auth/services/auth.service';
import { BasketService } from 'app/shared/services/basket/basket.service';

import { BasketItemComponent } from './components/basket-item/basket-item.component';
import {CartService} from "../shared/services/cart/cart.service";
import {Subject, takeUntil} from "rxjs";
import {StorageService} from "../core/storage/storage.service";
import {ActivatedRoute, Router, RouterLink} from "@angular/router";
import {TranslatePipe, TranslateService} from "@ngx-translate/core";
import {FormsModule} from "@angular/forms";
import {CollectionTeamComponent} from "./components/collection-team/collection-team.component";
import {NgClass, NgIf} from "@angular/common";
import {GuestFormComponent} from "./components/guest-form/guest-form.component";
import {ModalComponent} from "../shared/components/modal/modal.component";
import {CookieService} from "ngx-cookie-service";
import { OrangeMoneyComponent } from "./components/orange-money/orange-money.component";
import { environment } from 'environments/environment';
import { MetaPixelService } from 'app/shared/services/meta-pixel-service/meta-pixel.service';

@Component({
    selector: 'app-basket',
    imports: [
        BasketItemComponent,
        TranslatePipe,
        FormsModule,
        CollectionTeamComponent,
        NgIf,
        GuestFormComponent,
        ModalComponent,
        RouterLink,
        NgClass,
        OrangeMoneyComponent
    ],
    templateUrl: './basket.component.html',
    styleUrl: './basket.component.scss'
})
export class BasketComponent implements OnInit {
  destory$ = new Subject<void>;
  items: any;
  tota_price: number = 0;
  payment_type: string = '';
  privacyPolicyChecked = false;
  receiveEmailsChecked = false;
  show_collection_modal = false;
  show_payment_option = false;
  show_guest_form = false;
  currency_convert_amount: number = 0;
  currency_convert_label: number = 0;
  show_error_payment: boolean = false;
  payment_error_message: string = '';
  isLoading: boolean = false;
  is_data_fetched: boolean = false;
  payment_error_modal: boolean = false;
  selected_payment: string = '';
  response_payment: any;
  show_orange_mony_form: boolean = false;
  userData: any = null;
  show_orange_money = false;
  dedicationError = false;
  dedicationErrorKey = 'please fill dedication names';

  @ViewChildren(BasketItemComponent) basketItemComponents!: QueryList<BasketItemComponent>;

  basketRoutes: Record<string, string> = {
    ar: 'ar/السلة',
    en: 'en/basket',
  };

  constructor(
    public storageService: StorageService,
    public basketService: BasketService,
    public router: Router,
    public authService: AuthService,
    public cartService: CartService,
    public cookieService: CookieService,
    private route: ActivatedRoute,
    private pixel: MetaPixelService,
    private translate: TranslateService,
  ) {}

  fetchBasketData(){
    this.is_data_fetched = false;
    this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
      this.cartService.getCart(lang).subscribe({
        next: (value: any) => {
          this.items = value.data;
          this.is_data_fetched = true;          
          if(this.items.length < 1){
            this.basketService.quantity = 0;
            this.router.navigate(['/' + this.storageService.siteLanguage$.value]);
          } else {
            this.basketService.quantity = this.items.length;
            this.show_orange_money = true; // true
            this.pixel.trackCartView(
              this.items.map((i: any) => ({ productId: i.item_id, price: i.price / i.quantity, quantity: i.quantity })),
              { source: 'cart_page' }
            );
            
            this.items.forEach((item: any) => {
              if (item.type === 'monthly') {
                this.show_orange_money = false;
              }
            });
            this.tota_price = value.total_price;
            if(this.cookieService.get('countryCode') != "JO"){
              this.currency_convert_amount = value.price;
              this.currency_convert_label = value.user_currency;
            }
          }
        },
        error: () => {
          this.is_data_fetched = true;
        }
      });
    });
  }
  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {
      const payment = params['payment'];
      const message = params['message'];
      if(payment == "failed"){
        this.show_error_payment = true;
        this.payment_error_message = message;
      }
    });
    this.fetchBasketData()
  }

  /**
   * Validates dedication inputs, stores dedication names via API when needed, then proceeds to payment flow.
   */
  validateAndStoreDedicationThenProceed(): void {
    this.dedicationError = false;
    const components = this.basketItemComponents?.toArray() ?? [];
    for (const comp of components) {
      if (comp.hasBeneficiary) {
        const allFilled = comp.beneficiaryValues.every(v => v != null && String(v).trim() !== '');
        if (!allFilled) {
          this.dedicationErrorKey = 'please fill dedication names';
          this.dedicationError = true;
          return;
        }
        if (comp.hasDedicationPhrases) {
          const allPhrasesSelected = comp.dedicationPhraseSelections.every(id => id != null);
          if (!allPhrasesSelected) {
            this.dedicationErrorKey = 'please select dedication phrase';
            this.dedicationError = true;
            return;
          }
        }
      }
    }
    const payload: { items: { cart_item_id: number; names: string[]; dedication_phrase_ids?: number[] }[] } = { items: [] };
    for (const comp of components) {
      if (comp.hasBeneficiary && comp.beneficiaryValues.length) {
        const itemPayload: { cart_item_id: number; names: string[]; dedication_phrase_ids?: number[] } = {
          cart_item_id: comp.item.id,
          names: comp.beneficiaryValues.map(v => String(v ?? '').trim()),
        };
        if (comp.hasDedicationPhrases && comp.dedicationPhraseSelections.length) {
          itemPayload.dedication_phrase_ids = comp.dedicationPhraseSelections.map(id => id ?? 0);
        }
        payload.items.push(itemPayload);
      }
    }
    const proceed = () => {
      this.sendPixelCheckOut();
      if (this.cookieService.get('countryCode') == "JO") {
        this.show_payment_option = true;
      } else {
        this.payment_type = "card";
        this.submit_payment();
      }
    };
    const lang = this.storageService.siteLanguage$.value as string;
    if (payload.items.length > 0) {
      this.isLoading = true;
      this.cartService.storeDedicationNames(lang, payload).subscribe({
        next: () => {
          this.isLoading = false;
          proceed();
        },
        error: () => {
          this.isLoading = false;
          this.show_error_payment = true;
          this.payment_error_message = this.translate.instant('error saving dedication names');
        },
      });
    } else {
      proceed();
    }
  }

  show_payment_modal(): void {
    this.validateAndStoreDedicationThenProceed();
  }

  show_guest_modal(){
    this.show_guest_form = true;
  }
  submit_payment(userData: any = null){
    if(this.payment_type == "card"){
      this.show_payment_option = false;
      if(!this.authService.loggedInUser && userData == null){
        this.show_guest_form = true;
      } else{
        this.isLoading = true;
        this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
          this.cartService.submitPayment(lang, userData).subscribe({
            next: (value:any) => {
              this.response_payment = value;
              this.selected_payment = value.payment_method;
              this.isLoading = false;
              window.location.href = environment.networkUrl + value.session_id + '?checkoutVersion=1.0.0';
            },
            error: (error) => {
              if(error.error.error != null){
                location.reload();
              }
              this.isLoading = false;
            }
          });
        });
      }
    } else if(this.payment_type == "cash"){
      this.show_payment_option = false;
      this.show_collection_modal = true;
    } else if(this.payment_type == "orange_money"){
      this.show_payment_option = false;
      if(!this.authService.loggedInUser && userData == null){
        this.show_guest_form = true;
      } else{
        this.userData = userData;
        this.show_orange_mony_form = true;
      }
    }
  }

  sendPixelCheckOut(){    
    this.pixel.trackInitiateCheckout(
      this.items.map((i: any) => ({ productId: i.item_id, price: i.price / i.quantity, quantity: i.quantity })),
      {
        source: 'checkout_button',
        payment_method: this.payment_type
      }
    );  
  }
  /**
   * Privacy policy check event handler
   *
   * @param { Event } event
   */
  onPrivacyPolicyCheck(event: Event) {
    this.privacyPolicyChecked = (event.target as HTMLInputElement).checked;
  }

  onReceiveEmailCheck(event: Event) {
    this.receiveEmailsChecked = (event.target as HTMLInputElement).checked;
  }

  /**
   * Angular onDestory lifecycle method
   */
  ngOnDestroy(): void {
    this.destory$.next();
    this.destory$.complete();
  }

  close_modal(){
    this.show_payment_option = false;
    this.show_guest_form = false;
    this.show_orange_mony_form = false;
    this.show_collection_modal = false;
    this.show_error_payment = false;
    this.payment_error_modal = false;
    this.router.navigate([this.basketRoutes[this.storageService.siteLanguage$.value]], {
      queryParams: {
        'payment': null,
        'message': null
      },
      queryParamsHandling: 'merge'
    })
  }
}
