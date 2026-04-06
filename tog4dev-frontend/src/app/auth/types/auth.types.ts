export type LoginForm = {
  email: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  password: {
    value: string,
    errorMsg: string,
    isValid: boolean
  }
}

export type SignupForm = {
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
  phone_code:{
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  phone: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  password: {
    value: string,
    errorMsg: string,
    isValid: boolean
  },
  organizationName: {
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
};

export type LoginResponse = {
  user: {
    id: number,
    first_name: string,
    last_name: string,
    email: string,
    role: number,
  },
  token: string,
  cart: number
};