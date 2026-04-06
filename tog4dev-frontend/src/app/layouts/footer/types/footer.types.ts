export type NewsLetterResponse = {
  message: string,
  data?: {
    id: number,
    email: string,
    status: 'active' | 'inactive',
  }
}