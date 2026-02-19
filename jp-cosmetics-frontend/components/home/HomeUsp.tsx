"use client";

import { Truck, Headset, HandCoins, Gift } from "lucide-react";
import WebPageWrapper from "../WebPageWrapper";

export default function HomeUsp() {
  const usp = [
    {
      icon: Truck,
      label: "Free Shipping",
      label2: "Order over $100",
    },
    {
      icon: Headset,
      label: "Support 24/7",
      label2: "Before & After Purchase",
    },
    {
      icon: HandCoins,
      label: "Money Back",
      label2: "If you dont Satistied",
    },
    {
      icon: Gift,
      label: "Gift Voucher",
      label2: "There are many variations",
    },
  ];

  return (
    <WebPageWrapper>
      <div className="grid grid-cols-2 md:grid-cols-4 md:bg-[#ec6b81] text-white md:p-10 rounded-xl gap-3 md:gap-6">
        {usp.map((item, index) => {
          const Icon = item.icon;
          return (
            <div key={index} className={`flex items-center gap-3 group p-3 md:p-0 bg-[#ec6b81] rounded-xl min-h-26 md:min-h-0`}>
              <div className="border p-2 rounded-full">
                <Icon strokeWidth={1} className="size-4 lg:size-9 group-hover:animate-pulse" />
              </div>

              <div className="group-hover:animate-pulse">
                <p className="text-sm lg:text-base font-semibold">{item.label}</p>
                <p className="text-xs lg:text-sm">{item.label2}</p>
              </div>
            </div>
          );
        })}
      </div>
    </WebPageWrapper>
  );
}
