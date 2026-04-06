export type RegisterUserPostBody = {
  first_name: string;
  last_name: string;
  email: string;
  password: string;
  phone: string;
  organization_name: string;
  country: string;
  birthday: string;
}

export type SelectedCountry = {
  flag: string,
  phone_code: string,
  country_name_english: string
}