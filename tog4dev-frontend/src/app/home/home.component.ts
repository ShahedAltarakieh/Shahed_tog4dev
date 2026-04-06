import { Component, OnDestroy, OnInit } from '@angular/core';

import {NgIf} from "@angular/common";

import {Subject, Subscription, takeUntil} from 'rxjs';

import { OurStoriesService } from "../shared/components/our-stories/services/our-stories.service";
import { TestimonialsService } from "../shared/components/testimonials/services/testimonials.service";
import { StorageService } from 'app/core/storage/storage.service';

import {HomeSliderComponent} from "./components/home-slider/home-slider.component";
import { HowItWorksComponent } from "./components/how-it-works/how-it-works.component";
import { ProjectsListWithFilterComponent } from "./components/projects-list-with-filter/projects-list-with-filter.component";

import { OurPartnersComponent } from "../shared/components/our-partners/our-partners.component";
import { OurStoriesComponent } from "../shared/components/our-stories/our-stories.component";
import { TestimonialsComponent } from "../shared/components/testimonials/testimonials.component";

import { Story } from "../shared/components/our-stories/types/our-stories.types";
import { Testimonial } from "../shared/components/testimonials/types/testimonial.types";
import {Partner} from "../shared/components/our-partners/types/our-partners.types";
import {OurPartnersService} from "../shared/components/our-partners/services/our-partners.service";
import { ProjectsService } from 'app/shared/services/projects/projects.service';
import { ProjectItem } from 'app/shared/components/project-list-item/types/project-list-item.types';
import {HomeSliderService} from "./components/home-slider/services/home-slider.service";
import {HomeSlider} from "./components/home-slider/types/home-slider.types";
import {ActivatedRoute, Router} from "@angular/router";
import {QuickContributionService} from "../shared/services/quick-contribution/quick-contribution.service";
import {ModalComponent} from "../shared/components/modal/modal.component";
import {TranslatePipe} from "@ngx-translate/core";
import {ShareComponent} from "../shared/components/share/share.component";
import { Meta } from '@angular/platform-browser';
import { PaymentsService } from 'app/shared/services/payments/payments.service';
import { MetaPixelService } from 'app/shared/services/meta-pixel-service/meta-pixel.service';
import { CelebrationsService } from 'app/shared/services/celebrations/celebrations.service';


@Component({
    selector: 'app-home',
    imports: [
        HowItWorksComponent,
        OurPartnersComponent,
        OurStoriesComponent,
        ProjectsListWithFilterComponent,
        TestimonialsComponent,
        HomeSliderComponent,
        NgIf,
        ModalComponent,
        TranslatePipe,
        ShareComponent,
    ],
    templateUrl: './home.component.html',
    styleUrl: './home.component.scss'
})
export class HomeComponent implements OnInit, OnDestroy {

  constructor(public storageService: StorageService,
              public ourStoriesService: OurStoriesService,
              public testimonialsService: TestimonialsService,
              public ourPartnersService: OurPartnersService,
              public projectsService: ProjectsService,
              public homeSliderService: HomeSliderService,
              public quickContributionService: QuickContributionService,
              private route: ActivatedRoute,
              private paymentsService: PaymentsService,
              public router: Router,
              private pixel: MetaPixelService,
              private celebrations: CelebrationsService,
              public metaService: Meta,
            ) {}
  
  destory$ = new Subject<void>;
  storiesList: Story[] = [];
  testimonialsList: Testimonial[] = [];
  partnersList: Partner[] = [];
  projectsList: ProjectItem[] = [];
  homeSlider: HomeSlider[] = [];
  show_collection_popup: boolean = false;
  show_success_payment: boolean = false;
  quickContribution: any;
  private routeSub!: Subscription;

    /**
   * Angular afterViewInit lifecycle method
   */
  ngOnInit(): void {
      this.route.queryParams.subscribe(params => {
          const collection_team = params['collection_team'];
          if(collection_team == "success"){
              this.show_collection_popup = true;
              setTimeout(() => {
                this.celebrations.launchCelebration();
                this.celebrations.launchThankYouParty();
              }, 500);
          }
          const payment = params['payment'];
          const cart_id = params['cart_id'] ?? null;
          if(payment == "success"){
              this.show_success_payment = true;
              setTimeout(() => {
                this.celebrations.launchCelebration();
                this.celebrations.launchThankYouParty();
              }, 500);
              if(cart_id){
                this.handlePixelPurchaseEvent(cart_id);
              }
          }
      });
      this.routeSub = this.route.paramMap.subscribe(params => {
        this.fetchData();
      });
  }

  fetchData(): void {
      this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
          this.updateMetaTags();
          this.ourStoriesService.getStories(lang, null, null, true).subscribe(value => {
             this.storiesList = value.data;
          });
          this.testimonialsService.getTestimonials(lang, null, null, true).subscribe(value => this.testimonialsList = value.data);
          this.ourPartnersService.getPartners(lang, null, null, true).subscribe(value => this.partnersList = value.data);
          this.homeSliderService.getHomeSliders(lang).subscribe(value => this.homeSlider = value.data);
          this.projectsService.getProjects(lang, "home_only").subscribe(value => this.projectsList = value?.data ?? []);
          this.quickContributionService.getContribution(lang, "1", null).subscribe(value => {
            this.quickContribution = null;
            if(value){
              if(value.data.length > 0){
                this.quickContribution = value.data[0];            
              }
            }
          });
      });
  }


  closePopup(value: boolean){
    this.show_collection_popup = false;
    this.show_success_payment = false;
    this.router.navigate([], {
        queryParams: {
            'collection_team': null,
            'payment': null,
            'message': null,
            'cart_id': null
        },
        queryParamsHandling: 'merge'
    })
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

  updateMetaTags(): void {
    // Update standard meta tags
    this.metaService.updateTag({
      name: 'description',
      content: (this.storageService.siteLanguage$.value == "ar") ? "نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم" : "Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide."
    });
    // Update Open Graph meta tags
    this.metaService.updateTag({
      property: 'og:title',
      content: "Together For Development | معاً للتنمية"
    });
    this.metaService.updateTag({
      property: 'og:description',
      content: (this.storageService.siteLanguage$.value == "ar") ? "نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم" : "Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide."
    });
    this.metaService.updateTag({
      property: 'og:image',
      content: "https://tog4dev.com/app/assets/images/shared/logo.png"
    });
    this.metaService.updateTag({
      property: 'og:url',
      content: (this.storageService.siteLanguage$.value == "ar") ? "https://tog4dev.com/ar" : "https://tog4dev.com/en"
    });
    this.metaService.updateTag({
      property: 'og:type',
      content: 'website'
    });

    // Update Twitter Card meta tags
    this.metaService.updateTag({
      name: 'twitter:card',
      content: "https://tog4dev.com/app/assets/images/shared/logo.png"
    });
    this.metaService.updateTag({
      name: 'twitter:title',
      content: "Together For Development | معاً للتنمية"
    });
    this.metaService.updateTag({
      name: 'twitter:description',
      content: (this.storageService.siteLanguage$.value == "ar") ? "نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم" : "Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide."
    });
    this.metaService.updateTag({
      name: 'twitter:image',
      content: "https://tog4dev.com/app/assets/images/shared/logo.png"
    });
  }

  handlePixelPurchaseEvent(cart_id: string){
    this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
      this.paymentsService.getPayment(lang, cart_id).subscribe(value => {
        if(value && value.success == true && value.data.purchase_meta_pixel == false){
          const cart_items = value.data.cart_items.map((i: any) => ({ productId: i.item_id, price: i.price / i.quantity, quantity: i.quantity }));          
          this.pixel.trackPurchase(
            value.data.id,
            cart_items,
            {
              transaction_id: value.data.cart_id,
              source: 'thank_you_page'
            }
          );
          this.paymentsService.updatePurchaseOrderMeta(lang, cart_id).subscribe();
        }
      });
    });
    
  }
}
