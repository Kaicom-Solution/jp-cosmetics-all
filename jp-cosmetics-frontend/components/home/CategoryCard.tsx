import { Category } from '@/types';
import Link from 'next/link';
import React from 'react';


// Define the props for the component
interface CategoryCardProps {
  category: Category;
  className?: string; // Allow passing extra classes (like margins) from parent
}

const CategoryCard: React.FC<CategoryCardProps> = ({ category, className = '' }) => {
  return (
    <Link href={`/shop?page=1&category=${category.slug}`}>
    <div className={`group rounded-xl overflow-clip scroll-fade-up cursor-pointer ${className}`}>
      {/* Image Container */}
      <div className="h-[250px] overflow-clip">
        {/* Using standard <img> tag for direct compatibility. 
          For better performance in Next.js, consider using <Image fill ... /> 
        */}
        <img
          src={category.image}
          alt={category.name}
          className="w-full h-full object-cover aspect-auto group-hover:scale-110 duration-500"
        />
      </div>

      {/* Text Content */}
      <div className="p-5 bg-gray-50 text-center font-semibold group-hover:bg-[#ec6b81] space-y-1 duration-500">
        <p className="text-xl group-hover:text-white capitalize min-h-14">
          {category.name}
        </p>
      </div>
    </div>
    </Link>
  );
};

export default CategoryCard;