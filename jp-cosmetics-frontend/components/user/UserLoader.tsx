import { LoaderCircle } from "lucide-react";

export default function UserLoader() {
  return (
    <div className="fixed inset-0 bg-black/60 z-50 flex justify-center items-center h-screen">
      <div className="p-6 bg-pink-600 text-white rounded-xl shadow-lg shadow-pink-400 flex flex-col items-center justify-center gap-2 font-semibold text-sm">
        <LoaderCircle className="size-10 animate-spin" />
        Loading ..
      </div>
    </div>
  );
}
