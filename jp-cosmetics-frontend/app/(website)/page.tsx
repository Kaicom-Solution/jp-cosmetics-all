import HomeBrands from "@/components/home/HomeBrands";
import HomeCategory from "@/components/home/HomeCategory";
import HomeHero from "@/components/home/HomeHero";
import HomeProduct from "@/components/home/HomeProduct";
import HomePromo from "@/components/home/HomePromo";
import HomeReview from "@/components/home/HomeReview";
import HomeTrending from "@/components/home/HomeTrending";
import HomeUsp from "@/components/home/HomeUsp";
import { getBrands, getCategories, getFooterSliders, getHeroSliders, getPopularCategories, getTrendingProducts ,getPopularProducts } from "@/services/home.service";

import { Suspense } from "react";

export default async function Home() {

  const heroSliders = await getHeroSliders();

  const popularCategories = await getPopularCategories();

  const categories = await getCategories();

  const brands = await getBrands();

  const footerSliders = await getFooterSliders();

  const trendingProducts = await getTrendingProducts();

  const populerProducts = await getPopularProducts()

  return (
    <div className="space-y-20 pb-10 pt-4">
        <HomeHero heroSliders={heroSliders} popularCategories={popularCategories} />
        <HomeUsp />
        <HomeCategory popularCategories={categories} />
        <HomeProduct products={populerProducts} />
        <HomeBrands brands={brands} />
        <HomeTrending products={trendingProducts} />
        <HomeReview />
        <HomePromo footerSliders={footerSliders} />
      </div>
  );
}
