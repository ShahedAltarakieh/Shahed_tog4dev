import { Component, OnInit } from '@angular/core';

import { StorageService } from 'app/core/storage/storage.service';
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-privacy-policy',
    imports: [
        TranslatePipe
    ],
    templateUrl: './privacy-policy.component.html',
    styleUrl: './privacy-policy.component.scss'
})
export class PrivacyPolicyComponent implements OnInit {

  constructor(
    public storageService: StorageService) {}

  /**
   * Angular onInit lifecycle method
   */
  ngOnInit(): void {
  }
}
