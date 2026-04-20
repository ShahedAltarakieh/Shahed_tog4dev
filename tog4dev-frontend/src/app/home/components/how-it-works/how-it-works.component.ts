import { Component } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { MatIconModule, MatIconRegistry } from '@angular/material/icon';
import {TranslatePipe} from "@ngx-translate/core";

import { HOW_IT_WORKS_1, HOW_IT_WORKS_2, HOW_IT_WORKS_3, HOW_IT_WORKS_4 } from 'app/assets/images/how-it-works/how-it-works-icons';
import { StorageService } from 'app/core/storage/storage.service';
import { RouterLink } from '@angular/router';

interface HiwStep {
  n: number;
  icon: string;
  title: string;
  desc: string;
  hasCta?: boolean;
}

@Component({
    selector: 'app-how-it-works',
    imports: [
        MatIconModule,
        RouterLink,
        TranslatePipe
    ],
    templateUrl: './how-it-works.component.html',
    styleUrl: './how-it-works.component.scss'
})
export class HowItWorksComponent {

  aboutUsRoutes: Record<'ar' | 'en', string> = {
    ar: '/ar/من-نحن',
    en: '/en/about-us',
  };

  steps: HiwStep[] = [
    { n: 1, icon: 'how-it-works-1', title: 'choose a project', desc: 'find a cause that resonates with you' },
    { n: 2, icon: 'how-it-works-2', title: 'make a delegation', desc: 'your contribution creates real change', hasCta: true },
    { n: 3, icon: 'how-it-works-3', title: 'project execution', desc: 'our teams turn your trust into impact' },
    { n: 4, icon: 'how-it-works-4', title: 'receive a detailed report', desc: 'see the real difference you helped create' },
  ];

  constructor(
    public domSanitizer: DomSanitizer,
    public storageService: StorageService,
    public iconRegistry: MatIconRegistry,
  ) {
    this.iconRegistry.addSvgIconLiteral('how-it-works-1', this.domSanitizer.bypassSecurityTrustHtml(HOW_IT_WORKS_1));
    this.iconRegistry.addSvgIconLiteral('how-it-works-2', this.domSanitizer.bypassSecurityTrustHtml(HOW_IT_WORKS_2));
    this.iconRegistry.addSvgIconLiteral('how-it-works-3', this.domSanitizer.bypassSecurityTrustHtml(HOW_IT_WORKS_3));
    this.iconRegistry.addSvgIconLiteral('how-it-works-4', this.domSanitizer.bypassSecurityTrustHtml(HOW_IT_WORKS_4));
  }
}
