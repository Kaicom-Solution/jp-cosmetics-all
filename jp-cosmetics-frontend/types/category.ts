export interface Category {
  id: number;
  name: string;
  parent_id: number | null;
  slug: string;
  sequence: number;
  image: string | null;
  is_popular: number;
  description: string | null;
  children: Category[];
}
export interface CategoryResponse {
  success: boolean;
  data: Category[];
  message: string;
}