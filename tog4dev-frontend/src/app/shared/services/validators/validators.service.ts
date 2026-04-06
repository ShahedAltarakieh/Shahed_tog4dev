import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ValidatorsService {
  emailRegex: RegExp = new RegExp(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);

  constructor() { }

  /**
   * Checks if email is valid email,
   * @param value the email value to check
   * @returns {boolean} - true if it is valid email
  */
  public isValidEmail(value: string): boolean {
    return this.emailRegex.test(value);
  }
}
