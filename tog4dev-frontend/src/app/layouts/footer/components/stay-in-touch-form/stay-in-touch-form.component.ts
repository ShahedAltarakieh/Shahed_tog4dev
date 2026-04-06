import { Component } from '@angular/core';

import { FooterService } from 'app/layouts/footer/services/footer.service';
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-stay-in-touch-form',
    imports: [
        TranslatePipe
    ],
    templateUrl: './stay-in-touch-form.component.html',
    styleUrl: './stay-in-touch-form.component.scss'
})
export class StayInTouchFormComponent {
  constructor(public footerService: FooterService) {}

  emailValue = '';

  /**
   * Email input change handler
   * 
   * @param { Event } event 
   */
  onEmailChange(event: Event) {
    this.emailValue = (event.target as HTMLInputElement).value;
  }

  /**
   * Submit Newsletter Form
   * 
   * @returns { void }
   */
  onSubmitClick(): void {
    this.footerService.postNewsLetter(this.emailValue).subscribe(value => {
      this.footerService.newsLetterResponseMsg = value.message;
      
      setTimeout(() => {
        this.footerService.newsLetterResponseMsg = '';
      }, 3000);
    }, (err) => {
      this.footerService.newsLetterResponseMsg = err.error.error;

      setTimeout(() => {
        this.footerService.newsLetterResponseMsg = '';
      }, 3000);
    });
  }
}
