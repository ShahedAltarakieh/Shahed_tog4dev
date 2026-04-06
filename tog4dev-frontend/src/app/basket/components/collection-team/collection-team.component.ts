import {Component, EventEmitter, Input, Output} from '@angular/core';
import {StorageService} from "../../../core/storage/storage.service";
import {NgIf} from "@angular/common";
import {TranslatePipe} from "@ngx-translate/core";
import {BasicInputComponent} from "../../../shared/components/inputs/components/basic-input/basic-input.component";
import {HttpClient} from "@angular/common/http";
import {Router} from "@angular/router";
import {collectionForm} from "./types/collection-team.types";
import { MobileNumberComponent } from "../../../shared/components/inputs/components/mobile-number/mobile-number.component";
import { CollectionService } from './services/collection-team.service';
import {BasketService} from "../../../shared/services/basket/basket.service";
import { CookieService } from "ngx-cookie-service";

@Component({
    selector: 'app-collection-team',
    imports: [
        NgIf,
        TranslatePipe,
        BasicInputComponent,
        MobileNumberComponent
    ],
    templateUrl: './collection-team.component.html',
    styleUrl: './collection-team.component.scss'
})
export class CollectionTeamComponent {
  @Output() itemRemoved = new EventEmitter<void>(); // Event emitter to notify parent
  @Output() closeModal = new EventEmitter<void>(); // Event emitter to notify parent
  @Input() show!: boolean;
  isLoading = false;
  requestSuccessMsg = '';

  constructor(
      public httpClient: HttpClient,
      public router: Router,
      public basketService: BasketService,
      public collectionService: CollectionService,
      public storageService: StorageService,
      private cookieService: CookieService) {
  }

  firstNameErrorHandler(errorMsg: string) {
    this.collectionService.collectionForm.first_name.errorMsg = errorMsg;
    this.collectionService.collectionForm.first_name.isValid = !errorMsg;
  }

  lastNameErrorHandler(errorMsg: string) {
    this.collectionService.collectionForm.last_name.errorMsg = errorMsg;
    this.collectionService.collectionForm.last_name.isValid = !errorMsg;
  }

  emailErrorHandler(errorMsg: string) {
    this.collectionService.collectionForm.email.errorMsg = errorMsg;
    this.collectionService.collectionForm.email.isValid = !errorMsg;
  }

  mobileErrorHandler(errorMsg: string) {
    this.collectionService.collectionForm.phone.errorMsg = errorMsg;
    this.collectionService.collectionForm.phone.isValid = !errorMsg;
  }

  cityErrorHandler(errorMsg: string) {
    this.collectionService.collectionForm.city.errorMsg = errorMsg;
    this.collectionService.collectionForm.city.isValid = !errorMsg;
  }

  addressErrorHandler(errorMsg: string) {
    this.collectionService.collectionForm.address.errorMsg = errorMsg;
    this.collectionService.collectionForm.address.isValid = !errorMsg;
  }

  onFormSubmit() {
    this.isLoading = true;

    this.collectionService.submitCollection().subscribe({
      next: (value) => {
        this.requestSuccessMsg = 'Collection Created Successfully!';
        this.isLoading = false;
        this.basketService.quantity = 0;
        this.cookieService.delete('session_id');
        this.generateSessionID();
        this.router.navigate(['/' + this.storageService.siteLanguage$.value], { queryParams: { collection_team: 'success'}});
      },
      error: (err => {
        const errorsList: Record<string, string[]> = err.error.errors;
        this.isLoading = false;
        this.requestSuccessMsg = '';

        for (const key in errorsList) {
          this.collectionService.collectionForm[key as keyof collectionForm].errorMsg = errorsList[key][0];
          this.collectionService.collectionForm[key as keyof collectionForm].isValid = !errorsList[key][0];
        }
      })
    })
  }

  generateSessionID() {
    const unique_id = crypto.randomUUID();
    this.cookieService.set('session_id', unique_id, 20, "/");
  }

  close_modal(){
    this.closeModal.emit();
  }
}
