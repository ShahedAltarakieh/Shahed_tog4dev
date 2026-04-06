export type Fact = {
  id: number,
  title: string,
  description: string,
  logo: string,
}

export type GetFactResponse = {
  data: Fact[],
}