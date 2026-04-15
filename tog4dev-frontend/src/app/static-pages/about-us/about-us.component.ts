import { Component, OnInit } from '@angular/core';

import { StorageService } from 'app/core/storage/storage.service';
import { TranslatePipe } from "@ngx-translate/core";
import { Meta } from '@angular/platform-browser';

@Component({
    selector: 'app-about-us',
    imports: [
        TranslatePipe,
    ],
    templateUrl: './about-us.component.html',
    styleUrl: './about-us.component.scss'
})
export class AboutUsComponent implements OnInit {
  constructor(
    public metaService: Meta,
    public storageService: StorageService) {}

  ngOnInit(): void {
    this.updateMetaTags();
  }

  updateMetaTags(): void {
    this.metaService.updateTag({
      name: 'description',
      content: (this.storageService.siteLanguage$.value == "ar") ? "نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم" : "Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide."
    });
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
