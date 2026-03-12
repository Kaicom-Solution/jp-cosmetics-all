import {Category } from "@/types/category";

import { useState } from "react";

import { ChevronDown, ChevronRight } from "lucide-react";

interface CategoryItemProps {
  category: Category;
  level?: number;
  onSelect: (slug: string) => void;
  activeCategory?: string | null;
}

export default function CategoryItem({
  category,
  level = 0,
  onSelect,
  activeCategory,
}: CategoryItemProps) {
  const [open, setOpen] = useState(false);
  const hasChildren = category.children?.length > 0;

  const isActive = activeCategory === category.slug;

  return (
    <div>
      <button
        onClick={() => {
          setOpen(!open);
          onSelect(category.slug);
        }}
        className={`flex items-center justify-between w-full cursor-pointer rounded-md px-2 py-1 ${
          isActive ? "text-pink-600 font-semibold" : "hover:text-pink-600"
        }`}
        style={{ paddingLeft: level * 12 }}
      >
        <span className="text-sm">{category.name}</span>

        {hasChildren && (
          <span className="p-1">
            {open ? <ChevronDown size={16} /> : <ChevronRight size={16} />}
          </span>
        )}
      </button>

      {open && hasChildren && (
        <div className="mt-1 space-y-1">
          {category.children.map((child) => (
            <CategoryItem
              key={child.id}
              category={child}
              level={level + 1}
              activeCategory={activeCategory}
              onSelect={onSelect}
            />
          ))}
        </div>
      )}
    </div>
  );
}