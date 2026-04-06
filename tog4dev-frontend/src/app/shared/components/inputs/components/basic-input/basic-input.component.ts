import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { NgClass } from '@angular/common';

import { ValidatorsService } from 'app/shared/services/validators/validators.service';

import { AutoFillDirective } from 'app/shared/directives/auto-fill.directive';

import { BasicInputType, InputAttributes } from '../../types/inputs.types';
import {TranslatePipe} from "@ngx-translate/core";
import {StorageService} from "../../../../../core/storage/storage.service";

@Component({
    selector: 'app-basic-input',
    imports: [
        NgClass,
        AutoFillDirective,
        TranslatePipe,
    ],
    templateUrl: './basic-input.component.html',
    styleUrl: './basic-input.component.scss'
})
export class BasicInputComponent implements OnInit {
  @Input({ required: true }) inputAttr: InputAttributes = {} as unknown as InputAttributes;
  @Input({ required: true }) value = '';
  @Input({ required: true }) errorMsg = '';
  @Input() additionalClass: string = '';
  @Input() additionalInputClass: string = '';
  @Input({ required: true }) inputType: BasicInputType = 'first name';

  @Output() valueChanged: EventEmitter<string> = new EventEmitter<string>();
  @Output() errorChanged: EventEmitter<string> = new EventEmitter<string>();

  type = 'text';

  constructor(public validatorsService: ValidatorsService, public storageService: StorageService) {}

  /**
   * Angular onInit lifecycle method
   * 
   * @returns { void }
   */
  ngOnInit(): void {
    if (this.inputType === 'password') {
      this.type = 'password';
    }
    if (this.inputType === 'birthday') {
      this.type = 'date';
    }
  }

  onInputChange(event: Event) {
    switch (this.inputType) {
      case 'first name':
      case 'last name':
        this.handleNameChange(event);
        break;
      case 'country':
        this.handleCountryChange(event);
        break;
      case 'city':
        this.handleCityChange(event);
        break;
      case 'address':
        this.handleAddressChange(event);
        break;
      case 'email':
        this.handleEmailChange(event);
        break;
      case 'organization name':
        this.handleOrganizationChange(event);
        break;
      case 'password':
        this.handlePasswordChange(event);
        break;
      case 'birthday':
        this.handleBirthDayChange(event);
        break;
      case 'cliq number':
        this.handleCliqNumberChange(event);
        break;
      default:
        this.value = (event.target as HTMLInputElement).value;
        break;
    }

    this.valueChanged.next(this.value);
  }

  /**
   * Handle Name change (first and last name)
   * 
   * @param event 
   */
  handleNameChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;
    value = value.replace(/[^a-zA-Z\u0600-\u06FF]/g, '');
    (event.target as HTMLInputElement).value = value;

    value = (event.target as HTMLInputElement).value;

    this.value = value;

    if (this.inputAttr.isRequired && !this.value) {
      this.errorChanged.next('empty error');
    } 
    else if(this.value.length < 3){
      this.errorChanged.next('the field must be at least 3 characters');
    }
    else {
      this.errorChanged.next('');
    }
  }

    /**
   * Handle Country change
   * 
   * @param event 
   */
  handleCountryChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;

    this.value = value;

    if (this.inputAttr.isRequired && !this.value) {
      this.errorChanged.next('empty error');
    } else {
      this.errorChanged.next('');
    }
  }

  handleCityChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;

    this.value = value;

    if (this.inputAttr.isRequired && !this.value) {
      this.errorChanged.next('empty error');
    } else {
      this.errorChanged.next('');
    }
  }

  handleAddressChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;

    this.value = value;

    if (this.inputAttr.isRequired && !this.value) {
      this.errorChanged.next('empty error');
    } else {
      this.errorChanged.next('');
    }
  }  

  /**
   * Handle Email change
   * 
   * @param event 
   */
  handleEmailChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;

    this.value = value;

    if (!this.validatorsService.isValidEmail(value)) {
      this.errorChanged.next('not valid email'); 
    } else if (this.inputAttr.isRequired && !this.value) {
      this.errorChanged.next('empty error');
    } else {
      this.errorChanged.next(''); 
    }
  }

  /**
   * Handle Organization change
   * 
   * @param event 
   */
  handleOrganizationChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;

    this.value = value;

    this.valueChanged.next(value);

    if (this.inputAttr.isRequired && !this.value) {
      this.errorChanged.next('empty error');
    } else {
      this.errorChanged.next('');
    }
  }

  handlePasswordChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;

    this.value = value;

    this.valueChanged.next(value);

    if (this.inputAttr.isRequired && !this.value) {
      this.errorChanged.next('empty error');
    } else {
      this.errorChanged.next('');
    }
  }

  handleBirthDayChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;

    this.value = value;

    this.valueChanged.next(value);

    if (this.inputAttr.isRequired && !this.value) {
      this.errorChanged.next('empty error');
    } else {
      this.errorChanged.next('');
    }
  }

  handleCliqNumberChange(event: Event) {
    const input = event.target as HTMLInputElement;

    // Keep only digits
    let rawValue = input.value.replace(/\D/g, '');

    // Enforce it starts with "07"
    if (!rawValue.startsWith('07')) {
      rawValue = '07';
    }

    // Limit to 10 digits
    rawValue = rawValue.slice(0, 10);

    // Update the input field value to reflect the forced format
    input.value = rawValue;
    this.value = rawValue;
    this.valueChanged.next(rawValue);

    // Validation
    if (this.inputAttr.isRequired && !this.value) {
      this.errorChanged.next('empty error');
    } else if (!/^07\d{8}$/.test(this.value)) {
      this.errorChanged.next('invalid number format');
    } else {
      this.errorChanged.next('');
    }
  }



  /**
   * Icon click event handler
   * 
   * @returns { void }
   */
  onIconClick(): void {
    if (this.inputType === 'password' && this.type === 'password') {
      this.type = 'text';
    } else if (this.inputType === 'password' && this.type === 'text') {
      this.type = 'password';
    }
  }
}
