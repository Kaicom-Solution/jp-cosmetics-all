import React, { useState } from "react";
import { Star, ShoppingCart, Heart, Eye, Sparkles } from "lucide-react";
import Link from "next/link";
import { Product } from "@/types";
import { useCartStore } from "@/store/cart-store";
import { showToast } from "@/utils/toast";
import { useWishlistStore } from "@/store/wishListStore";

interface ProductCardProps {
  product?: Product;
  className?: string;
  wishlisted: boolean;
}

const ProductCard: React.FC<ProductCardProps> = ({
  product = {
    id: 1,
    name: "Vaseline Intensive Care Cocoa Glow Lotion",
    slug: "35289-vaseline-intensive-care-cocoa-glow-lotion",
    product_type: "configurable",
    status: "active",
    short_description: null,
    long_description: null,
    ingredients: null,
    how_to_use: null,
    primary_image:
      "http://127.0.0.1:8000/product_images/1765946947_sku-11589-1.jpg",
    category: {
      id: 6,
      name: "Beauty Tools & Accessories",
      slug: "72350-beauty-tools-accessories",
    },
    brand: {
      id: 1,
      name: "Aura",
      slug: "aura",
    },
    created_at: "2025-12-17",
    default_attribute: {
      id: 1,
      product_id: 1,
      attribute_name: "Size",
      attribute_value: "200ml",
      unit_price: "795.00",
      stock: 20,
      min_order: 1,
      max_order: 5,
      discount_type: "percentage",
      discount_amount: "18.00",
      status: 1,
      is_default: 1,
      created_at: "2025-12-17T04:49:07.000000Z",
      updated_at: "2025-12-17T04:49:07.000000Z",
      discounted_price: 651.9,
      attribute_discount_amount: 143.1,
      discount_percentage: 18,
    },
  },
  className = "",
  wishlisted: wishlisted,
}) => {
  const [isWishlisted, setIsWishlisted] = useState(wishlisted || false);
  const [imageError, setImageError] = useState(false);
  const addItem = useCartStore((state) => state.addItem);
  const { remove, add } = useWishlistStore();

  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault();
    if (product.default_attribute.stock < 1) {
      showToast.error("Requested quantity not available in stock.");
      return;
    }
    addItem({
      product_id: product.id,
      product_name: product.name,
      attribute_value: product.default_attribute.attribute_value ?? "",
      product_attribute_id: product.default_attribute.id ?? null,
      // unit_price: product.default_attribute.discounted_price,
      unit_price: parseFloat(product.default_attribute.unit_price),
      quantity: 1,
      discount_amount: product.default_attribute.attribute_discount_amount,
      discount_percentage: product.default_attribute.discount_percentage,
      image: product.primary_image,
    });
    showToast.success("Product added to cart successfully!");
  };

  const handleQuickView = (e: React.MouseEvent) => {
    e.preventDefault();
    console.log("Quick view:", product.name);
    // Add your quick view modal logic here
  };

  const toggleWishlist = async (e: React.MouseEvent, productId: number) => {
    e.preventDefault();
    try {
      if (!isWishlisted) {
        await add(productId);
        setIsWishlisted(true);
        showToast.success("Product added to cart wishlist!");
      } else {
        await remove(productId);
        setIsWishlisted(false);
        showToast.success("Product removed to cart wishlist!");
      }
    } catch (error) {
      console.error("Wishlist action failed", error);
    }
  };

  const getBadgeStyles = (badge: string) => {
    const lowerBadge = badge.toLowerCase();
    if (lowerBadge === "new")
      return "bg-gradient-to-r from-blue-500 to-blue-600";
    if (lowerBadge === "hot" || lowerBadge === "sale")
      return "bg-gradient-to-r from-red-500 to-rose-600";
    return "bg-gradient-to-r from-pink-500 to-rose-600";
  };

  return (
    <Link
      href={product ? `/shop/${product.slug}` : "#"}
      className={`group relative bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 cursor-pointer ${className}`}
    >
      {/* Image Container */}
      <div className="relative h-64 w-full max-w-[666px] mx-auto overflow-hidden bg-gray-100">
        {/* Gradient Overlay on Hover */}
        <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10" />

        {/* Main Image (Default) */}
        <img
          src={
            imageError
              ? "/assets/img/product/product2.png"
              : product.primary_image
          }
          alt={product.name}
          onError={() => setImageError(true)}
          className="w-full h-full object-cover transition-all duration-500 group-hover:scale-110"
        />

        {/* Secondary Image (Hover - Fade In) */}
        <img
          src={product.primary_image}
          alt={`${product.name} alternate view`}
          className="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-all duration-500 scale-105 group-hover:scale-110"
        />

        {/* Badges */}
        <div className="absolute top-3 left-3 z-20 flex flex-col gap-2">
          {/* {product.badge && (
            <span
              className={`${getBadgeStyles(
                product.badge
              )} text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm flex items-center gap-1 animate-in fade-in slide-in-from-left-2 duration-500`}
            >
              <Sparkles className="w-3 h-3" />
              {product.badge}
            </span>
          )} */}

          {product?.default_attribute?.discount_percentage > 0 && (
            <span className="bg-gradient-to-r from-emerald-500 to-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm animate-in fade-in slide-in-from-left-2 duration-700">
              -{product?.default_attribute.discount_percentage}%
            </span>
          )}
        </div>

        {/* Quick Action Buttons */}
        <div className="absolute top-3 right-3 z-20 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0">
          {/* Wishlist Button */}
          <button
            onClick={(e) => toggleWishlist(e, product.id)}
            className={`cursor-pointer p-2.5 rounded-full backdrop-blur-md transition-all duration-300 hover:scale-110 active:scale-95 shadow-lg ${
              isWishlisted
                ? "bg-gradient-to-r from-pink-500 to-rose-600 text-white"
                : "bg-white/90 text-gray-700 hover:bg-pink-50"
            }`}
            aria-label="Add to wishlist"
          >
            <Heart
              className={`w-4 h-4 ${isWishlisted ? "fill-current" : ""}`}
            />
          </button>

          {/* Quick View Button */}
          {/* <button
            onClick={handleQuickView}
            className="p-2.5 bg-white/90 backdrop-blur-md rounded-full text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-all duration-300 hover:scale-110 active:scale-95 shadow-lg"
            aria-label="Quick view"
          >
            <Eye className="w-4 h-4" />
          </button> */}
        </div>

        {/* Add to Cart - Hover Button */}
        <div className="absolute bottom-0 left-0 right-0 z-20 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
          <button
            // onClick={handleAddToCart}
            onClick={handleAddToCart}
            className="w-full cursor-pointer py-3 px-4 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2 hover:scale-105 active:scale-95"
          >
            <ShoppingCart className="w-5 h-5" />
            Add to Cart
          </button>
        </div>
      </div>

      {/* Content */}
      <div className="p-5 space-y-3 h-[200px]">
        {/* Category/Brand */}
        {product.category && (
          <p className="text-xs font-medium text-pink-600 uppercase tracking-wide line-clamp-1">
            {product.category.name}
          </p>
        )}

        {/* Product Name */}
        <h3 className="text-base font-bold text-gray-900 group-hover:text-pink-600 transition-colors duration-300 line-clamp-2 min-h-[3rem]">
          {product.name}
        </h3>

        {/* Star Rating */}
        {/* <div className="flex items-center gap-2">
          <div className="flex gap-0.5">
            {[...Array(5)].map((_, i) => (
              <Star
                key={i}
                className={`w-4 h-4 transition-all duration-200 ${
                  i < product.rate
                    ? "text-amber-400 fill-amber-400"
                    : "text-gray-300 fill-gray-300"
                }`}
              />
            ))}
          </div>
          <span className="text-xs text-gray-500 font-medium">
            ({product.rate}.0)
          </span>
        </div> */}

        {/* Price Section */}
        <div className="flex items-center justify-between pt-2 border-t border-gray-100">
          <div className="flex items-baseline gap-2">
            <p className="text-2xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">
              BDT{" "}
              {product?.default_attribute?.discount_percentage > 0
                ? product?.default_attribute?.discounted_price.toFixed(2)
                : product?.default_attribute?.unit_price}
            </p>
            {product?.default_attribute?.discount_percentage > 0 && (
              <p className="text-sm text-gray-600 line-through ">
                BDT {product?.default_attribute?.unit_price}
              </p>
            )}
          </div>

          {/* Mobile Add to Cart Icon */}
          <button
            onClick={handleAddToCart}
            className="lg:hidden p-2 bg-gradient-to-r from-pink-500 to-rose-600 text-white rounded-full hover:from-pink-600 hover:to-rose-700 transition-all hover:scale-110 active:scale-95 shadow-md cursor-pointer"
            aria-label="Add to cart"
          >
            <ShoppingCart className="w-5 h-5" />
          </button>
        </div>

        {/* Savings Badge */}
        {product?.default_attribute?.discount_percentage > 0 && (
          <div className="flex items-center justify-center">
            <span className="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">
              Save BDT{" "}
              {product?.default_attribute?.attribute_discount_amount.toFixed(2)}
            </span>
          </div>
        )}
      </div>
    </Link>
  );
};

export default ProductCard;
