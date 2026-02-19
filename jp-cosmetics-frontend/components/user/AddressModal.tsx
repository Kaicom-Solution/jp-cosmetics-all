import { X, MapPin, AlertTriangle } from "lucide-react";
import { useState, useEffect } from "react";

import { useAuthStore } from "@/store/authStore";

import type { AddressPayload, Address } from "@/types/user";

interface AddressModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSave: (data: AddressPayload) => void;
  address?: Address | null;
}

// Address Form Modal Component
export function AddressModal({
  isOpen,
  onClose,
  onSave,
  address = null,
}: AddressModalProps) {
  const user = useAuthStore().user;
  const [formData, setFormData] = useState<AddressPayload>({
    title: address?.title || "",
    name: address?.name || "",
    address: address?.address || "",
    phone: address?.phone || user?.phone || "",
    city: address?.city || "",
    area: address?.area || "",
    is_default: address?.is_default ? 1 : 0,
    status: 1,
  });

  useEffect(() => {
    setFormData({
      title: address?.title || "",
      name: address?.name || "",
      address: address?.address || "",
      phone: address?.phone || user?.phone || "",
      city: address?.city || "",
      area: address?.area || "",
      is_default: address?.is_default ? 1 : 0,
      status: address?.status || 1,
    });
  }, [address, user?.phone]);

  if (!isOpen) return null;

  const handleSubmit = () => {
    onSave(formData);
    onClose();
    setFormData({
      title: "",
      name: "",
      address: "",
      phone: address?.phone || user?.phone || "",
      city: "",
      area: "",
      is_default: 0,
      status: address?.status || 1,
    });
  };

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>,
  ) => {
    const { name, value, type } = e.target;

    if (type === "checkbox") {
      setFormData((prev) => ({
        ...prev,
        [name]: (e.target as HTMLInputElement).checked ? 1 : 0,
      }));
    } else {
      setFormData((prev) => ({
        ...prev,
        [name]: value,
      }));
    }
  };

  return (
    <div className="fixed inset-0 bg-opacity backdrop-blur-md flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        {/* Header */}
        <div className="flex items-center justify-between p-6 border-b border-gray-200">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl flex items-center justify-center">
              <MapPin className="w-5 h-5 text-white" />
            </div>
            <h2 className="text-xl font-bold text-gray-900">
              {address ? "Edit Address" : "Add New Address"}
            </h2>
          </div>
          <button
            onClick={onClose}
            className="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
          >
            <X className="w-5 h-5 text-gray-500" />
          </button>
        </div>

        {/* Form */}
        <div className="p-6 space-y-4">
          {/* Title */}
          <div>
            <label className="block text-sm font-semibold text-gray-700 mb-2">
              Address Title
            </label>
            <input
              type="text"
              name="title"
              value={formData.title || ""}
              onChange={handleChange}
              placeholder="e.g., Home, Office, etc."
              className="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
            />
          </div>

          {/* Full Name */}
          <div>
            <label className="block text-sm font-semibold text-gray-700 mb-2">
              Full Name
            </label>
            <input
              type="text"
              name="name"
              value={formData.name || ""}
              onChange={handleChange}
              placeholder="Enter your full name"
              className="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
            />
          </div>

          {/* Address */}
          <div>
            <label className="block text-sm font-semibold text-gray-700 mb-2">
              Address
            </label>
            <textarea
              name="address"
              value={formData.address || ""}
              onChange={handleChange}
              placeholder="Enter complete address"
              rows={3}
              className="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent resize-none"
            />
          </div>

          <div>
            <label className="block text-sm font-semibold text-gray-700 mb-2">
              Area
            </label>
            <input
              type="text"
              name="area"
              value={formData.area || ""}
              onChange={handleChange}
              placeholder="Enter your Area"
              className="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
            />
          </div>
          <div>
            <label className="block text-sm font-semibold text-gray-700 mb-2">
              City
            </label>
            <input
              type="text"
              name="city"
              value={formData.city || ""}
              onChange={handleChange}
              placeholder="Enter your City"
              className="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
            />
          </div>

          {/* Phone */}
          <div>
            <label className="block text-sm font-semibold text-gray-700 mb-2">
              Phone Number
            </label>
            <input
              type="tel"
              name="phone"
              value={formData.phone || ""}
              onChange={handleChange}
              placeholder="+1 888-456-668"
              className="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
            />
          </div>

          {/* Default Checkbox */}
          <div className="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
            <input
              type="checkbox"
              name="is_default"
              id="is_default"
              checked={formData.is_default == 1}
              onChange={handleChange}
              className="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500"
            />
            <label
              htmlFor="is_default"
              className="text-sm font-medium text-gray-700 cursor-pointer"
            >
              Set as default address
            </label>
          </div>

          {/* Action Buttons */}
          <div className="flex gap-3 pt-4">
            <button
              onClick={onClose}
              className="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors cursor-pointer"
            >
              Cancel
            </button>
            <button
              onClick={handleSubmit}
              className="flex-1 px-4 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all cursor-pointer"
            >
              {address ? "Save Changes" : "Add Address"}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
