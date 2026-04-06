export type Testimonial = {
  id: number,
  name: string,
  description: string,
  image: string,
  location: string,
}

export type GetTestimonialResponse = {
  data: Testimonial[],
}