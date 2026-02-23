"use client";

import React, { useState } from "react";
import {
  Trash2,
  Plus,
  Minus,
  ShoppingBag,
  Tag,
  ArrowRight,
  Heart,
  X,
  Truck,
  ShieldCheck,
  Gift,
  Sparkles,
} from "lucide-react";
import Link from "next/link";
import { useCartStore } from "@/store/cart-store";
import { useAuthStore } from "@/store/authStore";


const Cart = () => {
  const { items, updateQuantity, removeItem } = useCartStore();
  const user = useAuthStore().user

  const [promoCode, setPromoCode] = useState("");
  const [appliedPromo, setAppliedPromo] = useState<string | null>(null);

  // ================== CALCULATIONS ==================
  const subtotal = items.reduce(
    (sum, item) => sum + item.unit_price * item.quantity,
    0
  );

  const discount = items.reduce((sum, item) => sum + item.discount_amount, 0);

  const promoDiscount = appliedPromo ? subtotal * 0.1 : 0;
  const shipping = 0;
  const tax = 0;

  const total = subtotal - discount - promoDiscount + shipping + tax;

  const freeShippingProgress = subtotal >= 50 ? 100 : (subtotal / 50) * 100;

  const amountForFreeShipping = Math.max(0, 50 - subtotal);

  // ================== EMPTY CART ==================
  if (items.length === 0) {
    return (
      <div className="min-h-screen bg-gradient-to-b from-pink-50/30 to-white">
        <div className="px-[5%] py-16 text-center">
          <ShoppingBag className="w-16 h-16 mx-auto text-pink-400 mb-6" />
          <h1 className="text-3xl font-bold mb-4">Your Cart is Empty</h1>
          <p className="text-gray-600 mb-8">
            Start shopping to add items to your cart.
          </p>
          <Link href="/shop">
            <button className="px-8 py-4 bg-pink-600 text-white rounded-xl font-bold">
              Start Shopping
            </button>
          </Link>
        </div>
      </div>
    );
  }

  // ================== CART UI ==================
  return (
    <div className="min-h-screen bg-gradient-to-b from-pink-50/30 to-white">
      <div className="px-[5%] py-8 lg:py-12">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-4xl font-bold text-pink-600">Shopping Cart</h1>
          <p className="text-gray-600">
            {items.length} {items.length === 1 ? "item" : "items"} in your cart
          </p>
        </div>

        {/* Free Shipping */}
        {amountForFreeShipping > 0 && (
          <div className="mb-8 bg-white p-6 rounded-2xl border">
            <div className="flex justify-between mb-2">
              <span className="font-semibold">
                Add BDT {amountForFreeShipping.toFixed(2)} for FREE shipping
              </span>
              <span>BDT {subtotal.toFixed(2)} / BDT 50</span>
            </div>
            <div className="h-3 bg-gray-200 rounded-full">
              <div
                className="h-full bg-pink-600 rounded-full"
                style={{ width: `${freeShippingProgress}%` }}
              />
            </div>
          </div>
        )}

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* CART ITEMS */}
          <div className="lg:col-span-2 space-y-4">
            {items.map((item) => (
              <div
                key={`${item.product_id}-${item.product_attribute_id}`}
                className="bg-white rounded-2xl p-6 border border-pink-300"
              >
                <div className="flex gap-6">
                  <img
                    src={item.image}
                    alt="product"
                    className="w-32 h-32 object-cover rounded-xl"
                  />

                  <div className="flex-1">
                    <div className="flex justify-between">
                      <h3 className="font-bold text-lg w-[calc(100%-40px)]">
                        {item.product_name} ({item.attribute_value})
                      </h3>
                      <button
                        className="cursor-pointer hover:bg-pink-50 rounded-full duration-300 active:scale-75 size-9 flex items-center justify-center"
                        onClick={() =>
                          removeItem(item.product_id, item.product_attribute_id)
                        }
                      >
                        <Trash2 className="text-red-500 size-6" />
                      </button>
                    </div>

                    <p className="text-pink-600 text-xl font-bold">
                      BDT {item.unit_price.toFixed(2)}
                    </p>

                    <p className="text-sm text-gray-500">
                      Item Total: BDT {item.subtotal.toFixed(2)}
                    </p>

                    {/* Quantity */}
                    <div className="flex items-center gap-3 mt-4">
                      <button
                        className="cursor-pointer hover:bg-pink-100 rounded-full hover:text-pink-600 duration-300 active:scale-75"
                        onClick={() =>
                          updateQuantity(
                            item.product_id,
                            item.product_attribute_id,
                            item.quantity - 1
                          )
                        }
                        disabled={item.quantity <= 1}
                      >
                        <Minus />
                      </button>

                      <p className="font-bold w-10 text-center">{item.quantity}</p>

                      <button
                        className="cursor-pointer hover:bg-pink-100 rounded-full hover:text-pink-600 duration-300 active:scale-75"
                        onClick={() =>
                          updateQuantity(
                            item.product_id,
                            item.product_attribute_id,
                            item.quantity + 1
                          )
                        }
                      >
                        <Plus />
                      </button>
                    </div>

                    {/* <button className="flex items-center gap-2 text-sm mt-4 cursor-pointer">
                      <Heart className="w-4 h-4" />
                      Move to Wishlist
                    </button> */}
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* ORDER SUMMARY */}
          <div className="bg-white rounded-2xl p-6 border h-fit sticky top-24">
            <h2 className="text-xl font-bold mb-4 flex items-center gap-2">
              <Sparkles className="text-pink-600" /> Order Summary
            </h2>

            {/* Promo */}
            {appliedPromo ? (
              <div className="flex justify-between bg-pink-50 p-3 rounded-xl mb-4">
                <span>{appliedPromo}</span>
                <button onClick={() => setAppliedPromo(null)}>
                  <X />
                </button>
              </div>
            ) : (
              <div className="flex gap-2 mb-4">
                <input
                  value={promoCode}
                  onChange={(e) => setPromoCode(e.target.value)}
                  className="border px-3 py-2 rounded-xl w-full"
                  placeholder="Promo code"
                />
                <button
                  onClick={() => {
                    if (promoCode) {
                      setAppliedPromo(promoCode);
                      setPromoCode("");
                    }
                  }}
                  className="bg-black text-white px-4 rounded-xl active:scale-95 hover:bg-pink-700 duration-300 cursor-pointer"
                >
                  Apply
                </button>
              </div>
            )}

            {/* PRICE */}
            <div className="space-y-2 text-sm border-y py-4">
              <div className="flex justify-between">
                <span>Subtotal</span>
                <span>BDT {subtotal.toFixed(2)}</span>
              </div>

              <div className="flex justify-between text-green-600">
                <span>Discount</span>
                <span>- BDT {discount.toFixed(2)}</span>
              </div>

              {appliedPromo && (
                <div className="flex justify-between text-green-600">
                  <span>Promo</span>
                  <span>- BDT {promoDiscount.toFixed(2)}</span>
                </div>
              )}

              {/* <div className="flex justify-between">
                <span>Shipping</span>
                <span>{shipping === 0 ? "FREE" : `BDT ${shipping}`}</span>
              </div> */}

              {/* <div className="flex justify-between">
                <span>Tax</span>
                <span>BDT {tax.toFixed(2)}</span>
              </div> */}
            </div>

            <div className="flex justify-between font-bold text-xl py-4">
              <span>Total</span>
              <span className="text-pink-600">BDT {total.toFixed(2)}</span>
            </div>

            <Link
              href="/checkout"
              className="w-full bg-pink-600 text-white py-4 rounded-xl font-bold flex items-center justify-center gap-2"
            >
              Checkout <ArrowRight />
            </Link>

            {/* Trust */}
            <div className="mt-6 space-y-2 text-sm text-gray-600">
              <div className="flex gap-2">
                <ShieldCheck className="text-pink-600" />
                Secure Checkout
              </div>
              <div className="flex gap-2">
                <Truck className="text-pink-600" />
                Free shipping over BDT 500000
              </div>
              <div className="flex gap-2">
                <Gift className="text-pink-600" />
                Easy returns
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Cart;
