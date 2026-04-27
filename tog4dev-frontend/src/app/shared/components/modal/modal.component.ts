import {Component, EventEmitter, Input, OnInit, Output, SimpleChanges} from '@angular/core';

import {TranslatePipe} from "@ngx-translate/core";
import {RouterLink} from "@angular/router";
import {NgClass, NgIf} from "@angular/common";

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
