import apiClient from "@/lib/axios";
import { Product, ProductFilters, ProductList, SingleProduct } from "@/types";

export const getSingleProduct = async (slug: string): Promise<SingleProduct> => {
  try {
    const response = await apiClient.get(`/products/show/${slug}`); 
    return response.data?.data;
  } catch (error) {
    console.error("Failed to fetch Popular Products Info", error);
    return {} as SingleProduct;
  }
};

export const getProducts = async (): Promise<ProductList> => {
  try {
    const response = await apiClient.get("/products"); 
    return response.data?.data;
  } catch (error) {
    console.error("Failed to fetch Products Info", error);
    return {} as ProductList;
  }
};

export const getProductFilters = async (): Promise<ProductFilters> => {
  try {
    const response = await apiClient.get("/products/filter-data"); 
    return response.data?.data;
  } catch (error) {
    console.error("Failed to fetch Product Filters Info", error);
    return {} as ProductFilters;
  }
};


export const getRelatedProduct = async (slug: string): Promise<Product[]> => {
  try {
    const response = await apiClient.get(`/products/related-products/${slug}`);
    return response.data?.data;
  } catch (error) {
    console.error("Failed to fetch Related Products Info", error);

    return {} as Product[];
  }
};