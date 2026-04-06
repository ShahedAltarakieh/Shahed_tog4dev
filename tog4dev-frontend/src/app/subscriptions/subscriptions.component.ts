import {Component, OnDestroy, OnInit} from '@angular/core';
import { SubscriptionItemComponent } from './components/subscription-item/subscription-item.component';
import { ContributionHistoryItemComponent } from './components/contribution-history-item/contribution-history-item.component';
import {TranslatePipe} from "@ngx-translate/core";
import {Subject, takeUntil} from "rxjs";
import {MyDonationService} from "../shared/services/my-donation/my-donation.service";
import {StorageService} from "../core/storage/storage.service";
import {ModalComponent} from "../shared/components/modal/modal.component";
import {Router, RouterLink} from "@angular/router";

@Component({
    selector: 'app-subscriptions',
    imports: [
        SubscriptionItemComponent,
        ContributionHistoryItemComponent,
        TranslatePipe,
        ModalComponent,
        RouterLink,
    ],
    templateUrl: './subscriptions.component.html',
    styleUrl: './subscriptions.component.scss'
})
export class SubscriptionsComponent implements OnInit, OnDestroy{
    destory$ = new Subject<void>;
    one_time_payments:any = [];
    subscriptions_payments:any = [];
    show_unsubscribed_modal: boolean = false;
    constructor(public myDonation: MyDonationService,
                public router: Router,
                public storageService: StorageService) {
    }
    fetchListData(){
        this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
            this.myDonation.getList(lang).subscribe({
                next: (value: any) => {
                    if(value.data.length > 0){
                        this.subscriptions_payments = value.data;
                    }
                },
                error: () => {
                }
            });
        });
    }
    ngOnInit(): void {
        this.fetchListData()
    }

    unsubscribePayment(value: boolean){
        this.fetchListData();
        this.show_unsubscribed_modal = true;
    }

    /**
     * Angular onDestory lifecycle method
     */
    ngOnDestroy(): void {
        this.destory$.next();
        this.destory$.complete();
    }


    closePopup(value: boolean){
        this.show_unsubscribed_modal = false;
    }
}
