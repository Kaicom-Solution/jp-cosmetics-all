"use client";

import { useEffect, useState } from "react";
import {
  Search,
  User,
  Heart,
  ShoppingCart,
  Menu,
  X,
  LogIn,
} from "lucide-react";
import { BusinessInfo } from "@/types";
import apiClient from "@/lib/axios";
import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import Image from "next/image";
import { useCartStore } from "@/store/cart-store";
import { useAuthStore } from "@/store/authStore";
import { useWishlistStore } from "@/store/wishListStore";
import SubHeader from "./SubHeader";

const navdata = [
  { id: "home", label: "Home", link: "/" },
  { id: "shop", label: "Shop", link: "/shop" },
  { id: "contact", label: "Contact", link: "/contact" },
  { id: "blog", label: "Blog", link: "/blog" },
];

interface HeaderProps {
  data: BusinessInfo;
}

export default function Header({ data }: HeaderProps) {
  const pathname = usePathname();
  const headerLogo = data.header_logo || "/assets/img/jp-cosmetica-logo.png";
  const { items } = useCartStore();
  const user = useAuthStore().user;
  const wishlistItem = useWishlistStore().items;

  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState("");
  const [productList, setProductList] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);
  const router = useRouter();

  useEffect(() => {
    if (!searchOpen || !searchQuery.trim()) {
      setProductList([]);
      return;
    }

    const controller = new AbortController();
    const delayDebounce = setTimeout(async () => {
      try {
        setLoading(true);

        const res = await apiClient.get<{
          success: boolean;
          data: any[];
        }>(`/products/search?query=${encodeURIComponent(searchQuery)}`, {
          signal: controller.signal,
        });

        if (res.data.success) {
          setProductList(res.data.data);
        }
      } catch (error: any) {
        if (error.name !== "CanceledError") {
          console.error("Search error:", error);
        }
      } finally {
        setLoading(false);
      }
    }, 400);

    return () => {
      clearTimeout(delayDebounce);
      controller.abort();
    };
  }, [searchOpen, searchQuery]);

  return (
    <>
      {/* Top Bar */}
      <SubHeader user={user} />

      {/* Sticky Navbar */}
      <div className="sticky top-0 bg-white/95 backdrop-blur-xl shadow-sm z-50 border-b border-gray-100">
        <div className="px-[5%]">
          <div className="flex items-center justify-between py-4 md:py-5 gap-4">
            {/* Logo */}
            <Link href="/" className="flex-shrink-0">
              <Image
                src={headerLogo}
                alt="Header Logo"
                width={256}
                height={50}
                className="w-32 h-auto"
              />
            </Link>

            {/* Desktop Navigation */}
            <nav className="hidden lg:flex items-center gap-8 xl:gap-10 font-semibold text-lg">
              {navdata.map((item) => {
                const isActive =
                  item.link === "/"
                    ? pathname === "/"
                    : pathname.startsWith(item.link);
                return (
                  <Link
                    key={item.id}
                    href={item.link}
                    className={`relative py-1 transition-all duration-300 group ${
                      isActive
                        ? "text-pink-600"
                        : "text-gray-700 hover:text-pink-600"
                    }`}
                  >
                    {item.label}
                    <span
                      className={`absolute bottom-0 left-1/2 -translate-x-1/2 h-[3px] rounded-full bg-gradient-to-r from-pink-500 to-rose-600 transition-all duration-300 ${
                        isActive ? "w-full" : "w-0 group-hover:w-full"
                      }`}
                    />
                  </Link>
                );
              })}
            </nav>

            {/* Desktop Icons */}
            <div className="hidden lg:flex items-center gap-4 lg:gap-6 font-semibold">
              {/* Search Button */}
              <button
                onClick={() => setSearchOpen(true)}
                className="flex flex-col items-center gap-1 text-gray-600 hover:text-pink-600 transition-colors group cursor-pointer"
              >
                <div className="p-2 rounded-full group-hover:bg-pink-50 transition-colors">
                  <Search className="w-5 h-5" />
                </div>
                <span className="text-[12px]">Search</span>
              </button>

              {/* Account */}
              <Link
                href={user?.name ? "/user/dashboard" : "/login"}
                className="flex flex-col items-center gap-1 text-gray-600 hover:text-pink-600 transition-colors group"
              >
                <div className="p-2 rounded-full group-hover:bg-pink-50 transition-colors">
                  {user?.name ? (
                    <User className="w-5 h-5" />
                  ) : (
                    <LogIn className="w-5 h-5" />
                  )}
                </div>
                <span className="text-[12px]">
                  {user?.name ? user?.name : "Login"}
                </span>
              </Link>

              {/* Wishlist */}
              <Link
                href={user?.name ? "/user/wishlist" : "/login"}
                className="flex flex-col items-center gap-1 text-gray-600 hover:text-pink-600 transition-colors group relative"
              >
                <div className="p-2 rounded-full group-hover:bg-pink-50 transition-colors">
                  <Heart className="w-5 h-5" />
                </div>
                <span className="text-[12px]">Wishlist</span>
                <span className="absolute -top-1 -right-1 bg-gradient-to-r from-pink-500 to-rose-600 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                  {wishlistItem.length}
                </span>
              </Link>

              {/* Cart */}
              <Link
                href="/cart"
                className="flex flex-col items-center gap-1 text-gray-600 hover:text-pink-600 transition-colors group relative"
              >
                <div className="p-2 rounded-full group-hover:bg-pink-50 transition-colors">
                  <ShoppingCart className="w-5 h-5" />
                </div>
                <span className="text-[12px]">Cart</span>
                <span className="absolute -top-1 -right-1 bg-gradient-to-r from-pink-500 to-rose-600 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                  {items?.length ?? 0}
                </span>
              </Link>
            </div>

            {/* Mobile Menu Button */}
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="lg:hidden p-2 text-gray-600 hover:text-pink-600 transition-colors cursor-pointer"
            >
              {mobileMenuOpen ? (
                <X className="w-6 h-6" />
              ) : (
                <Menu className="w-6 h-6" />
              )}
            </button>
          </div>

          {/* Mobile Menu */}
          {mobileMenuOpen && (
            <div className="lg:hidden py-4 border-t border-gray-100 font-semibold">
              <nav className="flex flex-col gap-4">
                {navdata.map((item) => {
                  const isActive =
                    item.link === "/"
                      ? pathname === "/"
                      : pathname.startsWith(item.link);
                  return (
                    <Link
                      key={item.id}
                      href={item.link}
                      onClick={() => setMobileMenuOpen(false)}
                      className={`px-4 py-2 rounded-lg transition-colors ${
                        isActive
                          ? "bg-gradient-to-r from-pink-500 to-rose-600 text-white"
                          : "text-gray-700 hover:bg-pink-50"
                      }`}
                    >
                      {item.label}
                    </Link>
                  );
                })}
              </nav>

              <div className="grid grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100">
                <button
                  onClick={() => {
                    setSearchOpen(true);
                    setMobileMenuOpen(false);
                  }}
                  className="flex flex-col items-center gap-2 text-gray-600"
                >
                  <Search className="w-6 h-6" />
                  <span className="text-xs">Search</span>
                </button>
                <Link
                  href={user?.name ? "/user/dashboard" : "/login"}
                  className="flex flex-col items-center gap-2 text-gray-600"
                >
                  {user?.name ? (
                    <User className="w-6 h-6" />
                  ) : (
                    <LogIn className="w-6 h-6" />
                  )}

                  <span className="text-xs">
                    {user?.name ? user?.name : "Login"}
                  </span>
                </Link>
                <Link
                  href={user?.name ? "/user/wishlist" : "/login"}
                  className="flex flex-col items-center gap-2 text-gray-600 relative"
                >
                  <Heart className="w-6 h-6" />
                  <span className="text-xs">Wishlist</span>
                  <span className="absolute top-0 right-6 bg-pink-600 text-white text-[9px] rounded-full w-4 h-4 flex items-center justify-center">
                    {wishlistItem.length}
                  </span>
                </Link>
                <Link
                  href="/cart"
                  className="flex flex-col items-center gap-2 text-gray-600 relative"
                >
                  <ShoppingCart className="w-6 h-6" />
                  <span className="text-xs">Cart</span>
                  <span className="absolute top-0 right-6 bg-pink-600 text-white text-[9px] rounded-full w-4 h-4 flex items-center justify-center">
                    {items?.length ?? 0}
                  </span>
                </Link>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* Search Modal */}
      {searchOpen && (
        <div className="fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm animate-in fade-in duration-200">
          <div className="min-h-screen flex items-start justify-center p-4 pt-20">
            <div className="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col animate-in slide-in-from-top duration-300">
              {/* Modal Header */}
              <div className="p-6 border-b border-gray-100 relative">
                <div className="flex items-center gap-4">
                  <div className="flex-1 relative">
                    <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input
                      type="text"
                      placeholder="Search for cosmetics products..."
                      value={searchQuery}
                      onChange={(e) => setSearchQuery(e.target.value)}
                      className="w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-pink-500 focus:bg-white transition-all text-gray-700 text-sm md:text-base"
                      autoFocus
                    />
                  </div>
                  <button
                    onClick={() => {
                      setSearchOpen(false);
                      setSearchQuery("");
                    }}
                    className="p-2 bg-pink-600  rounded-full shadow-md hover:rotate-90 duration-300 cursor-pointer absolute top-2 right-2"
                  >
                    <X className="size-5 md:size-6 text-white" />
                  </button>
                </div>
              </div>

              {/* Modal Content */}
              <div className="flex-1 overflow-y-auto p-6">
                {searchQuery.trim() === "" ? (
                  // ================= EMPTY =================
                  <div className="text-center py-12">
                    <div className="w-16 h-16 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-4">
                      <Search className="w-8 h-8 text-pink-500" />
                    </div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">
                      Search Products
                    </h3>
                    <p className="text-gray-500 text-sm">
                      Start typing to search for your favorite cosmetics
                    </p>
                  </div>
                ) : loading ? (
                  // ================= LOADING =================
                  <div className="text-center py-12">
                    <div className="animate-pulse text-sm text-gray-500">
                      Searching products...
                    </div>
                  </div>
                ) : productList.length > 0 ? (
                  // ================= RESULTS =================
                  <div className="space-y-3">
                    <p className="text-sm text-gray-500 mb-4">
                      Found {productList.length} products
                    </p>

                    {productList.slice(0, 8).map((product) => (
                      <button
                        key={product.id}
                        onClick={() => {
                          setSearchOpen(false);
                          setSearchQuery("");
                          router.push(`/shop/${product.slug}`);
                        }}
                        className="w-full flex items-start gap-4 p-4 hover:bg-pink-50 rounded-xl transition-colors group cursor-pointer"
                      >
                        <img
                          src={product.image}
                          alt={product.name}
                          className="w-16 h-16 object-cover rounded-lg"
                        />

                        <div className="flex-1 text-left">
                          <h4 className="font-medium text-gray-900 group-hover:text-pink-600 transition-colors">
                            {product.name}
                          </h4>
                          {product.category && (
                            <p className="text-sm text-gray-500">
                              {product.category.name}
                            </p>
                          )}
                        </div>

                        <div className="text-lg font-semibold text-pink-600">
                          BDT{" "}
                          {product?.default_attribute?.discounted_price ??
                            product?.default_attribute?.price ??
                            product?.price}
                        </div>
                      </button>
                    ))}
                  </div>
                ) : (
                  // ================= NO RESULT =================
                  <div className="text-center py-12">
                    <div className="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                      <Search className="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">
                      No products found
                    </h3>
                    <p className="text-gray-500 text-sm">
                      Try searching with different keywords
                    </p>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
