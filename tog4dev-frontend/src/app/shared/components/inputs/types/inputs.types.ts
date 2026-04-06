
export type InputAttributes = {
  id: string,
  name: string,
  label: string,
  placeholder: string,
  isRequired: boolean,
  icon?: string,
}

export type BasicInputType = 'cliq number' | 'email' | 'birthday' | 'first name' | 'last name' | 'country' | 'organization name' | 'password' | 'city' | 'address';

export type DropdownItem = {
  id: number,
  value: string,
  text: string,
  icon?: string,
};