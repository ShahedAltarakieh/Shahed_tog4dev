import { Injectable } from '@angular/core';

import { ContactUsForm, ContactUsFormPostBody } from '../types/contact-us-form.types';
import { HttpClient } from '@angular/common/http';
import { environment } from 'environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ContactUsFormService {
  private apiUrl = environment.apiUrl;
  contactUsForm: ContactUsForm = {
    firstName: {
      value: '',
      isValid: false,
      errorMsg: '',
    },
    lastName: {
      value: '',
      isValid: false,
      errorMsg: '',
    },
    country: {
      value: '',
      isValid: false,
      errorMsg: '',
    },
    organizationName: {
      value: '',
      isValid: false,
      errorMsg: ''
    },
    email: {
      value: '',
      isValid: false,
      errorMsg: '',
    },
    message: {
      value: '',
      isValid: false,
      errorMsg: '',
    },
    phone: {
      value: '',
      isValid: false,
      errorMsg: '',
    },
  };
  
  constructor(public httpClient: HttpClient) { }

  submitContactUsForm(type: number) {
    const { firstName, lastName, organizationName, country, email, phone, message } = this.contactUsForm;

    const formData: ContactUsFormPostBody = {
      first_name: firstName.value,
      last_name: lastName.value,
      email: email.value,
      country: country.value,
      message: message.value,
      organization_name: organizationName.value,
      phone: phone.value,
      type: type
    };

    return this.httpClient.post(this.apiUrl + 'api/v1/contact-us', formData);
  };

  resetForm() {
    this.contactUsForm = {
      firstName: {
        value: '',
        isValid: false,
        errorMsg: '',
      },
      lastName: {
        value: '',
        isValid: false,
        errorMsg: '',
      },
      country: {
        value: '',
        isValid: false,
        errorMsg: '',
      },
      organizationName: {
        value: '',
        isValid: false,
        errorMsg: ''
      },
      email: {
        value: '',
        isValid: false,
        errorMsg: '',
      },
      message: {
        value: '',
        isValid: false,
        errorMsg: '',
      },
      phone: {
        value: '',
        isValid: false,
        errorMsg: '',
      },
    };
  };
}
