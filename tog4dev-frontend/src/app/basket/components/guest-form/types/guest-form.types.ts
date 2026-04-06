export type GuestPostBody = {
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  phone_code: string;
  temp_id: string;
}

export type guestForm = {
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
  phone: {
    value: '',
    errorMsg: '',
    isValid: false,
  }
};