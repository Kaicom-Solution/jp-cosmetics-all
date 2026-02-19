export interface NavItem {
  id: string;
  label: string;
  link: string;
}

export interface BusinessInfo {
  business_name: string | null;
  business_email: string | null;
  business_phone: string | null;
  website_url: string | null;
  bkash_number: string | null;
  trade_license: string | null;
  address: string | null;
  header_logo: string | null;
  footer_logo: string | null;
  favicon_icon: string | null;
  header_advertisement: string;
  footer_advertisement: string;
  facebook_url: string | null;
  twitter_url: string | null;
  linkedin_url: string | null;
  youtube_url: string | null;
  instagram_url: string | null;
}

export interface HeroSlider {
  id: number;
  label: string;
  title: string;
  short_description: string;
  url: string;
  image: string;
  status: number;
}

export interface Category {
  id: number;
  name: string;
  parent_id: number | null;
  slug: string;
  sequence: number;
  image: string;
  is_popular: number;
  description: string | null;
}

export interface Brand {
  id: number;
  name: string;
  slug: string;
  logo: string;
  description: string | null;
  status: number;
}

export interface FooterSlider {
  id: number;
  label: string;
  title: string;
  short_description: string | null;
  url: string | null;
  image: string;
  status: number;
}

export interface Product {
  id: number;
  name: string;
  slug: string;
  product_type: string;
  status: string;
  short_description: string | null;
  long_description: string | null;
  ingredients: string | null;
  how_to_use: string | null;
  primary_image: string;
  category: {
    id: number;
    name: string;
    slug: string;
  };
  brand: {
    id: number;
    name: string;
    slug: string;
  };
  created_at: string;
  default_attribute: {
    id: number;
    product_id: number;
    attribute_name: string;
    attribute_value: string;
    unit_price: string;
    stock: number;
    min_order: number;
    max_order: number;
    discount_type: string;
    discount_amount: string;
    status: number;
    is_default: number;
    created_at: string;
    updated_at: string;
    discounted_price: number;
    attribute_discount_amount: number;
    discount_percentage: number;
  };
}

export interface SingleProduct {
  id: number;
  name: string;
  slug: string;
  primary_image: string;
  product_type: string;
  status: string;
  short_description: string | null;
  long_description: string | null;
  ingredients: string | null;
  how_to_use: string | null;
  category: {
    id: number;
    name: string;
    slug: string;
  };
  brand: {
    id: number;
    name: string;
  };
  attributes: Array<{
    id: number;
    attribute_name: string;
    attribute_value: string;
    unit_price: string;
    discount_type: string | null;
    attribute_discount_amount: number;
    discount_percentage: number;
    discounted_price: number;
    formated_discount_price: string;
    stock: number;
  }>;
  attribute_images: Array<{
    attribute_id: number;
    image: string;
  }>;
}

export interface ProductList {
  data: Product[];
  links: any;
  meta: any;
}

export interface ProductFilters {
  categories: Array<{
    id: number;
    name: string;
    slug: string;
  }>;
  brands: Array<{
    id: number;
    name: string;
    slug: string;
  }>;
  price: number;
}

export interface FilterOption {
  id: number;
  name: string;
  slug: string;
}
export interface PaginationLink {
  url: string | null;
  label: string;
  page: number | null;
  active: boolean;
}

export interface PaginationData {
  current_page: number;
  from: number;
  last_page: number;
  links: PaginationLink[];
  path: string;
  per_page: number;
  to: number;
  total: number;
}


// export interface RelatedProduct {
//   id: number;
//   name: string;
//   slug: string;
//   price: number;
//   image: string;
// }
