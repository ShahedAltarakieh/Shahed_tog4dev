import {Component, ElementRef, Input, ViewChild} from '@angular/core';
import Swiper from "swiper";
import {Subject, takeUntil} from "rxjs";
import {StorageService} from "../../../core/storage/storage.service";
import {HomeSlider} from "./types/home-slider.types";
import { QuickContributionComponent } from "../quick-contribute/quick-contribute.component";
import {Contribution} from "../../../shared/services/types/QuickContributions.types";

@Component({
    selector: 'app-home-slider',
    imports: [QuickContributionComponent],
    templateUrl: './home-slider.component.html',
    styleUrl: './home-slider.component.scss'
})
export class HomeSliderComponent {
  @ViewChild('swiperContainer') swiperContainer!: ElementRef;

  swiper!: Swiper;
  destroy$ = new Subject<void>;
  @Input({ required: true }) homeSlider: HomeSlider[] = [];
  @Input() quickContribution!: Contribution;

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
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
        type: 'custom',
        renderCustom: function (swiper, current, total) {
          var out = ''
          for (var i = 1; i < total+1; i++) {
            if (i == current) {
              out = out + '<span class="swiper-pagination-bullet swiper-pagination-bullet-active" style="background: #8AD0C1; opacity: 1; width: 9px; height: 9px;" tabindex='+i+' role="button" aria-label="Go to slide '+i+1+'"></span>';
            }
            else {
              out = out + '<span class="swiper-pagination-bullet" style="background: #E2E8F0; opacity: 1; width: 9px; height: 9px;" tabindex='+i+' role="button" aria-label="Go to slide '+i+1+'"></span>';
            }
          };
          return out;
        },
      },
      autoplay: {
        delay: 3000
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
