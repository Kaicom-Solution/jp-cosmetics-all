import { FilterOption, ProductFilters } from "@/types";
import { SlidersHorizontal } from "lucide-react";

const DesktopFilters = ({
  filters,
  categories,
  brands,
  onFilterChange,
  onPriceChange,
  onClearAll,
  activeFiltersCount,
}: {
  filters: { category: string[]; brand: string[]; price: number };
  categories: FilterOption[];
  brands: FilterOption[];
  onFilterChange: (type: "category" | "brand", value: string) => void;
  onPriceChange: (value: number) => void;
  onClearAll: () => void;
  activeFiltersCount: number;
}) => (
  <aside className="hidden lg:block lg:col-span-1">
    <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6 sticky top-28">
      <div className="flex items-center justify-between mb-4">
        <h2 className="text-xl font-bold text-gray-900 flex items-center gap-2">
          <SlidersHorizontal className="w-5 h-5 text-pink-600" />
          Filters
        </h2>
        {activeFiltersCount > 0 && (
          <button
            onClick={onClearAll}
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
        <div className="space-y-2.5">
          {categories.map((cat) => (
            <label
              key={cat.id}
              className="flex items-center gap-3 cursor-pointer group"
            >
              <input
                type="checkbox"
                value={cat.name}
                checked={filters.category.includes(cat.id.toString())}
                onChange={() => onFilterChange("category", cat.id.toString())}
                className="w-4 h-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500 focus:ring-2 cursor-pointer"
              />
              <span className="text-gray-700 group-hover:text-pink-600 transition-colors text-sm font-medium">
                {cat.name}
              </span>
            </label>
          ))}
        </div>
      </div>

      {/* Brand Filter */}
      <div className="border-b border-gray-100 pb-6">
        <h3 className="font-semibold text-gray-900 mb-3 text-sm uppercase tracking-wide">
          Brand
        </h3>
        <div className="space-y-2.5">
          {brands.map((brand) => (
            <label
              key={brand.id}
              className="flex items-center gap-3 cursor-pointer group"
            >
              <input
                type="checkbox"
                value={brand.name}
                checked={filters.brand.includes(brand.id.toString())}
                onChange={() => onFilterChange("brand", brand.id.toString())}
                className="w-4 h-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500 focus:ring-2 cursor-pointer"
              />
              <span className="text-gray-700 group-hover:text-pink-600 transition-colors text-sm font-medium">
                {brand.name}
              </span>
            </label>
          ))}
        </div>
      </div>

      {/* Price Range */}
      {/* <div>
        <div className="flex items-center justify-between mb-3">
          <h3 className="font-semibold text-gray-900 text-sm uppercase tracking-wide">
            Price Range
          </h3>
          <span className="text-pink-600 font-bold text-sm">
            BDT {filters.price}
          </span>
        </div>
        <input
          type="range"
          min="0"
          max="1000"
          step="10"
          value={filters.price}
          onChange={(e) => onPriceChange(Number(e.target.value))}
          className="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-pink-600"
          style={{
            background: `linear-gradient(to right, rgb(236, 107, 129) 0%, rgb(236, 107, 129) ${
              (filters.price / 1000) * 100
            }%, rgb(229, 231, 235) ${
              (filters.price / 1000) * 100
            }%, rgb(229, 231, 235) 100%)`,
          }}
        />
        <div className="flex justify-between text-xs text-gray-500 mt-2">
          <span>BDT 0</span>
          <span>BDT 1000</span>
        </div>
      </div> */}
    </div>
  </aside>
);

export default DesktopFilters;
