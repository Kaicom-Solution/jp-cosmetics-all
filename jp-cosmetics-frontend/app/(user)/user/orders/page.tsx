"use client";

import { Suspense, useEffect, useState } from "react";
import { orderService } from "@/services/user.service";
import type { Order, OrderDetailResponse } from "@/types/user";

import { TruckElectric, HandCoins } from "lucide-react";

import { formatDate } from "@/utils/formatDate";
import { showToast } from "@/utils/toast";

import { OrderDetailsModal } from "@/components/user/OrderDetailsModal";
import UserLoader from "@/components/user/UserLoader";

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

export default function OrdersSection() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [showOrderModal, setShowOrderModal] = useState(false);
  const [selectedOrder, setSelectedOrder] = useState<number | null>(null);
  const [orderDetails, setOrderDetails] = useState<OrderDetailResponse | null>(
    null,
  );
  const [activeReviewIndex, setActiveReviewIndex] = useState<number | null>(
    null,
  );

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        const data = await orderService.list();
        setOrders(data);
      } catch (error) {
        showToast.error(`failed to get order list`);
      } finally {
        setLoading(false);
      }
    };

    fetchOrders();
  }, []);

  const fetchOrderDetails = async (id: any) => {
    setActiveReviewIndex(null);
    setLoading(true);
    try {
      const data = await orderService.detail(id);
      setOrderDetails(data);
    } catch (error) {
      showToast.error(`failed to get order details`);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Suspense fallback={<UserLoader />}>
      {loading && <UserLoader />}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 min-h-[53vh]">
        <h2 className="text-2xl font-bold text-gray-900 mb-6">My Orders</h2>

        {!orders.length ? (
          <div className="size-full flex justify-center items-center text-gray-500 text-lg">
            No orders found
          </div>
        ) : (
          <div className="gap-4 grid md:grid-cols-2 2xl:grid-cols-3">
            {orders.map((order) => (
              <div
                key={order.id}
                className="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow"
              >
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                  <div>
                    <h3 className="font-bold text-gray-900">
                      Order #{order.order_number}
                    </h3>
                    <p className="text-sm text-gray-600">
                      Placed on {formatDate(order.created_at)}
                    </p>
                    <h3 className="font-bold text-gray-900 mt-1">
                      Total : {order.payable_total}
                    </h3>
                  </div>
                </div>

                <div className="flex items-center gap-4 mb-4">
                  <span
                    className={`inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold w-fit capitalize ${
                      statusColors[order.payment_status] ||
                      "bg-gray-100 text-gray-800"
                    }`}
                  >
                    <HandCoins className="w-4 h-4" />
                    {order?.payment_status}
                  </span>
                  <span
                    className={`inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold w-fit capitalize ${
                      statusColors[order.status] || "bg-gray-100 text-gray-800"
                    } `}
                  >
                    <TruckElectric className="w-4 h-4" />
                    {order?.status}
                  </span>
                </div>

                <div className="flex flex-wrap gap-3">
                  <button
                    onClick={() => {
                      fetchOrderDetails(order.id);
                      setShowOrderModal(true);
                      // setSelectedOrder(order.id)
                    }}
                    className="flex-1 px-4 py-2.5 border-2 border-pink-500 text-pink-600 rounded-xl font-semibold hover:bg-pink-50 transition-colors cursor-pointer"
                  >
                    View Details
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
      {loading ? (
        <UserLoader />
      ) : (
        <OrderDetailsModal
          isOpen={showOrderModal}
          onClose={() => setShowOrderModal(false)}
          orderDetails={orderDetails}
          activeReviewIndex={activeReviewIndex}
          setActiveReviewIndex={setActiveReviewIndex}
          // selectedOrder = {selectedOrder}
        />
      )}
    </Suspense>
  );
}
