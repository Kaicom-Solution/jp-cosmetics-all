"use client";

import {
  Package,
  Heart,
  ShoppingBag,
  Bell,
  Gift,
  ChevronRight,
  LoaderCircle,
} from "lucide-react";

import StatCard from "./StatCard";
import OrderCard from "./OrderCard";
import QuickActionCard from "./QuickActionCard";
import { useEffect, useState } from "react";

import { showToast } from "@/utils/toast";

import { dashboardService, orderService } from "@/services/user.service";
import { DashboardResponse, Order } from "@/types/user";
import UserLoader from "./UserLoader";

export default function DashboardComponent() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [dashboard, setDashboard] = useState<DashboardResponse | null>(null);

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        const data = await orderService.list();
        setOrders(data);
      } catch (error) {
        // console.error("Failed to fetch orders", error);
        showToast.error(`failed to get order list`);
      } finally {
        // setLoading(false);
      }
    };

    const fetchDashboard = async () => {
      try {
        setLoading(true);
        const data = await dashboardService.fetch();
        setDashboard(data);
      } catch (err) {
        // console.error(err);
        showToast.error(`failed to get dashboard data`);
      } finally {
        setLoading(false);
      }
    };

    fetchDashboard();

    fetchOrders();
  }, []);

  return (
    <div className="space-y-6">
      {loading && <UserLoader />}
      
      {/* Stats Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard
          icon={Package}
          label="Total Orders"
          value={dashboard?.total_orders || 0}
          change="+3 this month"
          color="pink"
        />
        <StatCard
          icon={Heart}
          label="Wishlist Items"
          value={dashboard?.wishlist_items || 0}
          change="2 back in stock"
          color="rose"
        />
        <StatCard
          icon={Gift}
          label="Reward Points"
          value={dashboard?.reward_points || 0}
          change="Earn 50 more"
          color="pink"
        />
        <StatCard
          icon={ShoppingBag}
          label="Total Spent"
          value={dashboard?.total_spent || null}
          change="This year"
          color="rose"
        />
      </div>

      {/* Recent Orders */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-xl font-bold text-gray-900">Recent Orders</h2>
          <button className="text-pink-600 hover:text-pink-700 text-sm font-semibold flex items-center gap-1">
            View All
            <ChevronRight className="w-4 h-4" />
          </button>
        </div>

        <div className="space-y-4">
          {orders.map((order) => (
            <OrderCard key={order.id} order={order} />
          ))}
        </div>
      </div>

      {/* Quick Actions */}
      {/* <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <QuickActionCard
          icon={Bell}
          title="Notifications"
          description="3 new updates available"
          action="View"
        />
        <QuickActionCard
          icon={Gift}
          title="Special Offers"
          description="Exclusive deals just for you"
          action="Browse"
        />
      </div> */}
    </div>
  );
}
