"use client";

import { useState } from "react";

import WebPageWrapper from "../WebPageWrapper";
import { Brand } from "@/types";
import Image from "next/image";
import Link from "next/link";

import { useKeenSlider } from "keen-slider/react";
import "keen-slider/keen-slider.min.css";

const HomeBrands = ({ brands }: { brands: Brand[] }) => {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [loaded, setLoaded] = useState(false);

  // Initialize Keen Slider
  const [sliderRef, instanceRef] = useKeenSlider<HTMLDivElement>({
    initial: 0,
    slides: {
      perView: 6,
      spacing: 32,
    },
    slideChanged(slider) {
      setCurrentSlide(slider.track?.details?.rel ?? 0);
    },
    created() {
      setLoaded(true);
    },
    // Breakpoints for responsiveness
    breakpoints: {
      "(max-width: 1536px)": {
        slides: { perView: 5, spacing: 32 },
      },
      "(max-width: 1280px)": {
        slides: { perView: 4, spacing: 32 },
      },
      "(max-width: 1024px)": {
        slides: { perView: 3, spacing: 32 },
      },
      "(max-width: 680px)": {
        slides: { perView: 2, spacing: 32 },
      },
    },
  });

  return (
    <WebPageWrapper className="relative z-10">
      <div className="text-center mb-16">
        <span className="inline-block px-4 py-2 bg-pink-100 text-pink-600 rounded-full text-sm font-semibold mb-4">
          Shop By Brand
        </span>
        <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
          Discover Premium Beauty Brands
        </h2>
        <p className="text-lg text-gray-600 max-w-2xl mx-auto">
          Explore our curated collection of luxury cosmetics from world-renowned
          brands
        </p>
      </div>
      {/* Slider Section */}
      <div className="relative px-2">
        {/* Navigation Arrows */}
        {loaded && instanceRef.current && (
          <>
            <Arrow
              left
              onClick={(e: any) =>
                e.stopPropagation() || instanceRef.current?.prev()
              }
              disabled={currentSlide === 0}
            />

            <Arrow
              onClick={(e: any) =>
                e.stopPropagation() || instanceRef.current?.next()
              }
              disabled={
                // compute slideCount safely
                currentSlide >=
                Math.max(
                  0,
                  (instanceRef.current?.track?.details?.slides?.length ?? 0) -
                    1,
                )
              }
            />
          </>
        )}

        {/* Slider Container */}
        <div ref={sliderRef} className="keen-slider scroll-fade-up z-0">
          {brands.map((brand, index) => (
            <Link
              key={brand.id || index}
              href={`/shop?page=1&brand_ids=${brand.id}`}
              className="keen-slider__slide h-full group relative flex flex-col items-center justify-center p-2 bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100"
            >
              {/* Gradient background on hover */}
              <div className="absolute inset-0 bg-gradient-to-br from-pink-500/0 via-purple-500/0 to-pink-500/0 group-hover:from-pink-500/5 group-hover:via-purple-500/5 group-hover:to-pink-500/5 rounded-2xl transition-all duration-500"></div>

              {/* Logo container */}
              <div className="relative mb-4 w-50 h-50 max-w-full flex items-center justify-center overflow-clip">
                <div className="absolute inset-0 bg-gradient-to-br from-pink-100 to-purple-100 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
                <Image
                  src={brand.logo}
                  alt={brand.name}
                  className="relative w-full h-auto object-cover transition-all duration-500 scale-110 group-hover:scale-100"
                  width={250}
                  height={250}
                />
              </div>

              {/* Brand name */}
              <h3 className="relative text-sm font-semibold text-gray-700 group-hover:text-pink-600 transition-colors duration-300 text-center">
                {brand.name}
              </h3>

              {/* Decorative underline */}
              <div className="mt-2 w-0 h-0.5 bg-gradient-to-r from-pink-500 to-purple-500 group-hover:w-12 transition-all duration-500"></div>

              {/* Arrow indicator */}
              <div className="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                <svg
                  className="w-4 h-4 text-pink-500"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M9 5l7 7-7 7"
                  />
                </svg>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </WebPageWrapper>
  );
};

function Arrow(props: {
  disabled: boolean;
  left?: boolean;
  onClick: (e: any) => void;
}) {
  const disabledClass = props.disabled ? " opacity-30 cursor-not-allowed" : "";
  return (
    <svg
      onClick={props.onClick}
      className={`w-8 h-8 absolute top-1/2 -translate-y-1/2 cursor-pointer z-10 fill-current text-white bg-[#ec6b81] rounded-full shadow-md p-2 hover:bg-[#d85a72] transition-all ${
        props.left ? "left-0" : "right-0"
      } ${disabledClass}`}
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
    >
      {props.left && (
        <path d="M16.67 0l2.83 2.829-9.339 9.175 9.339 9.167-2.83 2.829-12.17-11.996z" />
      )}
      {!props.left && (
        <path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z" />
      )}
    </svg>
  );
}

export default HomeBrands;
