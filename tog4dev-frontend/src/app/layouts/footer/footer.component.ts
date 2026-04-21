import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

import { StayInTouchFormComponent } from 'app/layouts/footer/components/stay-in-touch-form/stay-in-touch-form.component';
import {TranslatePipe} from "@ngx-translate/core";
import { StorageService } from 'app/core/storage/storage.service';

@Component({
    selector: 'app-footer',
    imports: [
        RouterLink,
        StayInTouchFormComponent,
        TranslatePipe,
    ],
    templateUrl: './footer.component.html',
    styleUrl: './footer.component.scss'
})
export class FooterComponent {
  contactUsRoutes: Record<string , string> = {
    ar: 'ar/تواصل-معنا',
    en: 'en/contact-us'
  };
  aboutUsRoutes: Record<string, string> = {
    ar: 'ar/من-نحن',
    en: 'en/about-us',
  };
  ngOverseRoutes: Record<string , string> = {
    ar: 'ar/عالم-المنظمات',
    en: 'en/ngoverse'
  };

  constructor(public storageService: StorageService){}
}
