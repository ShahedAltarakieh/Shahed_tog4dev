export type CollectionTeamPostBody = {
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  address: string;
  city: string;
  temp_id: string;
}

export type collectionForm = {
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
  },
  city: {
    value: '',
    errorMsg: '',
    isValid: false,
  },
  address: {
    value: '',
    errorMsg: '',
    isValid: false,
  },
};