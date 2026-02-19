"use client";

import { useEffect, useState } from "react";

import { showToast } from "@/utils/toast";

import { TruckElectric, HandCoins, X } from "lucide-react";
import { OrderDetailResponse } from "@/types/user";
import { orderService } from "@/services/user.service";

type Props = {
  isOpen: boolean;
  onClose: () => void;
  selectedOrderId: number | null;
};

const statusColors: Record<string, string> = {
  pending: "bg-yellow-100 text-yellow-800",
  processing: "bg-yellow-100 text-yellow-800",
  confirm: "bg-blue-100 text-blue-800",
  dispatched: "bg-purple-100 text-purple-800",
  delivered: "bg-green-100 text-green-800",
  cancelled: "bg-red-100 text-red-800",
  cancel: "bg-red-100 text-red-800",
  failed: "bg-red-100 text-red-800",
  returned: "bg-pink-100 text-pink-800",
  refunded: "bg-pink-100 text-pink-800",
  success: "bg-teal-100 text-teal-800",
};

export const OrderDetailsModal = ({
  isOpen,
  onClose,
  selectedOrderId,
}: Props) => {
  const [orderDetails, setOrderDetails] = useState<OrderDetailResponse | null>(
    null
  );
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!isOpen) return;
    if (selectedOrderId === null) return;

    const fetchOrderDetails = async () => {
      setLoading(true);
      try {
        const data = await orderService.detail(selectedOrderId);
        setOrderDetails(data);
      } catch (error) {
        // console.error("Failed to fetch order details", error);
        showToast.error(`failed to get order details`);
      } finally {
        setLoading(false);
      }
    };

    fetchOrderDetails();
  }, [isOpen, selectedOrderId]);

  if (!isOpen || !selectedOrderId || !orderDetails) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-2">
      {/* Card */}
      <div className="relative w-full max-w-lg max-h-[85vh] overflow-y-auto rounded-3xl bg-white shadow-2xl shadow-black/20">
        {/* Gradient Top Bar */}
        {/* <div className="h-2 rounded-t-3xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500" /> */}

        {/* Close */}
        <button
          onClick={onClose}
          className="absolute top-2 right-2 rounded-full p-1 bg-red-600 text-white transition hover:rotate-360 duration-300 cursor-pointer"
        >
          <X className="size-4" />
        </button>

        <div className="p-3 space-y-6">
          {/* Header */}
          <div className="flex items-center justify-between flex-wrap gap-2">
            <h2 className="text-xl font-extrabold tracking-tight">
              Order #{orderDetails?.order?.order_number}
            </h2>
            <div className="flex flex-wrap gap-1">
              <div
                className={`flex items-center gap-2 rounded-full px-3 py-1 text-sm font-semibold capitalize ${
                  statusColors[orderDetails?.order?.payment_status] ||
                  "bg-gray-100 text-gray-800"
                }`}
              >
                <HandCoins className="w-4 h-4" />
                {orderDetails?.order?.payment_status}
              </div>
              <div
                className={`flex items-center gap-2 rounded-full px-3 py-1 text-sm font-semibold capitalize ${
                  statusColors[orderDetails?.order?.order_status] ||
                  "bg-gray-100 text-gray-800"
                }`}
              >
                <TruckElectric className="w-4 h-4" />
                {orderDetails?.order?.order_status}
              </div>
            </div>
          </div>

          {/* Items */}
          <div className="space-y-4">
            {orderDetails?.items.map((item, index) => {
              const attr = item.product_attribute;

              return (
                <div key={index}>
                  <div className="flex items-start gap-3 min-w-0">
                    {/* Product Image */}
                    {item?.product?.primary_image ? (
                      <img
                        src={item.product.primary_image}
                        alt={item.product.name}
                        className="h-12 w-12 shrink-0 rounded-xl object-cover"
                      />
                    ) : (
                      <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-400 to-pink-500 text-white font-bold">
                        {attr?.attribute_value?.slice(0, 2)}
                      </div>
                    )}

                    {/* Text */}
                    <div className="min-w-0 text-xs">
                      <p className="font-semibold text-gray-900 truncate line-clamp-1">
                        {item.product?.name}
                      </p>

                      <p className="text-gray-500 truncate">
                        {attr?.attribute_name}:{" "}
                        <span className="font-medium">
                          {attr?.attribute_value}
                        </span>
                      </p>

                      <p className="text-gray-500 truncate mt-1">
                        {item?.unit_price} x {item?.quantity}
                      </p>
                      <p className="text-gray-500 truncate mt-1">
                        Discount : {item?.discount_amount}
                      </p>
                    </div>
                  </div>
                  <div className="mt-3 flex items-center justify-between rounded-xl bg-gray-50 px-4 py-2 text-gray-600 text-sm font-medium ">
                    <span className="">
                      Payable Amount{" "}
                      </span>
                    <span className="">
                      ৳{item.payable}
                      </span>
                  </div>
                </div>
              );
            })}
          </div>

          {/* Total */}
          <div className=" rounded-2xl bg-gradient-to-r from-pink-500 to-rose-600 px-5 py-4 text-white space-y-1">
            <div className="flex items-center justify-between gap-1 text-sm font-medium">
              <span className="">Sub Total</span>
              <span className="">{orderDetails?.order?.sub_total_amount}</span>
            </div>
            <div className="flex items-center justify-between gap-1 text-sm font-medium">
              <span className="">Delivery Charge</span>
              <span className="">{orderDetails?.order?.delivery_charge}</span>
            </div>
            <div className="flex items-center justify-between gap-1 text-sm font-medium">
              <span className="">Discount Amount</span>
              <span className="">{orderDetails?.order?.discount_amount}</span>
            </div>
            <div className="flex items-center justify-between">
              <span className="text-lg font-semibold">Total</span>
              <span className="text-xl font-extrabold">
                {orderDetails?.order?.payable_total}
              </span>
            </div>
          </div>

          {/* Address */}
          <div className="rounded-2xl border border-gray-200 p-4">
            <p className="mb-1 text-sm font-semibold text-gray-500">
              Delivery Address
            </p>
            <p className="text-gray-800 leading-relaxed">
              {orderDetails?.order?.shipping?.location},
              {orderDetails?.order?.shipping?.area},
              {orderDetails?.order?.shipping?.city}
            </p>
            <p className="text-gray-500 leading-relaxed text-sm">
              {orderDetails?.order?.order_note}
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};
