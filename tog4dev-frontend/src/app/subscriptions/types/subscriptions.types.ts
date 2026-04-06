export type OneTimePaymentItem = {
  id: number,
  user_id: number,
  cart_id: string,
  status: 'approved',
  amount: string,
  collection_team_id: number | null,
  referrer_id: number | null,
  contract_id: number,
  payment_type: 'all',
  acquirer_message: string | null,
  acquirer_rrn: null,
  resp_code: string,
  resp_message: string,
  signature: string,
  token: string,
  tran_ref: string,
  cart_items: CartItem[],
};

export type SubscriptionPaymentItem = {
  id: number,
  user_id: number,
  cart_id: string,
  status: 'approved',
  amount: string,
  collection_team_id: number | null,

};

export type CartItem = {
  id: number,
  user_id: number,
  item_id: number,
  model_type: string,
  payment_id: number,
  price: string,
  collection_team_id: number | null,
  type: 'one-time',
  is_paid: number,
  quantity: number,
  temp_id: string,
  option_id: number | null,
  option_label: string | null,

};

export type GetPaymentHistoryResponse = {
  one_time_payments: OneTimePaymentItem[],
  subscription_payments: SubscriptionPaymentItem[],
};