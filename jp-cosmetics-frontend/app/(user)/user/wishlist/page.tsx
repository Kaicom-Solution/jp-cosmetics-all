"use client";
import { useState, useEffect, Suspense } from "react";
import { useWishlistStore } from "@/store/wishListStore";

import ProductCard from "@/components/home/ProductCard";
import UserLoader from "@/components/user/UserLoader";

function Wishlist() {
  const { items, fetch, loading } = useWishlistStore();

  useEffect(() => {
    fetch();
  }, []);

  return (
    <Suspense fallback={<UserLoader />}>
      {loading && <UserLoader />}

      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 min-h-[53vh]">
        <h2 className="text-2xl font-bold text-gray-900 mb-6">My Wishlist</h2>

        {items.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6">
            {items.map((i) => (
              <ProductCard key={i.id} product={i} wishlisted={true} />
            ))}
          </div>
        ) : (
          <div className="size-full flex justify-center items-center text-gray-500 text-lg">
            No items in wishlist
          </div>
        )}
      </div>
    </Suspense>
  );
}

export default Wishlist;
