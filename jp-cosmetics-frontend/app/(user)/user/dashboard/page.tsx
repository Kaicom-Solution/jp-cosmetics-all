"use client";
import DashboardComponent from "@/components/user/DashboardComponent";
import UserLoader from "@/components/user/UserLoader";
import { Suspense } from "react";

export default function UserDashboard() {

  return (
    <Suspense fallback={<UserLoader/>}>
      <DashboardComponent />
    </Suspense>
  );
}