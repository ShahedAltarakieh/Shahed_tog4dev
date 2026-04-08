import { AfterViewInit, Component, ElementRef, Inject, Input, OnDestroy, PLATFORM_ID, ViewChild } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';

import Swiper from 'swiper';
import { Subject, takeUntil } from 'rxjs';

import { StorageService } from 'app/core/storage/storage.service';

import { Story } from './types/our-stories.types';
import {TranslatePipe} from "@ngx-translate/core";
import {Category} from "../../services/types/categories.types";
import {Router} from "@angular/router";

@Component({
    selector: 'app-our-stories',
    imports: [
        TranslatePipe
    ],
    templateUrl: './our-stories.component.html',
    styleUrl: './our-stories.component.scss'
})
export class OurStoriesComponent implements AfterViewInit, OnDestroy {
  @Input({ required: true }) storiesList: Story[] = [];

  @ViewChild('swiperContainer') swiperContainer!: ElementRef;

  swiper!: Swiper;
  destroy$ = new Subject<void>;

  constructor(public storageService: StorageService, private router: Router, @Inject(PLATFORM_ID) private platformId: Object) {}

  /**
   * Angular afterViewInit lifecycle method
   */
  ngAfterViewInit(): void {
    this._initializeComponent();
  }

  /**
   * Initialize component data and subscriptions
   */
  _initializeComponent() {
    this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe((lang) => {
      setTimeout(() => {
        this.initSwiper();
      });
    });
  }

  /**
   * Initialize swiperjs
   * 
   * @returns { void }
   */
  initSwiper(): void {
    if (!isPlatformBrowser(this.platformId)) return;
    this.swiper?.destroy();

    this.swiper = new Swiper(this.swiperContainer.nativeElement, {
      slidesPerView: 1.5,
      observer: true,
      autoplay: {
        delay: 3000
      },
      loop: true,
      centeredSlides: true,
      centeredSlidesBounds: true,
      spaceBetween: 10,
      initialSlide: 3,
      breakpoints: {
        640: {
          slidesPerView: 1.5,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 37,
        },
        1024: {
          slidesPerView: 4,
          spaceBetween: 37,
        },
        2300: {
          slidesPerView: 5,
          spaceBetween: 37,
        },
        2800: {
          slidesPerView: 6,
          spaceBetween: 37,
        },
      },
    });
  }

  setActiveCategory(category: any) {
    var commands = null;
    var commands_ar = null;
    switch (category.type){
      case 2:
        commands = 'en/individual-projects';
        commands_ar = 'ar/المشاريع-الفردية';
        break;
      case 1:
        commands = 'en/organizations-projects';
        commands_ar = 'ar/مشاريع-المنظمات';
        break;
      case 3:
        commands = 'en/crowdfunding';
        commands_ar = 'ar/التمويل-الجماعي';
        break;
    }
      this.router.navigate([this.storageService.siteLanguage$.value === 'ar' ? commands_ar : commands, category.slug]);
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }
}
