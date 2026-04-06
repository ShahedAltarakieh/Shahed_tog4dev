export type Contribution = {
    id: number,
    title: string,
    description: string,
    target: number,
    single_price: number | null,
    price_list: number[],
    total_paid: number | null,
    percentage_amount: number | null,
}

export type GetContributionsResponse = {
    data: Contribution[],
}