import { NgClass } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
    selector: 'app-burger-menu',
    imports: [
        NgClass
    ],
    templateUrl: './burger-menu.component.html',
    styleUrl: './burger-menu.component.scss'
})
export class BurgerMenuComponent {
  @Input() isMobileMenuCollapsed = true;

  @Output() burgerMenuClicked = new EventEmitter<boolean>;

  toggleBurgerMenu = () => {
    this.isMobileMenuCollapsed = !this.isMobileMenuCollapsed;
    this.burgerMenuClicked.emit(this.isMobileMenuCollapsed);
  }
}
