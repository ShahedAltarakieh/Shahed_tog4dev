export type ContactUsForm = {
  firstName: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  lastName: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  country: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  email: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  organizationName: {
    value: string,
    errorMsg: string,
    isValid: boolean
  }
  phone: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  message: {
    value: string,
    errorMsg: string,
    isValid: boolean
  }
}

export type ContactUsFormPostBody = {
  first_name: string,
  last_name: string,
  country: string,
  organization_name: string,
  email: string,
  phone: string,
  message: string,
  type: number,
}