"use client";

import React, { useRef, useState } from "react";
import {
  Heart,
  ShieldCheck,
  ChevronRight,
  Minus,
  Plus,
  Flame,
  Package,
  Headphones,
  Facebook,
  Linkedin,
  MessageCircle,
} from "lucide-react";
import Link from "next/link";
import ProductCard from "@/components/home/ProductCard";
import { SingleProduct, Product } from "@/types";
import Image from "next/image";
import { useCartStore } from "@/store/cart-store";
import { showToast } from "@/utils/toast";
import { useWishlistStore } from "@/store/wishListStore";

interface responseProps {
  product: SingleProduct;
  relatedProduct: Product[];
}

const ProductDetails = ({ product, relatedProduct }: responseProps) => {
  const addItem = useCartStore((state) => state.addItem);
  const { remove, add } = useWishlistStore();
  const [main_image, setMainImage] = useState(product.primary_image);
  const [quantity, setQuantity] = useState(1);
  const [isWishlisted, setIsWishlisted] = useState(false);
  const [default_attribute, setDefaultAttribute] = useState(
    product.attributes[0],
  );
  const imgRef = useRef<HTMLImageElement>(null);
  const [activeTab, setActiveTab] = useState<
    "description" | "ingredients" | "howto" | "reviews"
  >("description");

  const handleQuantityChange = (type: "increase" | "decrease") => {
    if (type === "increase") {
      setQuantity((prev) => prev + 1);
    } else if (type === "decrease" && quantity > 1) {
      setQuantity((prev) => prev - 1);
    }
  };

  const toggleFullScreen = () => {
    const element = imgRef.current;

    if (element) {
      if (!document.fullscreenElement) {
        element.requestFullscreen().catch((err) => console.log(err));
      } else {
        document.exitFullscreen();
      }
    }
  };
  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault();
    if (default_attribute.stock < quantity) {
      showToast.error("Requested quantity not available in stock.");
      return;
    }
    addItem({
      product_id: product.id,
      product_name: product.name,
      attribute_value: default_attribute.attribute_value ?? "",
      product_attribute_id: default_attribute.id ?? null,
      unit_price: default_attribute.discounted_price,
      quantity: quantity,
      discount_amount: default_attribute.attribute_discount_amount,
      discount_percentage: default_attribute.discount_percentage,
      image: product.primary_image,
    });
    showToast.success("Product added to cart successfully!");
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

  const pageUrl = typeof window !== "undefined" ? window.location.href : "";

  const shareText = "Check out this product!";

  const openShareWindow = (url: string) => {
    window.open(url, "_blank", "noopener,noreferrer,width=600,height=500");
  };

  return (
    <div className="bg-white min-h-screen">
      {/* Breadcrumb */}
      <div className="px-[5%] py-4 bg-gray-50">
        <div className="flex items-center gap-2 text-sm text-gray-600">
          <Link
            href="/"
            className="hover:text-pink-600 transition-colors cursor-pointer"
          >
            Home
          </Link>
          <ChevronRight className="w-3.5 h-3.5" />
          <Link
            href="/shop"
            className="hover:text-pink-600 transition-colors cursor-pointer"
          >
            Shop
          </Link>
          <ChevronRight className="w-3.5 h-3.5" />
          <Link
            href={`/shop?page=1&category_id=${product.category.id}`}
            className="hover:text-pink-600 transition-colors cursor-pointer line-clamp-1"
          >
            {product.category.name}
          </Link>
          <ChevronRight className="w-3.5 h-3.5" />
          <span className="text-gray-900 font-medium line-clamp-1">
            {product.name}
          </span>
        </div>
      </div>

      {/* Main Product Section */}
      <div className="px-[5%] py-8">
        <div className="max-w-7xl mx-auto">
          <div className="grid grid-cols-1 lg:grid-cols-[auto_1fr] gap-6 lg:gap-8 items-start">
            {/* Left: Images */}
            <div className="space-y-4 w-full lg:w-[420px]">
              {/* Main Image */}
              <div className="relative bg-white border border-gray-200 rounded-lg overflow-hidden group aspect-square">
                <Image
                  ref={imgRef}
                  src={main_image}
                  alt={product.name}
                  className="w-full h-full object-contain p-4"
                  width={500}
                  height={500}
                />

                {/* Badges */}
                {/* <div className="absolute top-4 left-4 space-y-2">
                <div className="bg-yellow-400 text-gray-900 px-3 py-1.5 rounded text-xs font-bold">
                  {productData.badge}
                </div>
                <div className="bg-white text-gray-700 px-3 py-1 rounded text-xs">
                  DATE OF EXPIRY: {productData.expiryDate}
                </div>
              </div> */}

                {/* Fullscreen button */}
                <button
                  className="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm p-2 rounded hover:bg-white transition-colors cursor-pointer"
                  onClick={toggleFullScreen}
                >
                  <svg
                    className="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth={2}
                      d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"
                    />
                  </svg>
                </button>
              </div>

              {/* Thumbnail Images */}
              <div className="grid grid-cols-5 gap-2">
                {product.attribute_images.map((att_image, index) => (
                  <button
                    key={index}
                    onClick={() => setMainImage(att_image.image)}
                    className={`relative border rounded overflow-hidden transition-all cursor-pointer hover:border-pink-500 ${
                      main_image === att_image.image
                        ? "border-pink-500 border-2"
                        : "border-gray-200"
                    }`}
                  >
                    <img
                      src={att_image.image}
                      alt={`${product.name} view ${att_image.attribute_id}`}
                      className="w-full h-auto object-contain"
                    />
                  </button>
                ))}
              </div>

              {/* Social Share */}
              <div className="flex items-center gap-3 pt-4">
                {/* ✅ Facebook */}
                <button
                  onClick={() =>
                    openShareWindow(
                      `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(
                        pageUrl,
                      )}`,
                    )
                  }
                  className="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors cursor-pointer"
                >
                  <Facebook className="w-5 h-5" />
                </button>

                {/* ✅ X (Twitter) */}
                <button
                  onClick={() =>
                    openShareWindow(
                      `https://twitter.com/intent/tweet?url=${encodeURIComponent(
                        pageUrl,
                      )}&text=${encodeURIComponent(shareText)}`,
                    )
                  }
                  className="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center hover:bg-gray-800 transition-colors cursor-pointer"
                >
                  <svg
                    className="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                  </svg>
                </button>

                {/* ✅ LinkedIn */}
                <button
                  onClick={() =>
                    openShareWindow(
                      `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(
                        pageUrl,
                      )}`,
                    )
                  }
                  className="w-10 h-10 rounded-full bg-blue-700 text-white flex items-center justify-center hover:bg-blue-800 transition-colors cursor-pointer"
                >
                  <Linkedin className="w-5 h-5" />
                </button>

                {/* ✅ WhatsApp */}
                <button
                  onClick={() =>
                    openShareWindow(
                      `https://wa.me/?text=${encodeURIComponent(
                        shareText + " " + pageUrl,
                      )}`,
                    )
                  }
                  className="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition-colors cursor-pointer"
                >
                  <MessageCircle className="w-5 h-5" />
                </button>
              </div>
            </div>

            {/* Right: Product Info */}
            <div className="space-y-4">
              {/* Product Name */}
              <h1 className="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight">
                {product.name}
              </h1>

              {/* Size & Rating */}
              <div className="space-y-2">
                <span className="text-sm font-semibold text-gray-700">
                  Varients
                </span>
                <div className="flex items-center gap-2 flex-wrap">
                  {product.attributes &&
                    product.attributes.map((attr: any, idx: number) => (
                      <button
                        key={idx}
                        className={`${
                          default_attribute.id === attr.id
                            ? "border-pink-500 text-pink-600 bg-pink-50"
                            : ""
                        } px-4 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-pink-500 hover:text-pink-600 transition-all cursor-pointer hover:bg-pink-50`}
                        onClick={() => {
                          setDefaultAttribute(attr);
                        }}
                      >
                        {attr.attribute_value}
                      </button>
                    ))}
                </div>
              </div>
              {/* <div className="flex items-center gap-2 bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                <Star className="w-4 h-4 fill-white" />
                <span className="font-bold">{product.rating}</span>
                <span>| {productData.reviewCount} Reviews</span>
              </div> */}

              {/* Price */}
              <div className="flex items-center gap-3 pb-3 border-b flex-wrap">
                <span className="text-3xl lg:text-4xl font-bold text-pink-600">
                  ৳{default_attribute.discounted_price}
                </span>
                {default_attribute.discount_percentage > 0 && (
                  <>
                    <span className="text-lg lg:text-xl text-gray-400 line-through">
                      ৳{default_attribute.unit_price}
                    </span>

                    <span className="bg-purple-700 text-white px-2.5 py-1 rounded-md text-xs font-bold">
                      {default_attribute.discount_percentage}% OFF
                    </span>
                  </>
                )}
              </div>

              <div className="text-green-600 font-medium text-sm">
                {/* Save ৳{discountAmount.toFixed(2)} */}
              </div>

              {/* Quantity & Actions */}
              <div className="flex items-center gap-3 flex-wrap">
                <button
                  onClick={(e) => toggleWishlist(e, product.id)}
                  className={`w-12 h-12 rounded-lg flex items-center justify-center border-2 transition-all cursor-pointer flex-shrink-0 ${
                    isWishlisted
                      ? "bg-pink-50 border-pink-500 text-pink-600"
                      : "border-gray-300 text-gray-600 hover:border-pink-500 hover:text-pink-600"
                  }`}
                >
                  <Heart
                    className={`w-5 h-5 ${isWishlisted ? "fill-current" : ""}`}
                  />
                </button>

                <div className="flex items-center border-2 border-gray-300 rounded-lg overflow-hidden flex-shrink-0">
                  <button
                    onClick={() => handleQuantityChange("decrease")}
                    className="px-3 py-2.5 hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                    disabled={quantity <= 1}
                  >
                    <Minus className="w-4 h-4" />
                  </button>
                  <span className="px-5 py-2.5 font-bold text-base min-w-[50px] text-center border-x-2 border-gray-300">
                    {quantity}
                  </span>
                  <button
                    onClick={() => handleQuantityChange("increase")}
                    className="px-3 py-2.5 hover:bg-gray-100 transition-colors cursor-pointer"
                  >
                    <Plus className="w-4 h-4" />
                  </button>
                </div>

                <button
                  onClick={handleAddToCart}
                  className="min-w-48 py-3 px-6 bg-linear-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-bold rounded-lg transition-all duration-300 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer uppercase text-sm"
                >
                  ADD TO CART
                </button>
              </div>

              {/* Stock Status */}
              <div className="flex items-center gap-2 text-red-600 text-sm">
                <Flame className="w-4 h-4" />
                <span className="font-semibold">
                  Only {default_attribute.stock} items left in stock
                </span>
              </div>

              {/* Brief Description */}
              <div className="bg-gray-50 rounded-lg p-4 space-y-3">
                <h3 className="font-bold text-gray-900 text-base">
                  Brief Description
                </h3>
                <p className="text-gray-700 text-sm leading-relaxed">
                  {product.short_description}
                </p>
              </div>

              {/* Product Info Grid */}
              <div className="grid grid-cols-2 gap-x-8 gap-y-3 text-sm pt-2">
                {/* <div>
                  <span className="text-gray-600 block mb-1">SKU</span>
                  <p className="font-medium text-gray-900">{productData.sku}</p>
                </div> */}
                <div>
                  <span className="text-gray-600 block mb-1">Brands</span>
                  <p className="font-medium text-gray-900">
                    {product.brand.name}
                  </p>
                </div>
                <div className="col-span-2">
                  <span className="text-gray-600 block mb-1">Categories</span>
                  <p className="font-medium text-gray-900 text-xs leading-relaxed">
                    <Link
                      className="text-pink-600"
                      href={`/shop?page=1&category_id=${product.category.id}`}
                    >
                      {product.category.name}
                    </Link>
                  </p>
                </div>
                {/* <div className="col-span-2">
                  <span className="text-gray-600 block mb-1">Tags</span>
                  <p className="font-medium text-gray-900">
                    {productData.tags.join(", ")}
                  </p>
                </div> */}
              </div>
            </div>
          </div>
        </div>

        {/* Product Details Tabs */}
        <div className="max-w-7xl mx-auto mt-8">
          <div className="bg-white border border-gray-200 rounded-lg overflow-hidden">
            {/* Tab Headers */}
            <div className="flex border-b border-gray-200 overflow-x-auto">
              <button
                onClick={() => setActiveTab("description")}
                className={`flex-shrink-0 py-4 px-6 font-semibold transition-colors cursor-pointer ${
                  activeTab === "description"
                    ? "text-pink-600 border-b-3 border-pink-600 bg-pink-50"
                    : "text-gray-600 hover:text-pink-600 hover:bg-gray-50"
                }`}
              >
                Description
              </button>
              <button
                onClick={() => setActiveTab("ingredients")}
                className={`flex-shrink-0 py-4 px-6 font-semibold transition-colors cursor-pointer ${
                  activeTab === "ingredients"
                    ? "text-pink-600 border-b-3 border-pink-600 bg-pink-50"
                    : "text-gray-600 hover:text-pink-600 hover:bg-gray-50"
                }`}
              >
                Ingredients
              </button>
              <button
                onClick={() => setActiveTab("howto")}
                className={`flex-shrink-0 py-4 px-6 font-semibold transition-colors cursor-pointer ${
                  activeTab === "howto"
                    ? "text-pink-600 border-b-3 border-pink-600 bg-pink-50"
                    : "text-gray-600 hover:text-pink-600 hover:bg-gray-50"
                }`}
              >
                How to Use
              </button>
              {product.reviews.length > 0 && (
                <button
                  onClick={() => setActiveTab("reviews")}
                  className={`flex-shrink-0 py-4 px-6 font-semibold transition-colors cursor-pointer ${
                    activeTab === "reviews"
                      ? "text-pink-600 border-b-3 border-pink-600 bg-pink-50"
                      : "text-gray-600 hover:text-pink-600 hover:bg-gray-50"
                  }`}
                >
                  Reviews ({product.reviews.length || 0})
                </button>
              )}
            </div>

            {/* Tab Content */}
            <div className="p-6 lg:p-8">
              {activeTab === "description" && (
                <div className="space-y-4">
                  <p className="text-gray-700 leading-relaxed">
                    {product.long_description}
                  </p>
                </div>
              )}

              {activeTab === "ingredients" && (
                <div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4">
                    Full Ingredients List
                  </h3>
                  <p className="text-gray-700 leading-relaxed">
                    {product.ingredients}
                  </p>
                </div>
              )}

              {activeTab === "howto" && (
                <div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4">
                    How to Use
                  </h3>
                  <p className="text-gray-700 leading-relaxed">
                    {product.how_to_use}
                  </p>
                </div>
              )}

              {activeTab === "reviews" && (
                <div className="space-y-4">
                  {product.reviews.map((review, index) => (
                    <div
                      key={index}
                      className="flex space-x-4 items-start bg-gray-50 p-4 rounded-lg shadow-sm"
                    >
                      <p className="w-10 h-10 flex justify-center items-center rounded-full bg-linear-to-r from-pink-500 to-pink-600 hover:from-pink-600 text-white font-bold text-lg">
                        {review.customer_name?.slice(0, 1)}
                      </p>
                      <div className="flex-1">
                        <div className="flex justify-between items-center mb-1">
                          <p className="font-semibold">
                            {review.customer_name}
                          </p>
                        </div>
                        <div className="flex text-yellow-400 mb-1">
                          {Array.from({ length: 5 }, (_, i) => (
                            <span key={i}>
                              {i < review?.rating ? "★" : "☆"}
                            </span>
                          ))}
                        </div>
                        <p className="text-gray-700">{review.review}</p>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Trust Badges */}
        <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 py-8 border-t">
          <div className="flex flex-col items-center text-center">
            <Package className="w-12 h-12 text-gray-900 mb-3" />
            <h4 className="font-bold text-gray-900 mb-1">
              100% Genuine Products
            </h4>
            <p className="text-sm text-gray-600">
              Authentic products guaranteed
            </p>
          </div>

          <div className="flex flex-col items-center text-center">
            <ShieldCheck className="w-12 h-12 text-gray-900 mb-3" />
            <h4 className="font-bold text-gray-900 mb-1">
              100% Secure Payments
            </h4>
            <p className="text-sm text-gray-600">
              Your payment is safe with us
            </p>
          </div>

          <div className="flex flex-col items-center text-center">
            <Headphones className="w-12 h-12 text-gray-900 mb-3" />
            <h4 className="font-bold text-gray-900 mb-1">
              Help Center (+8809666737475)
            </h4>
            <p className="text-sm text-gray-600">24/7 customer support</p>
          </div>
        </div>

        {/* Related Products */}
        {relatedProduct.length > 0 && (
          <div className="max-w-7xl mx-auto mt-12">
            <div className="flex items-center justify-between mb-8">
              <h2 className="text-2xl lg:text-3xl font-bold text-gray-900">
                Related Products
              </h2>
              <Link
                href="/shop"
                className="text-pink-600 hover:text-pink-700 font-semibold flex items-center gap-2 group cursor-pointer"
              >
                View All
                <ChevronRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
              </Link>
            </div>
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              {relatedProduct?.map((product, index) => (
                <ProductCard key={index} product={product} wishlisted={false} />
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default ProductDetails;
