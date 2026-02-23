import { MapPin } from "lucide-react";

export default function AddressCard({
  title,
  name,
  address,
  area,
  city,
  phone,
  isDefault,
  onEdit,
  onRemove,
}: any) {
  return (
    <div className="border-2 border-gray-200 rounded-xl p-5 hover:border-pink-300 transition-colors relative">
      {isDefault == 1 && (
        <span className="absolute top-3 right-3 px-3 py-1 bg-gradient-to-r from-pink-500 to-rose-600 text-white text-xs font-semibold rounded-full">
          Default
        </span>
      )}
      <div className="flex items-start gap-3 mb-4">
        <MapPin className="w-5 h-5 text-pink-600 flex-shrink-0 mt-1" />
        <div>
          <h3 className="font-bold text-gray-900 mb-1">{title}</h3>
          <p className="text-sm text-gray-900 font-semibold">{name}</p>
          <p className="text-sm text-gray-600 mt-1">{address}</p>
          <p className="text-sm text-gray-600 mt-1">
            {area} , {city}
          </p>
          <p className="text-sm text-gray-600">{phone}</p>
        </div>
      </div>
      <div className="flex gap-2">
        <button
          onClick={onEdit}
          className="flex-1 px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer"
        >
          Edit
        </button>
        <button
          onClick={onRemove}
          className="flex-1 px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer bg-gradient-to-r from-pink-500 to-rose-600 text-white"
        >
          Remove
        </button>
      </div>
    </div>
  );
}
