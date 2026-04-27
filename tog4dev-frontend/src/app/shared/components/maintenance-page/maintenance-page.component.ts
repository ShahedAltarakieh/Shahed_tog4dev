import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { RouterLink } from '@angular/router';
import { StorageService } from 'app/core/storage/storage.service';
import { PageMaintenanceInfo } from 'app/shared/services/page-maintenance/page-maintenance.service';

@Component({
  selector: 'app-maintenance-page',
  standalone: true,
  imports: [CommonModule, TranslateModule, RouterLink],
  templateUrl: './maintenance-page.component.html',
  styleUrl: './maintenance-page.component.scss',
})
export class MaintenancePageComponent {
  @Input() info!: PageMaintenanceInfo;

  constructor(public storageService: StorageService) {}

  get title(): string {
    const lang = this.storageService.siteLanguage$.value;
    return (lang === 'ar' ? this.info?.label_ar : this.info?.label_en) || '';
  }

  get message(): string {
    const lang = this.storageService.siteLanguage$.value;
    const msg = lang === 'ar' ? this.info?.message_ar : this.info?.message_en;
    return msg || '';
  }
}
