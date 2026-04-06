import { Component, OnInit } from '@angular/core';

import { StorageService } from 'app/core/storage/storage.service';
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-refund-policy',
    imports: [
        TranslatePipe
    ],
    templateUrl: './refund-policy.component.html',
    styleUrl: './refund-policy.component.scss'
})
export class RefundPolicyComponent implements OnInit {

  constructor(
    public storageService: StorageService) {}

  /**
   * Angular onInit lifecycle method
   */
  ngOnInit(): void {
  }
}
