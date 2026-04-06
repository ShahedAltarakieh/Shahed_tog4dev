export type Category = {
  id: number,
  title: string,
  slug: string,
  slug_ar: string,
  slug_en: string,
  image: string,
  description: string,
  is_all_option: boolean,
  hero_title: string,
  hero_description: string,
  hero_image: string
}

export type GetCategoryResponse = {
  data: Category[],
}