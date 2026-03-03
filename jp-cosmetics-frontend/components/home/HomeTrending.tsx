"use client";

import { useState } from "react";
import { useKeenSlider } from "keen-slider/react";
import "keen-slider/keen-slider.min.css";
import ProductCard from "./ProductCard";
import WebPageWrapper from "../WebPageWrapper";
import Headline from "../Headline";
import { Product } from "@/types";

const HomeTrending = ({ products }: { products: Product[] }) => {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [loaded, setLoaded] = useState(false);

  // Initialize Keen Slider
  const [sliderRef, instanceRef] = useKeenSlider<HTMLDivElement>({
    initial: 0,
    slides: {
      perView: 5,
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
        slides: { perView: 4, spacing: 32 },
      },
      "(max-width: 1280px)": {
        slides: { perView: 3, spacing: 32 },
      },
      "(max-width: 1024px)": {
        slides: { perView: 2, spacing: 32 },
      },
      "(max-width: 680px)": {
        slides: { perView: 1, spacing: 32 },
      },
    },
  });

  return (
    <WebPageWrapper>
      <Headline
        className="mb-5 scroll-fade-up"
        mainText="Trending Products"
        subText="Add our products to weekly line up"
      />
      {/* Show All Button */}
      <div className="flex justify-center mb-5">
        <button className="relative w-[160px] overflow-hidden rounded-md bg-[#ec6b81] p-1 font-semibold text-white shadow-md group scroll-fade-up cursor-pointer">
          <span className="absolute inset-0 bg-[#d85a72] rounded-md -translate-x-full group-hover:translate-x-0 transition-transform duration-500"></span>
          <div className="relative z-10 flex items-center justify-center text-sm px-8 py-2">
            Show All
          </div>
        </button>
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
                  (instanceRef.current?.track?.details?.slides?.length ?? 0) - 1
                )
              }
            />
          </>
        )}

        {/* Slider Container */}
        <div
          ref={sliderRef}
          className="keen-slider scroll-fade-up z-0 py-4"
        >
          {products.map((product, index) => (
            <ProductCard
              key={index}
              product={product}
              className="keen-slider__slide h-full"
              wishlisted={false}
            />
          ))}
        </div>
      </div>

      {/* Pagination Dots */}
      {loaded && instanceRef.current && (
        <div className="flex justify-center py-4 gap-2">
          {[
            ...Array(
              instanceRef.current?.track?.details?.slides?.length ?? 0
            ).keys(),
          ].map((idx) => {
            return (
              <button
                key={idx}
                onClick={() => {
                  instanceRef.current?.moveToIdx(idx);
                }}
                className={`w-3 h-3 rounded-full transition-colors duration-200 cursor-pointer ${
                  currentSlide === idx ? "bg-[#ec6b81]" : "bg-gray-300"
                }`}
                aria-label={`Go to slide ${idx + 1}`}
              ></button>
            );
          })}
        </div>
      )}
    </WebPageWrapper>
  );
};

// --- Helper Components for Arrows ---

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

export default HomeTrending;
