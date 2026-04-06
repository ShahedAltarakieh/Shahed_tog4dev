import { Component } from '@angular/core';
import { BasicInputComponent } from 'app/shared/components/inputs/components/basic-input/basic-input.component';
import { DatepickerComponent } from 'app/shared/components/inputs/components/datepicker/datepicker.component';
import { SelectDropdownComponent } from 'app/shared/components/inputs/components/select-dropdown/select-dropdown.component';
import { EditProfileService } from './services/edit-profile.service';
import {SelectedCountry} from "../../auth/components/signup/types/signup.types";
import {HttpClient} from "@angular/common/http";
import {TranslatePipe} from "@ngx-translate/core";
import {SignupForm} from "../../auth/types/auth.types";
import {EditProfileForm} from "./types/edit-profile.types";
import {ModalComponent} from "../../shared/components/modal/modal.component";

@Component({
    selector: 'app-edit-profile',
    imports: [
        BasicInputComponent,
        SelectDropdownComponent,
        DatepickerComponent,
        TranslatePipe,
        ModalComponent
    ],
    templateUrl: './edit-profile.component.html',
    styleUrl: './edit-profile.component.scss'
})
export class EditProfileComponent {
  imageUrl = '';
  countries_list: any;
  country_flag: string = "";
  country_selected: SelectedCountry = {} as unknown as SelectedCountry;
  country_name: string = "";
  show_edit_modal: boolean = false;

  constructor(public editProfileService: EditProfileService, public httpClient: HttpClient) {
    this.editProfileService.getUserInfo().subscribe((res) => {
      editProfileService.editForm.first_name.value = res.first_name;
      editProfileService.editForm.first_name.isValid = true;
      editProfileService.editForm.last_name.value = res.last_name;
      editProfileService.editForm.last_name.isValid = true;
      editProfileService.editForm.email.value = res.email;
      editProfileService.editForm.email.isValid = true;
      editProfileService.editForm.organization_name.value = res.organization_name;
      editProfileService.editForm.organization_name.isValid = true;
      editProfileService.editForm.city.value = res.city;
      editProfileService.editForm.city.isValid = true;
      editProfileService.editForm.birthday.value = res.birthday;
      editProfileService.editForm.birthday.isValid = true;
      this.imageUrl = res.image;
      this.httpClient.get('/app/assets/json/countries.json').subscribe((countries) => {
        this.countries_list = countries;
        this.country_selected = this.countries_list.find((country: { country_name_english: string; }) => country.country_name_english == res.country);
        if(!this.country_selected){
          this.country_selected = this.countries_list.find((country: { phone_code: string; }) => country.phone_code == "+962");
        }
        this.country_flag = this.country_selected.flag;
        this.country_name = this.country_selected.country_name_english;
        editProfileService.editForm.country.value = this.country_name;
        editProfileService.editForm.country.isValid = true;
      });
    });
  }

  firstNameErrorHandler(errorMsg: string) {
    this.editProfileService.editForm.first_name.errorMsg = errorMsg;
    this.editProfileService.editForm.first_name.isValid = !errorMsg;
  }

  lastNameErrorHandler(errorMsg: string) {
    this.editProfileService.editForm.last_name.errorMsg = errorMsg;
    this.editProfileService.editForm.last_name.isValid = !errorMsg;
  }

  emailErrorHandler(errorMsg: string) {
    this.editProfileService.editForm.email.errorMsg = errorMsg;
    this.editProfileService.editForm.email.isValid = !errorMsg;
  }

  passwordErrorHandler(errorMsg: string) {
    this.editProfileService.editForm.password.errorMsg = errorMsg;
    this.editProfileService.editForm.password.isValid = !errorMsg;
  }

  organizationNameErrorHandler(errorMsg: string) {
    this.editProfileService.editForm.organization_name.errorMsg = errorMsg;
    this.editProfileService.editForm.organization_name.isValid = !errorMsg;
  }

  cityErrorHandler(errorMsg: string) {
    this.editProfileService.editForm.city.errorMsg = errorMsg;
    this.editProfileService.editForm.city.isValid = !errorMsg;
  }

  birthDayErrorHandler(errorMsg: string) {
    this.editProfileService.editForm.birthday.errorMsg = errorMsg;
    this.editProfileService.editForm.birthday.isValid = !errorMsg;
  }

  countryErrorHandler(errorMsg: string) {
    this.editProfileService.editForm.country.errorMsg = errorMsg;
    this.editProfileService.editForm.country.isValid = !errorMsg;
  }

  onProfileImageUpload(event: Event) {
    const input = event.target as HTMLInputElement;

    if ((input.files || []).length > 0) {
      const file = input.files![0];

      // Check if the uploaded file is an image
      if (file && file.type.startsWith('image/')) {
        // Convert the image to base64
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imageUrl = e.target?.result as string; // Store base64 image data
          this.editProfileService.editForm.image.value = this.imageUrl;
          this.editProfileService.editForm.image.isValid = true;
          this.editProfileService.imageToUpload = file; // You can still store the file if you need it later
        };
        reader.readAsDataURL(file); // Read the file as base64
      } else {
        // Handle invalid file type (non-image files)
        this.editProfileService.editForm.image.isValid = false;
        this.editProfileService.editForm.image.errorMsg = "Please upload a valid image file.";
      }
    }
  }


  submit() {

    this.editProfileService.editProfile().subscribe({
      next: (value) => {
        this.show_edit_modal = true;
      },
      error: (err => {
        const errorsList: Record<string, string[]> = err.error.errors;

        // this.isLoading = false;
        // this.requestSuccessMsg = '';

        for (const key in errorsList) {
          this.editProfileService.editForm[key as keyof EditProfileForm].errorMsg = errorsList[key][0];
          this.editProfileService.editForm[key as keyof EditProfileForm].isValid = !errorsList[key][0];
        }
      })
    })
    this.editProfileService.editProfile();
  }
}
