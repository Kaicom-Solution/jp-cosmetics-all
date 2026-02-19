"use client";

import { useState } from "react";
import { showToast } from "@/utils/toast";

import { useAuthStore } from "@/store/authStore";
import { profileService } from "@/services/user.service";

type Props = {
  isOpen: boolean;
  onClose: () => void;
};

export default function PersonalInfoModal({ isOpen, onClose }: Props) {
  const user = useAuthStore().user;

  const [form, setForm] = useState({
    name: user?.name || "",
    email: user?.email || "",
    phone: user?.phone || "",
  });

  if (!isOpen) return null;

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async () => {
    try {
      const user = await profileService.updateProfile(form);
      useAuthStore.getState().setUser(user);
      onClose();
    } catch (err) {
      showToast.error(`failed to get profile data`);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
      <div className="relative w-full max-w-md rounded-3xl bg-white shadow-2xl shadow-black/20 animate-in fade-in zoom-in duration-200">
        <div className="p-6 space-y-6">
          <div>
            <h3 className="text-2xl font-extrabold tracking-tight">
              Update Personal Info
            </h3>
            <p className="text-sm text-gray-500 mt-1">
              Keep your profile up to date
            </p>
          </div>

          <div className="space-y-4">
            <input
              name="name"
              placeholder="Full Name"
              value={form.name}
              onChange={handleChange}
              className="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:border-pink-500 focus:bg-white focus:ring-4 focus:ring-pink-500/20 transition outline-none"
            />

            <input
              name="email"
              placeholder="Email Address"
              value={form.email}
              disabled={!!user?.phone}
              onChange={handleChange}
              className="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:border-pink-500 focus:bg-white focus:ring-4 focus:ring-pink-500/20 transition outline-none"
            />

            <input
              name="phone"
              placeholder="Phone Number"
              value={form.phone}
              disabled={!!user?.phone}
              onChange={handleChange}
              className="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:border-pink-500 focus:bg-white focus:ring-4 focus:ring-pink-500/20  transition outline-none"
            />
          </div>

          {/* Actions */}
          <div className="flex items-center justify-end gap-3 pt-2">
            <button
              onClick={onClose}
              className="rounded-xl border border-gray-200 px-4 py-2 text-gray-600 hover:bg-gray-100 transition cursor-pointer"
            >
              Cancel
            </button>

            <button
              onClick={handleSubmit}
              className="rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 px-5 py-2 font-semibold text-white shadow-lg shadow-pink-500/30 hover:scale-[1.02] active:scale-[0.98] transition cursor-pointer"
            >
              Save Changes
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
