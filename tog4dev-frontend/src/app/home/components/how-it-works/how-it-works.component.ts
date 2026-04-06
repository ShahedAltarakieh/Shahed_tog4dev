import { Component } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { MatIconModule, MatIconRegistry } from '@angular/material/icon';
import {TranslatePipe} from "@ngx-translate/core";

import { HOW_IT_WORKS_1, HOW_IT_WORKS_2, HOW_IT_WORKS_3, HOW_IT_WORKS_4, HOW_IT_WORKS_LINE } from 'app/assets/images/how-it-works/how-it-works-icons';
import { StorageService } from 'app/core/storage/storage.service';
import { RouterLink } from '@angular/router';
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

  constructor(    
    public domSanitizer: DomSanitizer,
    public storageService: StorageService,
    public iconRegistry: MatIconRegistry,
  ) {
    this.iconRegistry.addSvgIconLiteral('how-it-works-1', this.domSanitizer.bypassSecurityTrustHtml(HOW_IT_WORKS_1));
    this.iconRegistry.addSvgIconLiteral('how-it-works-2', this.domSanitizer.bypassSecurityTrustHtml(HOW_IT_WORKS_2));
    this.iconRegistry.addSvgIconLiteral('how-it-works-3', this.domSanitizer.bypassSecurityTrustHtml(HOW_IT_WORKS_3));
    this.iconRegistry.addSvgIconLiteral('how-it-works-4', this.domSanitizer.bypassSecurityTrustHtml(HOW_IT_WORKS_4));
    this.iconRegistry.addSvgIconLiteral('how-it-works-line', this.domSanitizer.bypassSecurityTrustHtml(HOW_IT_WORKS_LINE));
  
  }
}
