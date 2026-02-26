export const dynamic = "force-dynamic";

import { Suspense } from "react";
import ShopClient from "./ShopClient";
import ShopPageSkeleton from "./ShopPageSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<ShopPageSkeleton/>}>
      <ShopClient />
    </Suspense>
  );
}
