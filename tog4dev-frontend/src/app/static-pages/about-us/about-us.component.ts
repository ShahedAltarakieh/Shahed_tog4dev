import { Component, OnInit } from '@angular/core';

import { AboutUsSliderComponent } from './components/about-us-slider/about-us-slider.component';
import { OurPartnersComponent } from 'app/shared/components/our-partners/our-partners.component';
import { StorageService } from 'app/core/storage/storage.service';
import { OurPartnersService } from 'app/shared/components/our-partners/services/our-partners.service';
import { Subject, takeUntil } from 'rxjs';
import { Partner } from 'app/shared/components/our-partners/types/our-partners.types';
import {TranslatePipe} from "@ngx-translate/core";
import { Meta } from '@angular/platform-browser';

@Component({
    selector: 'app-about-us',
    imports: [
        AboutUsSliderComponent,
        OurPartnersComponent,
        TranslatePipe,
    ],
    templateUrl: './about-us.component.html',
    styleUrl: './about-us.component.scss'
})
export class AboutUsComponent implements OnInit {
  partnersList: Partner[] = [];
  destroy$ = new Subject<void>;

  constructor(
    public metaService: Meta,
    public storageService: StorageService,
    public ourPartnersService: OurPartnersService) {}

  /**
   * Angular onInit lifecycle method
   */
  ngOnInit(): void {
    this.fetchData();
  }

  fetchData() {
    this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(lang => {
        this.ourPartnersService.getPartners(lang, null, null).subscribe(value => this.partnersList = value.data);
        this.updateMetaTags();
    });
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
      content: window.location.href
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
}
