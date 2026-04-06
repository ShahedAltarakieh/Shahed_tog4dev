export type EditProfileForm = {
  first_name: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  last_name: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  email: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  password: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  organization_name: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  city: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  birthday: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  country: {
    value: string,
    errorMsg: string,
    isValid: boolean
  }
  image: {
    value: string,
    errorMsg: string,
    isValid: boolean
  }
}