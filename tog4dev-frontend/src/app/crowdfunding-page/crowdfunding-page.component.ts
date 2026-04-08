import {Component, HostListener, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';

import { Subject, takeUntil } from 'rxjs';

import { StorageService } from 'app/core/storage/storage.service';

import { BreadcrumbComponent } from 'app/shared/components/breadcrumb/breadcrumb.component';
import { ProjectSliderComponent } from 'app/shared/components/project-slider/project-slider.component';

import { Breadcrumb } from 'app/shared/components/breadcrumb/types/breadcrumb.types';
import { OurPartnersComponent } from 'app/shared/components/our-partners/our-partners.component';
import { Partner } from 'app/shared/components/our-partners/types/our-partners.types';
import { OurPartnersService } from 'app/shared/components/our-partners/services/our-partners.service';
import {DecimalPipe, NgClass, NgIf} from "@angular/common";
import {TranslatePipe, TranslateService} from "@ngx-translate/core";
import {ProjectItem} from "../shared/components/project-list-item/types/project-list-item.types";
import {ProjectsService} from "../shared/services/projects/projects.service";
import {CartService} from "../shared/services/cart/cart.service";
import {BasketService} from "../shared/services/basket/basket.service";
import {ModalComponent} from "../shared/components/modal/modal.component";
import {ShareComponent} from "../shared/components/share/share.component";
import {ConvertCurrencyService} from "../shared/services/convert-currency/convert-currency.service";
import {CookieService} from "ngx-cookie-service";
import {Meta} from "@angular/platform-browser";
import { MetaPixelService } from 'app/shared/services/meta-pixel-service/meta-pixel.service';
import { CelebrationsService } from 'app/shared/services/celebrations/celebrations.service';

@Component({
    selector: 'app-crowdfunding-page',
    imports: [
        BreadcrumbComponent,
        ProjectSliderComponent,
        OurPartnersComponent,
        NgIf,
        TranslatePipe,
        NgClass,
        DecimalPipe,
        ModalComponent,
        ShareComponent,
    ],
    templateUrl: './crowdfunding-page.component.html',
    styleUrl: './crowdfunding-page.component.scss'
})
export class CrowdfundingPageComponent implements OnInit{
  breadcrumb: Breadcrumb[] = [];
  partnersList: Partner[] = [];
  destory$ = new Subject<void>;
  id: number | null = null;
  lang: string = '';
  category_slug: string | null = '';
  item_slug: string | null = '';
  project!: ProjectItem;
  type: string = "one_time";
  amount: number = 0;
  contributeOptionIndex = -1;
  customAmount: string = '';
  show_add_popup: boolean = false;
  fixed_div: boolean = false;
  amount_error: boolean = false;
  currency: string = '';
  show_share = false;
  converted_price:number = 0;
  converted_currency_label:string = '';
  middle_amount: number = 0;
  url: string = '';
  calculatedPrice: string = '';
  percentage_amount_int: number = 0;

  constructor(
      public cartService: CartService,
      private pixel: MetaPixelService,
      public storageService: StorageService,
      public basketService: BasketService,
      public translate: TranslateService,
      public cookieService: CookieService,
      public convertedCurrency: ConvertCurrencyService,
      public metaService: Meta,
      public projectService: ProjectsService,
      public ourPartnersService: OurPartnersService,
      private route: ActivatedRoute,
      private router: Router,
      private celebrations: CelebrationsService,
      ) {
    translate.get("JOD").subscribe((translation: string) => {
      this.currency = translation;
    });
    this.converted_currency_label = this.cookieService.get("currency");
  }


  @HostListener('window:scroll', [])
  onWindowScroll() {
    // Change the header style when the scroll position is greater than 50px
    this.fixed_div = typeof window !== 'undefined' ? window.scrollY > 75 : false;
  }

  /**
   * Angular onInit lifecycle method
   */
  ngOnInit(): void {
    this.lang = this.storageService.siteLanguage$.value;
    this.route.paramMap.subscribe(params => {
      this.category_slug = params.get('category_slug');
      this.item_slug = params.get('slug');
      this.fetchData();
    });
  }

  fetchData() {
    this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
      
      if(lang != this.lang && this.item_slug != null){
        this.lang = lang;
        if(lang == "ar"){
          this.router.navigate(["ar/التمويل-الجماعي", this.project.category.slug_ar ,this.project.slug_ar]);
        } else {
          this.router.navigate(["en/crowdfunding", this.project.category.slug_en ,this.project.slug_en]);
        }
        return;
      }    
      this.projectService.getProject(lang, this.item_slug).subscribe(value => {
        this.type = "one_time";
        this.amount = 0;
        this.contributeOptionIndex = -1;
        this.customAmount = '';
        if(value){
          this.project = value.data;
          this.middle_amount = this.project.price_list[1];
          this.getConvertedPrice(this.middle_amount);
          if(this.project.percentage_amount >= 100){
            this.project.percentage_amount = 100;
          }
          var command = '';
          if(lang == "ar"){
            command = "/ar/التمويل-الجماعي/";
          } else {
            command = "/en/crowdfunding/";
          }
          if(this.project){
            this.breadcrumb = [];
            this.breadcrumb.push({
              link: command,
              title: "B2C Public Shares"
            });
            this.breadcrumb.push({
              link: command + this.project.category.slug,
              title: this.project.category.title
            });
            this.breadcrumb.push({
              link: command + this.project.category.slug + "/" + this.project.slug,
              title: this.project.title
            });

            this.url = typeof window !== 'undefined' ? window.location.href : '';

            this.percentage_amount_int = parseInt(String(this.project.percentage_amount));    
            if(this.project.percentage_amount && this.project.percentage_amount > 0 && this.project.percentage_amount <= 1){
              this.percentage_amount_int = 1;
            }
            this.pixel.trackViewContentProduct(
              {
                productId: this.project.id,
                name: this.project.title,
                category: this.project.category.title,
                price: this.middle_amount
              },
              {
                type: "B2C Public Shares"
              }
            );
            this.updateMetaTags();
          }
        }
      });
      this.ourPartnersService.getPartners(lang, null, null).subscribe(value => this.partnersList = value.data);

    });
  }

  setContributeValue(value: number, index: number) {
    this.customAmount = '';
    this.amount = value;
    this.contributeOptionIndex = index;
    this.amount_error = false;
    this.getConvertedPrice(this.amount);
    this.getCalculatedPercantage();
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
    } else{
      this.getConvertedPrice(this.amount);
    }
    if(numericValue == ''){
      inputElement.value = '';
      this.customAmount = '';
    }
    this.getCalculatedPercantage();
  }

  submitContribute(event?: MouseEvent){
    const formData: any = {
      type: this.type,
      price: this.amount,
      item_id: this.project.id,
      option_id: null,
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
        next: (value: any) => {
          this.basketService.quantity = value.count ?? 0;
          this.show_add_popup = true;          
          this.pixel.trackAddToCartProduct(
            {
              productId:  this.project.id,
              name: this.project.title,
              category: this.project.category.title,
              price: this.amount,
              quantity: 1
            },
            {
              type: "B2C Public Shares",
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

  /**
   * Angular onDestory lifecycle method
   */
  ngOnDestroy(): void {
    this.destory$.next();
    this.destory$.complete();
  }

  closePopup(value: boolean){
    this.show_add_popup = false;
    this.show_share = false;
  }

  resetForm(){
    this.amount = 0;
    this.contributeOptionIndex = -1;
    this.customAmount = '';
    this.getConvertedPrice(this.middle_amount);
    this.calculatedPrice = '';
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


  updateMetaTags(): void {
    // Update standard meta tags
    this.metaService.updateTag({
      name: 'description',
      content: this.project.description
    });
    // Update Open Graph meta tags
    this.metaService.updateTag({
      property: 'og:title',
      content: this.project.title
    });
    this.metaService.updateTag({
      property: 'og:description',
      content: this.project.description
    });
    this.metaService.updateTag({
      property: 'og:image',
      content: this.project.image
    });
    this.metaService.updateTag({
      property: 'og:url',
      content: typeof window !== 'undefined' ? window.location.href : ''
    });
    this.metaService.updateTag({
      property: 'og:type',
      content: 'website'
    });

    // Update Twitter Card meta tags
    this.metaService.updateTag({
      name: 'twitter:card',
      content: this.project.image
    });
    this.metaService.updateTag({
      name: 'twitter:title',
      content: this.project.title
    });
    this.metaService.updateTag({
      name: 'twitter:description',
      content: this.project.description
    });
    this.metaService.updateTag({
      name: 'twitter:image',
      content: this.project.image
    });
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
