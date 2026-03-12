export const dynamic = "force-dynamic";

import { Suspense } from "react";
import ShopPageSkeleton from "./ShopPageSkeleton";
import ShopPageClient from "./ShopPageClient";

export default function Page() {
  return (
    <Suspense fallback={<ShopPageSkeleton/>}>
      <ShopPageClient/>
    </Suspense>
  );
}
