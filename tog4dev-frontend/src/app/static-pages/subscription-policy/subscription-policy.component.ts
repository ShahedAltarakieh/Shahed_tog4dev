import { Component, OnInit } from '@angular/core';

import { StorageService } from 'app/core/storage/storage.service';
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-subscription-policy',
    imports: [
        TranslatePipe
    ],
    templateUrl: './subscription-policy.component.html',
    styleUrl: './subscription-policy.component.scss'
})
export class SubscriptionPolicyComponent implements OnInit {

  constructor(
    public storageService: StorageService) {}

  /**
   * Angular onInit lifecycle method
   */
  ngOnInit(): void {
  }
}
