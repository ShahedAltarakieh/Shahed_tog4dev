import { Component, OnInit } from '@angular/core';

import { StorageService } from 'app/core/storage/storage.service';
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-cookie-policy',
    imports: [
        TranslatePipe
    ],
    templateUrl: './cookie-policy.component.html',
    styleUrl: './cookie-policy.component.scss'
})
export class CookiePolicyComponent implements OnInit {

  constructor(
    public storageService: StorageService) {}

  /**
   * Angular onInit lifecycle method
   */
  ngOnInit(): void {
  }
}
