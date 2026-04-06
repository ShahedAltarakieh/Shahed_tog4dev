export type Story = {
  id: number,
  title: string,
  image: string,
  category: {
    id: number,
    title: string,
  }
}

export type GetStoryResponse = {
  data: Story[],
}