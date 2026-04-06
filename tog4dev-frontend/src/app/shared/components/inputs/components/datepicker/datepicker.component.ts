import { NgClass } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormsModule } from '@angular/forms';

import { NgbDatepickerModule, NgbDateStruct } from '@ng-bootstrap/ng-bootstrap';
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-datepicker',
    imports: [
        NgbDatepickerModule,
        FormsModule,
        NgClass,
        TranslatePipe
    ],
    templateUrl: './datepicker.component.html',
    styleUrls: ['../basic-input/basic-input.component.scss', './datepicker.component.scss']
})
export class DatepickerComponent {
  @Input({ required: true }) id: string = '';
  @Input({ required: true }) label = '';
  @Input({ required: true }) placeholder = '';
  @Input({ required: true }) value = '';
  @Input({ required: true }) errorMsg = '';
  @Input() isRequired = false;

  @Output() valueChanged = new EventEmitter<string>;
  @Output() errorChanged = new EventEmitter<string>;

  model!: NgbDateStruct;
  
  onDateSelect() {
    this.valueChanged.emit(this.model.year + "-" + this.model.month + "-" + this.model.day);
    this.errorChanged.next('');
  }
}
