
import {AfterViewInit, Component, ElementRef, Inject, Input, OnDestroy, PLATFORM_ID, ViewChild} from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { StorageService } from 'app/core/storage/storage.service';
import { Subject, takeUntil } from 'rxjs';

import Swiper from 'swiper';
import { Testimonial } from "./types/testimonial.types";
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-testimonials',
    imports: [
        TranslatePipe
    ],
    templateUrl: './testimonials.component.html',
    styleUrl: './testimonials.component.scss'
})
export class TestimonialsComponent implements AfterViewInit, OnDestroy{
  @Input({ required: true }) testimonialsList: Testimonial[] = [];

  @ViewChild('swiperContainer') swiperContainer!: ElementRef;

  swiper!: Swiper;
  destroy$ = new Subject<void>;

  constructor(public storageService: StorageService, @Inject(PLATFORM_ID) private platformId: Object) {}

  /**
   * Angular afterViewInit lifecycle method
   * 
   * @returns { void }
   */
  ngAfterViewInit(): void {
    this._initializeComponent();
  }

  /**
   * Initialize component data and subscriptions
   * 
   * @returns { void }
   */
  _initializeComponent(): void {
    this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(() => {
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
      navigation: {
        nextEl: '.next-icon',
        prevEl: '.previous-icon',
      },
      loop: true,
      slidesPerView: 1,
      observer: true,
    });
  }

  /**
   * Angular onDestory lifecycle method
   * 
   * @returns { void }
   */
  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }
}
