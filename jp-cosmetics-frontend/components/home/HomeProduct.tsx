
import WebPageWrapper from "../WebPageWrapper";
import Headline from "../Headline";
import ProductCard from "./ProductCard";
import { Product } from "@/types";
import Link from "next/link";
export default function HomeProduct({ products }: { products: Product[] }) {
  return (
    
    <WebPageWrapper>
      <Headline
        className="mb-10 scroll-fade-up"
        mainText="Our Products"
        subText="Add our products to weekly line up"
      />

      <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-8">
        {products.map((item, index) => (
          <ProductCard key={index} product={item} wishlisted={false}/>
        ))}
      </div>

      {/* Show all button */}
      <div className="flex justify-center mt-5">
        <button className="relative w-[160px] overflow-hidden rounded-md bg-[#ec6b81] p-1 font-semibold text-white shadow-md group">
          <span className="absolute inset-0 bg-[#d85a72] rounded-md -translate-x-full group-hover:translate-x-0 transition-transform duration-500"></span>

          <Link href="/shop" className="relative z-10 flex items-center justify-center text-sm px-8 py-2">
            Show All
          </Link>
        </button>
      </div>
    </WebPageWrapper>
  );
}
