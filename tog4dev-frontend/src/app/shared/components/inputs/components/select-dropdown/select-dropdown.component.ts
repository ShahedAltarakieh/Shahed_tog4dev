import { Component, ElementRef, EventEmitter, HostListener, Input, OnInit, Output } from '@angular/core';
import {NgClass, NgForOf} from '@angular/common';
import {FormsModule} from "@angular/forms";
import {TranslatePipe} from "@ngx-translate/core";
import {StorageService} from "../../../../../core/storage/storage.service";
// import { DropdownItem } from '../types/inputs.types';

@Component({
    selector: 'app-select-dropdown',
    imports: [
        NgClass,
        FormsModule,
        NgForOf,
        TranslatePipe
    ],
    templateUrl: './select-dropdown.component.html',
    styleUrls: ['../basic-input/basic-input.component.scss', './select-dropdown.component.scss']
})
export class SelectDropdownComponent implements OnInit {
  @Input({ required: true }) id = '';
  @Input({ required: true }) label = '';
  @Input({ required: true }) value = '';
  @Input({ required: true }) errorMsg = '';
  @Input({ required: true }) placeholder = '';
  @Input() additionalClass: string = '';
  @Input() items: any;
  @Input() icon = 'mobile-arrow.svg'
  @Input() rightIcon = '';
  @Input() isRequired = true;
  @Input() selectedDropdownValue: string = '';
  // @Input({ required: true }) dropdownList: DropdownItem[] = []; 

  @Output() valueChanged: EventEmitter<string> = new EventEmitter<string>();
  @Output() errorChanged: EventEmitter<string> = new EventEmitter<string>();

  @HostListener('document:click', ['$event.target'])
  onOutsideClick(target: HTMLElement) {
    if ((this.elementRef.nativeElement as HTMLElement).querySelector('.input-container') && !(this.elementRef.nativeElement as HTMLElement).querySelector('.input-container')!.contains(target)) {
      this.isExpanded = false;
    }
  }

  isExpanded = false;
  searchQuery: string = '';

  constructor(public elementRef: ElementRef, public storageService: StorageService) {}

  /**
   * Angular onInit lifecycle method
   */
  ngOnInit(): void {
    if (!!this.selectedDropdownValue) {
      this.valueChanged.next(this.selectedDropdownValue);
      this.errorChanged.next('');
    }
  }

  onDropdownClick(event: Event) {
    event.stopPropagation();

    this.isExpanded = !this.isExpanded;
  }

  onItemSelect(item: any) {
    this.valueChanged.next(item.country_name_english);
    this.icon = item.flag;
    this.closeInput();
    this.errorChanged.next('');
  }

  onInputClick() {
    this.isExpanded = true;
  }

  closeInput(){
    this.isExpanded = false;
  }

  filteredCountries() {
    if(this.storageService.siteLanguage$.value === 'ar'){
      return this.items.filter((item: { country_name_arabic: string; }) =>
          item.country_name_arabic.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    } else {
      return this.items.filter((item: { country_name_english: string; }) =>
          item.country_name_english.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    }
  }

  // This method returns the appropriate country name based on the current language
  getCountryName(value: string): string {
    const country = this.items.find((c: { country_name_english: any; }) => c.country_name_english === value);
    return country ? (this.storageService.siteLanguage$.value === 'ar' ? country.country_name_arabic : country.country_name_english) : '';
  }
}
