"use client";

import { useEffect, useState } from "react";

const notices = [
  "✨ Free Shipping on Orders Over $50",
  "🔥 30% Flash Sale – Limited Time",
  "🚚 Delivery in 3–5 Business Days",
];

export default function SubHeader({ user }: { user: any }) {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [animate, setAnimate] = useState(true);

  useEffect(() => {
    const interval = setInterval(() => {
      setAnimate(false);

      setTimeout(() => {
        setCurrentIndex((prev) => (prev + 1) % notices.length);
        setAnimate(true);
      }, 300); // duration of exit animation
    }, 3000);

    return () => clearInterval(interval);
  }, []);

  return (
    <div className="bg-gradient-to-r from-pink-500 via-rose-500 to-pink-600 text-white shadow-sm">
      <div className="px-[5%] flex items-center justify-between gap-4 md:gap-6 text-[11px] md:text-xs">
        
        <p className="py-2.5 font-medium hover:text-pink-100 cursor-pointer transition-colors">
          Help & Advice
        </p>

        <p
          className={`py-2.5 hidden sm:block font-medium transition-all duration-300 text-sm ${
            animate
              ? "opacity-100 translate-y-0"
              : "opacity-0 translate-y-2"
          }`}
        >
          {notices[currentIndex]}
        </p>

        <div className="py-2.5 font-medium hover:text-pink-100 cursor-pointer transition-colors">
          Welcome {user?.name ?? "(Login)"}
        </div>

      </div>
    </div>
  );
}