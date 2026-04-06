import {Component, Input} from '@angular/core';
import {Category} from "../../../shared/services/types/categories.types";
import {NgClass, NgIf} from '@angular/common';
import {StorageService} from "../../../core/storage/storage.service";

@Component({
    selector: 'app-hero-section',
    imports: [
        NgClass,
        NgIf
    ],
    templateUrl: './hero-section.component.html',
    styleUrl: './hero-section.component.scss'
})
export class HeroSectionComponent {
  @Input() details!: Category;

  constructor(
      public storageService: StorageService) {
  }
  contributeValue = 20;
  isCustomContributeShown = false;
  contributeOptionIndex = -1;
  contributionOptionValues = [
    20,
    40,
    60,
  ];

  setContributeValue(value: number, index: number) {
    this.isCustomContributeShown = false;
    this.contributeValue = value;
    this.contributeOptionIndex = index;
  };

  enableCustomInput(index: number) {
    this.contributeOptionIndex = index;
    this.isCustomContributeShown = true;
  };

  /**
   * Contrubte change input event handler
   * 
   * (1) Allow numberes to be written in input.
   * 
   * @param event 
  */
  onContributeChange(event: Event) {
    let value = (event.target as HTMLInputElement).value;
    value = value.replace(/[^0-9]/g, '');

    (event.target as HTMLInputElement).value = value;

    this.contributeValue = +value;
  };
}