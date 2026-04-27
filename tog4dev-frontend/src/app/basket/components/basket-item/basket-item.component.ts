import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import { BasketItem } from 'app/basket/types/basket.types';
import { BasketService } from 'app/shared/services/basket/basket.service';
import {CartService} from "../../../shared/services/cart/cart.service";
import {Subject, takeUntil} from "rxjs";
import {StorageService} from "../../../core/storage/storage.service";
import {NgIf} from "@angular/common";
import {TranslatePipe} from "@ngx-translate/core";
import {RouterLink} from "@angular/router";
import {ModalComponent} from "../../../shared/components/modal/modal.component";
import {FormsModule} from "@angular/forms";

@Component({
    selector: 'app-basket-item',
    imports: [
        NgIf,
        TranslatePipe,
        RouterLink,
        ModalComponent,
        FormsModule
    ],
    templateUrl: './basket-item.component.html',
    styleUrl: './basket-item.component.scss'
})
export class BasketItemComponent implements OnInit{
  @Input({ required: true }) item: any;
  destory$ = new Subject<void>;
  @Output() itemRemoved = new EventEmitter<void>(); // Event emitter to notify parent
  route: string = '';
  route_testimonial: string = '';
  show_remove_confirm: boolean = false;

  /** When has_beneficiary is true, one input per quantity. */
  beneficiaryValues: string[] = [];

  /** Selected dedication phrase id per beneficiary index (when item has dedication_phrases). */
  dedicationPhraseSelections: (number | null)[] = [];

  get hasBeneficiary(): boolean {    
    return !!this.item?.has_beneficiary;
  }

  /** True when item has a non-empty dedication_phrases array. */
  get hasDedicationPhrases(): boolean {
    const phrases = this.item?.item?.dedication_phrases;
    return Array.isArray(phrases) && phrases.length > 0;
  }

  /** Indices 0..quantity-1 for *ngFor to show one input per quantity. */
  get beneficiaryIndices(): number[] {
    const q = this.item?.quantity ?? 0;
    return Array.from({ length: Math.max(0, q) }, (_, i) => i);
  }

  detailsProjectsRoutes: Record<string , string> = {
    ar: '/ar/المشاريع-الفردية/',
    en: '/en/individual-projects/'
  };
  detailsOrganizationsRoutes: Record<string , string> = {
    ar: '/ar/مشاريع-المنظمات/',
    en: '/en/organizations-projects/'
  };
  detailsCrowdFundingsRoutes: Record<string , string> = {
    ar: '/ar/التمويل-الجماعي/',
    en: '/en/crowdfunding/'
  };
  ngOnInit() {
    if (this.hasBeneficiary && this.item?.quantity) {
      const quantity = this.item.quantity;
      const stored = Array.isArray(this.item.dedication_names) ? this.item.dedication_names : [];
      this.beneficiaryValues = Array.from(
        { length: quantity },
        (_, i) => (stored[i] != null ? String(stored[i]) : '')
      );
      if (this.hasDedicationPhrases) {
        const storedIds = Array.isArray(this.item.dedication_phrase_ids) ? this.item.dedication_phrase_ids : [];
        this.dedicationPhraseSelections = Array.from(
          { length: quantity },
          (_, i) => (storedIds[i] != null ? Number(storedIds[i]) : null)
        );
      }
    }
    if (this.item.item?.category_type != null) {
      const category_type = this.item.item.category_type;
      switch (category_type) {
        case 1:
          this.route = this.storageService.localized(this.detailsOrganizationsRoutes) + this.item.item.category_slug;
          this.route_testimonial = this.storageService.localized(this.detailsOrganizationsRoutes) + this.item.item.category_slug + "#testimonials";
          break;
        case 2:
          this.route = this.storageService.localized(this.detailsProjectsRoutes) + this.item.item.category_slug;
          this.route_testimonial = this.storageService.localized(this.detailsProjectsRoutes) + this.item.item.category_slug + "#testimonials";
          break;
        case 3:
          this.route = this.storageService.localized(this.detailsCrowdFundingsRoutes) + this.item.item.category_slug;
          this.route_testimonial = this.storageService.localized(this.detailsCrowdFundingsRoutes) + this.item.item.category_slug + "#testimonials";
          break;
      }
    }
  }

  constructor(public basketService: BasketService,
              public storageService: StorageService,
              public cartService: CartService) {}


  removePopup(){
    this.show_remove_confirm = true;
  }

  closePopup(value: boolean){
    this.show_remove_confirm = false;
  }

  removeFromBasket(value: boolean){
    this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
      this.cartService.removeFromCart(lang, this.item.id).subscribe({
        next: (value: any) => {
          this.closePopup(true);
          this.itemRemoved.emit(); // Emit event after successful removal
        },
        error: () => {
          // alert("there are error");
        }
      });
    });
  }
}
