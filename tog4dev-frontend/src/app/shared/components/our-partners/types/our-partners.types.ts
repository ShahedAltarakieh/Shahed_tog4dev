export type Partner = {
  id: number,
  title: string,
  image: string,
}

export type GetPartnerResponse = {
  data: Partner[],
}