import { Component, OnInit } from '@angular/core';

import { StorageService } from 'app/core/storage/storage.service';
import {TranslatePipe} from "@ngx-translate/core";
import { NgoverseSliderComponent } from "./components/ngoverse-slider/ngoverse-slider.component";
import { ContactUsFormComponent } from "../../shared/components/contact-us-form/contact-us-form.component";
import { Subject, takeUntil } from 'rxjs';
import { Meta } from '@angular/platform-browser';

@Component({
    selector: 'app-ngoverse',
    imports: [
        TranslatePipe,
        NgoverseSliderComponent,
        ContactUsFormComponent
    ],
    templateUrl: './ngoverse.component.html',
    styleUrl: './ngoverse.component.scss'
})
export class NgoverseComponent implements OnInit {
  destroy$ = new Subject<void>;
  
  constructor(
    public storageService: StorageService,
    public metaService: Meta) {}

  /**
   * Angular onInit lifecycle method
   */
  ngOnInit(): void {
      this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(lang => {
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
      content: typeof window !== 'undefined' ? window.location.href : ''
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
