"use client";

interface BrandPagination {
  current_page: number;
  data: Brand[];
  total: number;
}

interface BrandResponse {
  success: boolean;
  message: string;
  data: BrandPagination;
}

import { useEffect, useState } from "react";

import { ChevronDown, Filter } from "lucide-react";
import { SlidersHorizontal, X } from "lucide-react";

import apiClient from "@/lib/axios";
import { showToast } from "@/utils/toast";

import { CategoryResponse, Category } from "@/types/category";
import { ProductList, Brand } from "@/types";
import { useRouter, useSearchParams } from "next/navigation";
import ProductCard from "@/components/home/ProductCard";
import Pagination from "@/components/shop/Pagination";
import ActiveFilters from "@/components/shop/ActiveFilters";
import MobileFilters from "@/components/shop/MobileFilters";
import CategoryItem from "@/components/shop/CategoryItem";
import ShopPageSkeleton from "./ShopPageSkeleton";

export default function ShopPageClient() {
  const [categoryTree, setCategoryTree] = useState<Category[]>();
  const [brands, setBrands] = useState<Brand[]>([]);
  const [productList, setProductList] = useState<ProductList | null>(null);
  const [loading, setLoading] = useState(true);
  const [mobileFiltersOpen, setMobileFiltersOpen] = useState(false);

  const searchParams = useSearchParams();
  const router = useRouter();

  const getCategory = async () => {
    try {
      const res = await apiClient.get<CategoryResponse>("/categories/tree");

      if (res.data.success) {
        setCategoryTree(res.data.data);
      }
    } catch (error: any) {
      showToast.error(
        error?.response?.data?.message ||
          "Can not get subscription plans at this moment",
      );
    }
  };

  const getBrands = async () => {
    try {
      const response = await apiClient.get<BrandResponse>("/brands");
      setBrands(response.data.data.data);
    } catch (error) {
      console.warn("Failed to fetch Brands Info", error);
      setBrands([]);
    }
  };

  const getProducts = async (query: string) => {
    try {
      const res = await apiClient.get<{
        success: boolean;
        data: ProductList;
        message: string;
      }>(`/products${query ? `?${query}` : ""}`);

      if (res.data.success) {
        setProductList(res.data.data);
      }
    } catch (error: any) {
      showToast.error(
        error?.response?.data?.message || "Can not get products at this moment",
      );
    } finally {
      setLoading(false);
    }
  };

  const updateQueryParams = (updates: Record<string, string | null>) => {
    const params = new URLSearchParams(searchParams.toString());

    Object.entries(updates).forEach(([key, value]) => {
      if (!value) {
        params.delete(key);
      } else {
        params.set(key, value);
      }
    });

    const newQuery = params.toString();

    router.replace(`?${newQuery}`, { scroll: true });
    // setQuery(newQuery);
  };

  const handleBrandChange = (slug: string) => {
    const currentBrand = searchParams.get("brand");

    updateQueryParams({
      brand: currentBrand === slug ? null : slug,
      page: "1",
    });
  };

  const handleSortChange = (value: string) => {
    updateQueryParams({
      sort: value,
      page: "1",
    });
  };

  const handlePageChange = (page: number) => {
    updateQueryParams({
      page: page.toString(),
    });
  };

  useEffect(() => {
    getCategory();
    getBrands();
  }, []);

  useEffect(() => {
    // setLoading(true);
    const queryString = searchParams.toString();
    getProducts(queryString);
  }, [searchParams]);

  useEffect(() => {
    if (mobileFiltersOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }

    return () => {
      document.body.style.overflow = "";
    };
  }, [mobileFiltersOpen]);

  const clearAllFilters = () => {
    const params = new URLSearchParams(searchParams.toString());
    ["category", "brand", "sort", "page"].forEach((key) => params.delete(key));

    router.replace(`?${params.toString()}`);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const activeCategory = searchParams.get("category");
  const activeBrand = searchParams.get("brand");

  // Inside your ShopPageClient component, before return
  const activeFiltersCount =
    (searchParams.get("category") ? 1 : 0) +
    (searchParams.get("brand") ? 1 : 0) +
    (searchParams.get("sort") && searchParams.get("sort") !== "newest" ? 1 : 0);

  return (
    <>
      {loading ? (
        <ShopPageSkeleton />
      ) : (
        <div className="bg-gradient-to-b from-pink-50/30 to-white min-h-screen">
          <div className="px-[5%] py-8 lg:py-12">
            <div className="mb-8 text-center">
              <h1 className="text-4xl md:text-5xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent mb-2">
                Shop All Products
              </h1>
              <p className="text-gray-600">
                Discover our exclusive collection of premium beauty products
              </p>
            </div>

            <div className="flex items-center justify-between lg:mb-2 gap-4">
              <button
                onClick={() => setMobileFiltersOpen(true)}
                className="lg:hidden flex items-center gap-2 px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl hover:border-pink-400 transition-colors"
              >
                <Filter className="w-4 h-4" />
                <span className="font-medium">Filters</span>
              </button>

              <div className="text-sm text-gray-600 font-medium hidden lg:block">
                <span className="font-semibold text-gray-900">
                  {productList?.meta?.total ?? 0}
                </span>{" "}
                products found
              </div>

              <div className="relative">
                <select
                  value={searchParams.get("sort") ?? "newest"}
                  onChange={(e) => handleSortChange(e.target.value)}
                  className="appearance-none pl-4 pr-10 py-2.5 bg-white border-2 border-gray-200 rounded-xl hover:border-pink-400 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all cursor-pointer text-sm font-medium"
                >
                  <option value="newest" className="font-medium text-gray-700">
                    Newest First
                  </option>
                  <option
                    value="price_low"
                    className="font-medium text-gray-700"
                  >
                    Price: Low to High
                  </option>
                  <option
                    value="price_high"
                    className="font-medium text-gray-700"
                  >
                    Price: High to Low
                  </option>
                  <option
                    value="name_asc"
                    className="font-medium text-gray-700"
                  >
                    Name (A-Z)
                  </option>
                  <option
                    value="name_desc"
                    className="font-medium text-gray-700"
                  >
                    Name (Z-A)
                  </option>
                </select>
                <ChevronDown className="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <div className="text-sm text-gray-600 font-medium lg:hidden mt-2 mb-6">
              <span className="font-semibold text-gray-900">
                {productList?.meta?.total ?? 0}
              </span>{" "}
              products found
            </div>

            {(activeCategory || activeBrand) && (
              <ActiveFilters
                activeCategory={activeCategory}
                activeBrand={activeBrand}
                updateQueryParams={updateQueryParams}
              />
            )}

            <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
              <aside className="hidden lg:block lg:col-span-1">
                <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6 sticky top-28">
                  <div className="flex items-center justify-between mb-4">
                    <h2 className="text-xl font-bold text-gray-900 flex items-center gap-2">
                      <SlidersHorizontal className="w-5 h-5 text-pink-600" />
                      Filters
                    </h2>

                    {activeFiltersCount > 0 && (
                      <button
                        onClick={clearAllFilters}
                        className="text-xs text-pink-600 hover:text-pink-700 font-semibold cursor-pointer"
                      >
                        Clear
                      </button>
                    )}
                  </div>

                  {/* Category Filter */}
                  <div className="border-b border-gray-100 pb-6">
                    <h3 className="font-semibold text-gray-900 mb-3 text-sm uppercase tracking-wide">
                      Category
                    </h3>
                    <div className="space-y-2.5 max-h-[250px] overflow-y-auto">
                      {categoryTree &&
                        categoryTree.map((cat) => (
                          <CategoryItem
                            key={cat.id}
                            category={cat}
                            activeCategory={searchParams.get("category")}
                            onSelect={(slug) =>
                              updateQueryParams({
                                category: slug,
                                page: "1",
                              })
                            }
                          />
                        ))}
                    </div>
                  </div>

                  {/* Brand Filter */}
                  <div className="border-b border-gray-100 pb-6">
                    <h3 className="font-semibold text-gray-900 mb-3 text-sm uppercase tracking-wide">
                      Brand
                    </h3>
                    <div className="space-y-2.5 max-h-[250px] overflow-y-auto">
                      {brands.map((brand) => {
                        const active = searchParams.get("brand") === brand.slug;

                        return (
                          <label
                            key={brand.id}
                            onClick={() => handleBrandChange(brand.slug)}
                            className={`flex items-center gap-3 cursor-pointer group ${
                              active ? "text-pink-600 font-semibold" : ""
                            }`}
                          >
                            <span className="text-sm">{brand.name}</span>
                          </label>
                        );
                      })}
                    </div>
                  </div>
                </div>
              </aside>

              <main className="lg:col-span-3">
                {productList && productList.data.length > 0 ? (
                  <>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6">
                      {productList.data.map((product) => (
                        <ProductCard
                          key={product.id}
                          product={product}
                          wishlisted={false}
                        />
                      ))}
                    </div>

                    <Pagination
                      paginationData={productList.meta}
                      onPageChange={handlePageChange}
                    />
                  </>
                ) : (
                  <div className="flex flex-col items-center justify-center py-20 px-4">
                    <div className="w-20 h-20 bg-gradient-to-br from-pink-100 to-rose-100 rounded-full flex items-center justify-center mb-6">
                      <Filter className="w-10 h-10 text-pink-400" />
                    </div>
                    <h3 className="text-2xl font-bold text-gray-900 mb-2">
                      No products found
                    </h3>
                    <p className="text-gray-600 mb-6 text-center max-w-md">
                      We couldn't find any products matching your current
                      filters. Try adjusting your selections.
                    </p>
                    <button
                      onClick={clearAllFilters}
                      className="px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-semibold rounded-xl hover:from-pink-600 hover:to-rose-700 transition-all shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 cursor-pointer"
                    >
                      Clear all filters
                    </button>
                  </div>
                )}
              </main>
            </div>
          </div>

          {mobileFiltersOpen && (
            <MobileFilters
              isOpen={mobileFiltersOpen}
              categoryTree={categoryTree || []}
              brands={brands}
              setMobileFiltersOpen={setMobileFiltersOpen}
              handleBrandChange={handleBrandChange}
              updateQueryParams={updateQueryParams}
            />
          )}
        </div>
      )}
    </>
  );
}
