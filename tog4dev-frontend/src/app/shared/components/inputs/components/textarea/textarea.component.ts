import { Component, EventEmitter, Input, Output } from '@angular/core';
import { NgClass } from '@angular/common';

import { InputAttributes } from '../../types/inputs.types';
import { AutoFillDirective } from 'app/shared/directives/auto-fill.directive';
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-textarea',
    imports: [
        NgClass,
        AutoFillDirective,
        TranslatePipe
    ],
    templateUrl: './textarea.component.html',
    styleUrls: ['../basic-input/basic-input.component.scss', './textarea.component.scss']
})
export class TextareaComponent {
  @Input({ required: true }) inputAttr: InputAttributes = {} as unknown as InputAttributes;
  @Input({ required: true }) value = '';
  @Input({ required: true }) errorMsg = '';

  @Output() valueChanged: EventEmitter<string> = new EventEmitter<string>();
  @Output() errorChanged: EventEmitter<string> = new EventEmitter<string>();

  /**
   * textarea change event handler
   * 
   * @param event 
   */
  onInputChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;

    this.valueChanged.next(value);

    if (!value && this.inputAttr.isRequired) {
      this.errorChanged.next('empty error');
    } else {
      this.errorChanged.next('');
    }
  }
}
