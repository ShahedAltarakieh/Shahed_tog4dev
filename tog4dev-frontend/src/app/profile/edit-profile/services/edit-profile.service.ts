import { Injectable } from '@angular/core';
import { EditProfileForm } from '../types/edit-profile.types';
import {HttpClient} from "@angular/common/http";
import {AuthService} from "../../../auth/services/auth.service";
import {ContactUsFormPostBody} from "../../../shared/components/contact-us-form/types/contact-us-form.types";
import { environment } from 'environments/environment';
import { StorageService } from 'app/core/storage/storage.service';
@Injectable({
  providedIn: 'root'
})
export class EditProfileService {
  private apiUrl = environment.apiUrl;
  editForm: EditProfileForm = {
    first_name: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    last_name: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    email: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    password: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    organization_name: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    country: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    city: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    birthday: {
      value: '',
      errorMsg: '',
      isValid: false,
    },
    image: {
      value: '',
      errorMsg: '',
      isValid: true,
    },
  };

  imageToUpload: File | null = null;

  constructor(public httpClient: HttpClient, public authService: AuthService, private storageService: StorageService) { }

  getUserInfo(){
    const loggedInUser = this.authService.loggedInUser;
    return this.httpClient.get<any>(this.apiUrl + 'api/v1/user', {
      headers: { 'Authorization': 'Bearer ' + loggedInUser?.token, 'Content-Type': 'application/json' },
    });
  }

  editProfile() {
    const loggedInUser = this.authService.loggedInUser;

    const {
      first_name,
      last_name,
      organization_name,
      country,
      city,
      email,
      password,
      birthday,
      image
    } = this.editForm;


    const formData: any = {
      first_name: first_name.value,
      last_name: last_name.value,
      email: email.value,
      country: country.value,
      organization_name: organization_name.value,
      city: city.value,
      password: password.value,
      birthday: birthday.value,
      image: image.value
    };

    return this.httpClient.put(this.apiUrl + 'api/v1/user/profile', formData, {
      headers: { 'Authorization': 'Bearer ' + loggedInUser?.token, 'Content-Type': 'application/json', 'Accept-Language': this.storageService.siteLanguage$.value },
    });
  };
}
