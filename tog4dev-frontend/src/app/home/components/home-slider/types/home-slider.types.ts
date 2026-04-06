export type HomeSlider = {
  id: number,
  title: string,
  description: string,
  image: string,
  image_tablet: string,
  image_mobile: string,
  logo: string
}

export type GetSliderResponse = {
  data: HomeSlider[],
}