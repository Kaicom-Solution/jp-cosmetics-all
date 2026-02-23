import apiClient from "@/lib/axios";
import { Brand, BusinessInfo, Category, FooterSlider, HeroSlider, Product } from "@/types";

// If you want to skip external API calls during a build (e.g. docker build),
// set NEXT_PUBLIC_SKIP_FETCH_ON_BUILD=1 or NEXT_PHASE=development in the build env.
const shouldSkipFetch = Boolean(
  process.env.NEXT_PUBLIC_SKIP_FETCH_ON_BUILD === "1" ||
    process.env.NEXT_PHASE === "development"
);

export const getBusinessInfo = async (): Promise<BusinessInfo> => {
  if (shouldSkipFetch) return {} as BusinessInfo;

  try {
    const response = await apiClient.get("/settings");

const baseURL = process.env.NEXT_PUBLIC_API_BASE_URL;

    // console.log("here it is");
    // console.log(baseURL);
    // console.log(response);
    return response.data?.data;
  } catch (error) {
    // Log at warn level so build logs show context but don't fail the build
    console.warn("Failed to fetch business info", error);
    return {} as BusinessInfo;
  }
};

export const getHeroSliders = async (): Promise<HeroSlider[]> => {
  if (shouldSkipFetch) return [] as HeroSlider[];

  try {
    const response = await apiClient.get("/header-sliders");
    return response.data?.data;
  } catch (error) {
    console.warn("Failed to fetch Hero Sliders info", error);
    return [] as HeroSlider[];
  }
};

export const getCategories = async (): Promise<Category[]> => {
  if (shouldSkipFetch) return [] as Category[];

  try {
    const response = await apiClient.get("/categories");
    return response.data?.data;
  } catch (error) {
    console.warn("Failed to fetch Category Info", error);
    return [] as Category[];
  }
};

export const getPopularCategories = async (): Promise<Category[]> => {
  if (shouldSkipFetch) return [] as Category[];

  try {
    const response = await apiClient.get("/categories/popular/list");
    return response.data?.data;
  } catch (error) {
    console.warn("Failed to fetch Popular Category Info", error);
    return [] as Category[];
  }
};

export const getBrands = async (): Promise<Brand[]> => {
  if (shouldSkipFetch) return [] as Brand[];

  try {
    const response = await apiClient.get("/brands");
    return response.data?.data;
  } catch (error) {
    console.warn("Failed to fetch Brands Info", error);
    return [] as Brand[];
  }
};

export const getFooterSliders = async (): Promise<FooterSlider[]> => {
  if (shouldSkipFetch) return [] as FooterSlider[];

  try {
    const response = await apiClient.get("/footer-sliders");
    return response.data?.data;
  } catch (error) {
    console.warn("Failed to fetch Footer Sliders Info", error);
    return [] as FooterSlider[];
  }
};

export const getTrendingProducts = async (): Promise<Product[]> => {
  if (shouldSkipFetch) return [] as Product[];

  try {
    const response = await apiClient.get("/trending-products"); 
    return response.data?.data;
  } catch (error) {
    console.warn("Failed to fetch Trending Products Info", error);
    return [] as Product[];
  }
};

export const getPopularProducts = async (): Promise<Product[]> => {
  if (shouldSkipFetch) return [] as Product[];

  try {
    const response = await apiClient.get("/popular-products"); 
    return response.data?.data;
  } catch (error) {
    console.warn("Failed to fetch Popular Products Info", error);
    return [] as Product[];
  }
};