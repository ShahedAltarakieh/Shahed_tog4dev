import {Component, HostListener, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';

import {Subject, Subscription, takeUntil} from 'rxjs';

import { StorageService } from 'app/core/storage/storage.service';

import { BreadcrumbComponent } from 'app/shared/components/breadcrumb/breadcrumb.component';
import { ProjectSliderComponent } from 'app/shared/components/project-slider/project-slider.component';

import { Breadcrumb } from 'app/shared/components/breadcrumb/types/breadcrumb.types';
import { OurPartnersComponent } from 'app/shared/components/our-partners/our-partners.component';
import { Partner } from 'app/shared/components/our-partners/types/our-partners.types';
import { OurPartnersService } from 'app/shared/components/our-partners/services/our-partners.service';
import {NgClass, NgForOf, NgIf} from "@angular/common";
import {TranslatePipe} from "@ngx-translate/core";
import {ProjectItem} from "../shared/components/project-list-item/types/project-list-item.types";
import {ProjectsService} from "../shared/services/projects/projects.service";
import {FormsModule} from "@angular/forms";
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
    selector: 'app-project-page',
    imports: [
        BreadcrumbComponent,
        ProjectSliderComponent,
        OurPartnersComponent,
        NgIf,
        TranslatePipe,
        NgClass,
        FormsModule,
        ModalComponent,
        ShareComponent,
    ],
    templateUrl: './project-page.component.html',
    styleUrl: './project-page.component.scss'
})
export class ProjectPageComponent implements OnInit, OnDestroy {
  breadcrumb: Breadcrumb[] = [];
  partnersList: Partner[] = [];
  destory$ = new Subject<void>;
  id: number | null = null;
  lang: string = '';
  category_slug: string | null = '';
  item_slug: string | null = '';
  project!: ProjectItem;
  type: string | null = null;
  amount: number = 0;
  default_quantity: number = 1;
  single_price: number = 0;
  options: any;
  dropdown1: string[] = [];
  dropdown2: string[] = [];
  selected_d1: string = '';
  selected_d2: string = '';
  selected_option: number = 0;
  fixed_div: boolean = false;
  show_add_popup: boolean = false;
  payment_type: string = '';
  private routeSub!: Subscription;
  type_error: boolean = false;
  show_share:boolean = false;
  converted_price:number = 0;
  converted_currency_label:string = '';
  url: string = '';

  /** Project 113: separate assets for small screens vs desktop (replace paths when files are uploaded). */
  readonly project113VideoMobileSrc = '/app/assets/videos/haj-mobile.mp4';
  readonly project113VideoWebSrc = '/app/assets/videos/haj-web.mp4';

  constructor(
    public storageService: StorageService,
    public projectService: ProjectsService,
    public ourPartnersService: OurPartnersService,
    private route: ActivatedRoute,
    public metaService: Meta,
    private pixel: MetaPixelService,
    public cookieService: CookieService,
    public convertedCurrency: ConvertCurrencyService,
    public basketService: BasketService,
    public cartService: CartService,
    private router: Router,
    private celebrations: CelebrationsService,
  ) {
    this.converted_currency_label = this.cookieService.get("currency");
  }

  @HostListener('window:scroll', [])
  onWindowScroll() {
    // Change the header style when the scroll position is greater than 50px
    this.fixed_div = typeof window !== 'undefined' ? window.scrollY > 75 : false;
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
          this.router.navigate(["ar/المشاريع-الفردية", this.project.category.slug_ar ,this.project.slug_ar]);
        } else {
          this.router.navigate(["en/individual-projects", this.project.category.slug_en ,this.project.slug_en]);
        }
        return;
      }
      this.projectService.getProject(lang, this.item_slug).subscribe({
        next: (value) => {
          if (value) {
            this.options = null;
            this.dropdown1 = [];
            this.dropdown2 = [];
            this.selected_d1 = '';
            this.selected_d2 = '';
            this.selected_option = 0;
            this.default_quantity = 1;

            this.project = value.data;
            let command = '';
            if (lang === "ar") {
              command = "/ar/المشاريع-الفردية/";
            } else {
              command = "/en/individual-projects/";
            }

            if (this.project) {
              this.breadcrumb = [];
              this.breadcrumb.push({
                link: command,
                title: "individual projects"
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

              if(this.project.payment_type == "Subscription"){
                this.payment_type = "subscription";
                this.type = "monthly";
              } else if(this.project.payment_type == "One-Time"){
                this.payment_type = "one_time";
                this.type = "one_time";
              }

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
              this.ourPartnersService.getPartners(lang, null, this.project.category.id)
                .subscribe({
                  next: value => this.partnersList = value.data,
                  error: err => {
                  }
                });

              this.pixel.trackViewContentProduct(
                {
                  productId: this.project.id,
                  name: this.project.title,
                  category: this.project.category.title,
                  price: this.project.price
                },
                {
                  type: "Individual Projects"
                }
              );
            
              this.updateMetaTags();
            }
          }
        },
        error: (err) => {
          if(err.error?.redirect){
            this.router.navigate([this.storageService.siteLanguage$.value === 'ar' ? "/ar" : "/en"]);
          }
        }
      });
    });
  }

  setType(type: string) {
    this.type_error = false;
    this.type = type;
  };

  submitContribute(event?: MouseEvent){
    const formData: any = {
      type: this.type,
      price: this.amount,
      quantity: this.default_quantity,
      item_id: this.project.id,
      option_id: this.selected_option,
      temp_id: this.cookieService.get('session_id')
    };
    if(this.type == '' || this.type == null){
      this.type_error = true;
      return;
    }
    else {
      if (event) {
        this.celebrations.spawnHearts(event);
      }
      this.cartService.addToCart(formData).subscribe({
        next: (value:any) => {
          this.basketService.quantity = value.count;
          this.pixel.trackAddToCartProduct(
            {
              productId:  this.project.id,
              name: this.project.title,
              category: this.project.category.title,
              price: this.amount,
              quantity: this.default_quantity
            },
            {
              type: "Individual Projects",
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
          // alert("there are error");
        }
      });
    }
  }

  resetData(){
    if(this.project.payment_type != "Subscription" && this.project.payment_type != "One-Time"){
      this.type = null;
    }
    this.amount = this.single_price;
    this.default_quantity = 1;
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

  closePopup(value: boolean){
    this.show_share = false;
    this.show_add_popup = false;
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
}
