import { Component, ElementRef, ViewChild } from '@angular/core';

import { StorageService } from 'app/core/storage/storage.service';
import { Subject, takeUntil } from 'rxjs';
import Swiper from 'swiper';

@Component({
    selector: 'app-ngoverse-slider',
    imports: [],
    templateUrl: './ngoverse-slider.component.html',
    styleUrl: './ngoverse-slider.component.scss'
})

export class NgoverseSliderComponent {
  @ViewChild('swiperContainer') swiperContainer!: ElementRef;

  swiper!: Swiper;
  destroy$ = new Subject<void>;

  constructor(public storageService: StorageService) {}

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
