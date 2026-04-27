import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';

import { StorageService } from '../core/storage/storage.service';

@Component({
    selector: 'app-notfound-redirect',
    imports: [
    ],
    templateUrl: './notfound-redirect.component.html',
    styleUrl: './notfound-redirect.component.scss'
})
export class NotfoundRedirectComponent {

  constructor(private router: Router, private route: ActivatedRoute, private storageService: StorageService) {
    const queryParams = { ...this.route.snapshot.queryParams };
    const target = '/' + (this.storageService.defaultLanguage || 'en');
    if (Object.keys(queryParams).length > 0) {
      this.router.navigate([target], { queryParams });
    } else {
      this.router.navigate([target]);
    }
  }
}
