import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {TranslatePipe} from "@ngx-translate/core";
import {ModalComponent} from "../../../shared/components/modal/modal.component";
import {MyDonationService} from "../../../shared/services/my-donation/my-donation.service";
import {StorageService} from "../../../core/storage/storage.service";
import {Subject, takeUntil} from "rxjs";

@Component({
    selector: 'app-subscription-item',
    imports: [
        TranslatePipe,
        ModalComponent
    ],
    templateUrl: './subscription-item.component.html',
    styleUrl: './subscription-item.component.scss'
})
export class SubscriptionItemComponent implements OnInit{
  destory$ = new Subject<void>;
  @Output() cancelSubscription = new EventEmitter<void>();
  @Input() payment!: any;
  show_unsubscribe_modal = false;
  @Output() unSubscribeEmitted = new EventEmitter<boolean>();
  constructor(public myDonation: MyDonationService,
              public storageService: StorageService) {
  }
  ngOnInit() {
  }

  unsubscribe_item(){
    this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
      this.myDonation.unsubscribePayment(lang, this.payment.id).subscribe({
        next: (value: any) => {
          this.show_unsubscribe_modal = false;
          this.unSubscribeEmitted.emit();
        },
        error: () => {
        }
      });
    });
  }

  closePopup(value: boolean){
    this.show_unsubscribe_modal = false;
  }
}
