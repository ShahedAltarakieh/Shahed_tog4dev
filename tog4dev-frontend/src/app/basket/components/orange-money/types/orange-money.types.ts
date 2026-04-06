export type OrangeMoneyPostBody = {
  orange_number: string;
  first_name: string;
  last_name: string;
  email: string;
  phone_number: string;
  temp_id: string;
  code: string;
}

export type orangeMoneyForm = {
  cliq_number: {
    value: '',
    errorMsg: '',
    isValid: false,
  }
};