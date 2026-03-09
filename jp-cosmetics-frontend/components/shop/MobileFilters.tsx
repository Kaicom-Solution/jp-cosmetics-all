"use client";

import { SlidersHorizontal, X } from "lucide-react";
import { useSearchParams, useRouter } from "next/navigation";
import { Category } from "@/types/category";
import { Brand } from "@/types";
import CategoryItem from "./CategoryItem";

interface MobileFiltersProps {
  isOpen: boolean;
  categoryTree: Category[];
  brands: Brand[];
  setMobileFiltersOpen: (open: boolean) => void;
  handleBrandChange: (slug: string) => void;
  updateQueryParams: (updates: Record<string, string | null>) => void;
}

const MobileFilters = ({
  isOpen,
  categoryTree,
  brands,
  setMobileFiltersOpen,
  handleBrandChange,
  updateQueryParams,
}: MobileFiltersProps) => {
  const searchParams = useSearchParams();
  const router = useRouter();

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 lg:hidden">
      <div
        className="absolute inset-0 bg-black/50 backdrop-blur-sm"
        onClick={() => setMobileFiltersOpen(false)}
      />

      <div className="absolute right-0 top-0 bottom-0 w-full max-w-sm bg-white shadow-2xl overflow-y-auto">
        <div className="p-6 space-y-6">
          {/* Header */}
          <div className="flex items-center justify-between pb-4 border-b border-gray-200">
            <h2 className="text-xl font-bold text-gray-900 flex items-center gap-2">
              <SlidersHorizontal className="w-5 h-5 text-pink-600" />
              Filters
            </h2>

            <button
              onClick={() => setMobileFiltersOpen(false)}
              className="p-2 hover:bg-gray-100 rounded-full cursor-pointer"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Category */}
          <div className="border-b pb-6">
            <h3 className="font-semibold text-gray-900 mb-3 text-sm uppercase">
              Category
            </h3>
            <div className="space-y-2">
              {categoryTree.map((cat) => (
                <CategoryItem
                  key={cat.id}
                  category={cat}
                  activeCategory={searchParams.get("category")}
                  onSelect={(slug:any) =>
                    updateQueryParams({
                      category: slug,
                      page: "1",
                    })
                  }
                />
              ))}
            </div>
          </div>

          {/* Brand */}
          <div className="border-b pb-6">
            <h3 className="font-semibold text-gray-900 mb-3 text-sm uppercase">
              Brand
            </h3>
            <div className="space-y-2">
              {brands.map((brand) => {
                const active = searchParams.get("brand") === brand.slug;
                return (
                  <button
                    key={brand.id}
                    onClick={() => {
                      handleBrandChange(brand.slug);
                      setMobileFiltersOpen(false);
                    }}
                    className={`block w-full text-left text-sm ${
                      active ? "text-pink-600 font-semibold" : ""
                    }`}
                  >
                    {brand.name}
                  </button>
                );
              })}
            </div>
          </div>

          {/* Clear All */}
          <button
            onClick={() => {
              router.replace("/shop");
              setMobileFiltersOpen(false);
            }}
            className="w-full py-3 border border-gray-300 rounded-xl"
          >
            Clear Filters
          </button>
        </div>
      </div>
    </div>
  );
};

export default MobileFilters;
