import WebPageWrapper from "@/components/WebPageWrapper";

export default function ShopPageSkeleton() {
  return (
    <WebPageWrapper>
      <div className="px-[5%] py-8 lg:py-12">
        <div className="mb-8 text-center">
          <h1 className="h-10 w-full max-w-1/3 mb-2 bg-pink-100 animate-pulse mx-auto"></h1>
          <h1 className="h-5 w-full max-w-1/2 mb-2 bg-pink-100 animate-pulse mx-auto"></h1>
        </div>
        <div className="lg:flex lg:space-x-8">
          {/* Filters Sidebar */}
          <aside className="hidden lg:block lg:w-1/5">
            <ShopFiltersSkeleton />
          </aside>

          {/* Main Content */}
          <main className="lg:w-4/5 space-y-6">
            <ShopSortSkeleton />

            <ShopProductsSkeleton />

            <PaginationSkeleton />
          </main>
        </div>
      </div>
    </WebPageWrapper>
  );
}

function ShopFiltersSkeleton() {
  return (
    <div className="space-y-6 animate-pulse bg-white/50 rounded-md p-5">
      {[...Array(9)].map((_, i) => (
        <div key={i} className="bg-pink-50/50 rounded h-6 w-full"></div>
      ))}
    </div>
  );
}

 function ShopSortSkeleton() {
  return (
    <div className="flex items-center justify-between animate-pulse pb-4">
      <div className="h-6 bg-gray-200 rounded w-40"></div>
      <div className="h-6 bg-gray-200 rounded w-24"></div>
    </div>
  );
}

 function ShopProductsSkeleton() {
  return (
    <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      {[...Array(6)].map((_, i) => (
        <div key={i} className="animate-pulse">
          <div className="h-50 bg-gray-100 rounded-lg"></div>
          <div className="bg-white/70 space-y-3 p-3 rounded-lg">
 <div className="h-4 bg-gray-100 rounded w-3/4"></div>
          <div className="h-4 bg-gray-100 rounded w-1/2"></div>
          <div className="h-8 bg-gray-100 rounded w-1/2"></div>
          </div>
         
        </div>
      ))}
    </div>
  );
}

 function PaginationSkeleton() {
  return (
    <div className="flex justify-center items-center gap-4 animate-pulse">
      {[...Array(5)].map((_, i) => (
        <div key={i} className="h-8 bg-gray-200 rounded w-8"></div>
      ))}
    </div>
  );
}
