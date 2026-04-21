import {Component, EventEmitter, Input, OnInit, Output, SimpleChanges} from '@angular/core';

import {TranslatePipe} from "@ngx-translate/core";
import {Router, RouterLink} from "@angular/router";
import {NgClass, NgIf} from "@angular/common";
import {StorageService} from "../../../core/storage/storage.service";

@Component({
    selector: 'app-modal',
    imports: [
        TranslatePipe,
        RouterLink,
        NgIf,
        NgClass
    ],
    templateUrl: './modal.component.html',
    styleUrl: './modal.component.scss'
})
export class ModalComponent{
  isLoading = false;
  @Input({ required: true }) title: string = '';
  @Input() show!: boolean;
  @Input() with_icon: boolean = false;

  @Output() valueEmitted = new EventEmitter<boolean>();
  @Output() confirmEmitted = new EventEmitter<boolean>();
  @Input() confirm_remove_button: boolean = false;
  @Input() confirm_button_label: string | null = '';
  @Input() yellow_button: string | null = '';
  @Input() success_icon: boolean = false;
  @Input() with_basket_button: boolean = false;

  constructor(public storageService: StorageService, private router: Router) {}

  get basketLink(): string {
    const lang = this.storageService?.siteLanguage$?.value || 'en';
    return `/${lang}/basket`;
  }

  goToBasket(event: Event): void {
    event.preventDefault();
    this.accept();
    this.router.navigateByUrl(this.basketLink);
  }

  accept(){
    this.valueEmitted.emit(false);
  }
  remove(){
    this.isLoading = true;
    this.confirmEmitted.emit(true);
  }

  close_modal(){
    this.show = false;
  }

  ngOnChanges(changes: SimpleChanges): void {
    this.isLoading = false;
  }
}
