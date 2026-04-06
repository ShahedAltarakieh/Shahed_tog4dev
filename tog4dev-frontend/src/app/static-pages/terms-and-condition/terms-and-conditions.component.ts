import { Component, OnInit } from '@angular/core';

import { StorageService } from 'app/core/storage/storage.service';
import { Subject } from 'rxjs';
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-terms-and-conditions',
    imports: [
        TranslatePipe
    ],
    templateUrl: './terms-and-conditions.component.html',
    styleUrl: './terms-and-conditions.component.scss'
})
export class TermsAndConditionsComponent implements OnInit {

  constructor(
    public storageService: StorageService) {}

  /**
   * Angular onInit lifecycle method
   */
  ngOnInit(): void {
  }
}
