"use client";

import { useState } from "react";

import { showToast } from "@/utils/toast";

import { profileService } from "@/services/user.service";

import { Eye, EyeOff } from "lucide-react";

type Props = {
  isOpen: boolean;
  onClose: () => void;
};

export default function PasswordModal({ isOpen, onClose }: Props) {
  const [form, setForm] = useState({
    current_password: "",
    new_password: "",
    new_password_confirmation: "",
  });

  const [showCurrent, setShowCurrent] = useState(false);
  const [showNew, setShowNew] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [loading, setLoading] = useState(false);

  if (!isOpen) return null;

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async () => {
    setLoading(true);
    if (form.new_password !== form.new_password_confirmation) {
      showToast.error(`Password didnot match`);
      return;
    }

    try {
      await profileService.updatePassword(form);
      onClose();
    } catch (err) {
      showToast.error(`failed to update password`);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
      <div className="relative w-full max-w-md rounded-3xl bg-white shadow-2xl shadow-black/30 animate-in fade-in zoom-in duration-200">
        <div className="p-6 space-y-6">
          {/* Header */}
          <div>
            <h3 className="text-2xl font-extrabold tracking-tight">
              Change Password
            </h3>
            <p className="mt-1 text-sm text-gray-500">
              Choose a strong password to keep your account secure
            </p>
          </div>

          {/* Inputs */}
          <div className="space-y-4">
            {/* Current Password */}
            <div className="relative">
              <input
                type={showCurrent ? "text" : "password"}
                name="current_password"
                placeholder="Current Password"
                value={form.current_password}
                onChange={handleChange}
                className="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:border-pink-500 focus:bg-white focus:ring-4 focus:ring-pink-500/20 transition outline-none"
              />
              <button
                type="button"
                onClick={() => setShowCurrent(!showCurrent)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700"
              >
                {showCurrent ? (
                  <EyeOff className="w-5 h-5" />
                ) : (
                  <Eye className="w-5 h-5" />
                )}
              </button>
            </div>

            {/* New Password */}
            <div className="relative">
              <input
                type={showNew ? "text" : "password"}
                name="new_password"
                placeholder="New Password"
                value={form.new_password}
                onChange={handleChange}
                className="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:border-pink-500 focus:bg-white focus:ring-4 focus:ring-pink-500/20 transition outline-none"
              />
              <button
                type="button"
                onClick={() => setShowNew(!showNew)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700"
              >
                {showNew ? (
                  <EyeOff className="w-5 h-5" />
                ) : (
                  <Eye className="w-5 h-5" />
                )}
              </button>
            </div>

            {/* Confirm Password */}
            <div className="relative">
              <input
                type={showConfirm ? "text" : "password"}
                name="new_password_confirmation"
                placeholder="Confirm New Password"
                value={form.new_password_confirmation}
                onChange={handleChange}
                className="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:border-pink-500 focus:bg-white focus:ring-4 focus:ring-pink-500/20 transition outline-none"
              />
              <button
                type="button"
                onClick={() => setShowConfirm(!showConfirm)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700"
              >
                {showConfirm ? (
                  <EyeOff className="w-5 h-5" />
                ) : (
                  <Eye className="w-5 h-5" />
                )}
              </button>
            </div>
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
              {loading ? "Updating .." : "Update Password"}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
