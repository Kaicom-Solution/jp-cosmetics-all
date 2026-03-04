"use client";

import ProductCard from "@/components/home/ProductCard";
import { useState } from "react";
import { ChevronDown, Filter } from "lucide-react";
import { PaginationData, Product, ProductFilters } from "@/types";
import ActiveFilterTags from "./ActiveFilterTags";
import DesktopFilters from "./DesktopFilters";
import MobileFilters from "./MobileFilters";
import Pagination from "./Pagination";

const ProductListComponent = ({
  products,
  paginationData,
  filterOptions,
  onPageChange,
  onFilterChange,
  onSortChange
}: {
  products: Product[];
  paginationData: PaginationData;
  filterOptions: ProductFilters;
  onPageChange: (page: number) => void;
  onFilterChange: (filters: { category: string[]; brand: string[]; price: number }) => void;
  onSortChange: (sort: string) => void;
}) => {
  const [sortBy, setSortBy] = useState("featured");
  const [mobileFiltersOpen, setMobileFiltersOpen] = useState(false);
  const [filters, setFilters] = useState<{ category: string[]; brand: string[]; price: number }>({
    category: [],
    brand: [],
    price: 1000
  });

  const handleFilterChange = (type: 'category' | 'brand', value: string) => {
    const newFilters = { ...filters };
    const list = newFilters[type];
    const newList = list.includes(value)
      ? list.filter((item) => item !== value)
      : [...list, value];
    
    newFilters[type] = newList;
    setFilters(newFilters);
    onFilterChange(newFilters);
  };

  const handlePriceChange = (value: number) => {
    const newFilters = { ...filters, price: value };
    setFilters(newFilters);
    onFilterChange(newFilters);
  };

  const clearAllFilters = () => {
    const newFilters = { category: [], brand: [], price: 1000 };
    setFilters(newFilters);
    onFilterChange(newFilters);
  };

  const removeFilter = (type: 'category' | 'brand', value: string) => {
    if (type === 'category' && value === '') {
      const newFilters = { ...filters, price: 1000 };
      setFilters(newFilters);
      onFilterChange(newFilters);
    } else {
      handleFilterChange(type, value);
    }
  };

  const handleSortChange = (value: string) => {
    setSortBy(value);
    onSortChange(value);
  };

  const activeFiltersCount = filters.category.length + filters.brand.length + (filters.price < 1000 ? 1 : 0);

  return (
    <div className="bg-gradient-to-b from-pink-50/30 to-white min-h-screen">
      <div className="px-[5%] py-8 lg:py-12">
        
        <div className="mb-8 text-center">
          <h1 className="text-4xl md:text-5xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent mb-2">
            Shop All Products
          </h1>
          <p className="text-gray-600">Discover our exclusive collection of premium beauty products</p>
        </div>

        <div className="flex items-center justify-between lg:mb-6 gap-4">
          <button
            onClick={() => setMobileFiltersOpen(true)}
            className="lg:hidden flex items-center gap-2 px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl hover:border-pink-400 transition-colors"
          >
            <Filter className="w-4 h-4" />
            <span className="font-medium">Filters</span>
            {activeFiltersCount > 0 && (
              <span className="bg-gradient-to-r from-pink-500 to-rose-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {activeFiltersCount}
              </span>
            )}
          </button>

          <div className="text-sm text-gray-600 font-medium hidden lg:block">
            <span className="font-semibold text-gray-900">{paginationData.total}</span> products found
          </div>

          <div className="relative">
            <select
              value={sortBy}
              onChange={(e) => handleSortChange(e.target.value)}
              className="appearance-none pl-4 pr-10 py-2.5 bg-white border-2 border-gray-200 rounded-xl hover:border-pink-400 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all cursor-pointer text-sm font-medium"
            >
              <option value="newest" className="font-medium text-gray-700">Newest First</option>
              <option value="price_low" className="font-medium text-gray-700">Price: Low to High</option>
              <option value="price_high" className="font-medium text-gray-700">Price: High to Low</option>
              <option value="name_asc" className="font-medium text-gray-700">Name (A-Z)</option>
              <option value="name_desc" className="font-medium text-gray-700">Name (Z-A)</option>
            </select>
            <ChevronDown className="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
          </div>
        </div>

        <div className="text-sm text-gray-600 font-medium lg:hidden mt-2 mb-6">
            <span className="font-semibold text-gray-900">{paginationData.total}</span> products found
          </div>

        <ActiveFilterTags
          filters={filters}
          categories={filterOptions.categories}
          brands={filterOptions.brands}
          onRemove={removeFilter}
          onClearAll={clearAllFilters}
        />

        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <DesktopFilters
            filters={filters}
            categories={filterOptions.categories}
            brands={filterOptions.brands}
            onFilterChange={handleFilterChange}
            onPriceChange={handlePriceChange}
            onClearAll={clearAllFilters}
            activeFiltersCount={activeFiltersCount}
          />

          <main className="lg:col-span-3">
            {products.length > 0 ? (
              <>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6">
                  {products.map((product) => (
                    <ProductCard key={product.id} product={product} wishlisted={false} />
                  ))}
                </div>

                <Pagination
                  paginationData={paginationData}
                  onPageChange={onPageChange}
                />
              </>
            ) : (
              <div className="flex flex-col items-center justify-center py-20 px-4">
                <div className="w-20 h-20 bg-gradient-to-br from-pink-100 to-rose-100 rounded-full flex items-center justify-center mb-6">
                  <Filter className="w-10 h-10 text-pink-400" />
                </div>
                <h3 className="text-2xl font-bold text-gray-900 mb-2">No products found</h3>
                <p className="text-gray-600 mb-6 text-center max-w-md">
                  We couldn't find any products matching your current filters. Try adjusting your selections.
                </p>
                <button 
                  onClick={clearAllFilters}
                  className="px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-semibold rounded-xl hover:from-pink-600 hover:to-rose-700 transition-all shadow-lg hover:shadow-xl hover:scale-105 active:scale-95"
                >
                  Clear all filters
                </button>
              </div>
            )}
          </main>
        </div>
      </div>

      <MobileFilters
        isOpen={mobileFiltersOpen}
        filters={filters}
        categories={filterOptions.categories}
        brands={filterOptions.brands}
        onClose={() => setMobileFiltersOpen(false)}
        onFilterChange={handleFilterChange}
        onPriceChange={handlePriceChange}
        onClearAll={clearAllFilters}
        activeFiltersCount={activeFiltersCount}
      />
    </div>
  );
};

export default ProductListComponent;
