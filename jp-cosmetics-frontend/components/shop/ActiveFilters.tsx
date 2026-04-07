"use client";
import { X } from "lucide-react";

interface ActiveFiltersProps {
  activeCategory?: string | null;
  activeBrand?: string | null;
  updateQueryParams: (updates: Record<string, string | null>) => void;
}

export default function ActiveFilters({
  activeCategory,
  activeBrand,
  updateQueryParams,
}: ActiveFiltersProps) {
  if (!activeCategory && !activeBrand) return null;

  return (
    <div className="flex flex-wrap gap-2 mb-6">
      {activeCategory && (
        <div className="flex items-center gap-2 bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-sm">
          Category: {activeCategory}
          <button
            className="cursor-pointer"
            onClick={() =>
              updateQueryParams({
                category: null,
                page: "1",
              })
            }
          >
            <X size={14} />
          </button>
        </div>
      )}

      {activeBrand && (
        <div className="flex items-center gap-2 bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-sm">
          Brand: {activeBrand}
          <button
            className="cursor-pointer"
            onClick={() =>
              updateQueryParams({
                brand: null,
                page: "1",
              })
            }
          >
            <X size={14} />
          </button>
        </div>
      )}
    </div>
  );
}
