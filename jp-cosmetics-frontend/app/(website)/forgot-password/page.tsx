"use client";

import { useState } from "react";
import Link from "next/link";
import { Mail, ArrowLeft, Lock, KeyRound } from "lucide-react";
import apiClient from "@/lib/axios";
import { useRouter } from "next/navigation";

export default function ForgotPasswordPage() {
  const [step, setStep] = useState<1 | 2>(1);

  const [email, setEmail] = useState("");
  const [otp, setOtp] = useState("");

  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");

  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState("");
  const [error, setError] = useState("");

  const router = useRouter();

  /* ---------------- STEP 1: SEND OTP ---------------- */
  const handleSendOtp = async (e: React.FormEvent) => {
    e.preventDefault();

    setError("");
    setSuccess("");

    if (!email) {
      setError("Email is required.");
      return;
    }

    setLoading(true);

    try {
      await apiClient.post("/forgot-password", { email });

      setSuccess("OTP sent successfully. Check your inbox.");
      setStep(2); // ✅ Move to next step
    } catch (err: any) {
      setError(err?.response?.data?.message || "Failed to send OTP.");
    } finally {
      setLoading(false);
    }
  };

  /* ---------------- STEP 2: RESET PASSWORD ---------------- */
  const handleResetPassword = async (e: React.FormEvent) => {
    e.preventDefault();

    setError("");
    setSuccess("");

    if (!otp) {
      setError("OTP is required.");
      return;
    }

    if (!password || !confirmPassword) {
      setError("Both password fields are required.");
      return;
    }

    if (password !== confirmPassword) {
      setError("Passwords do not match.");
      return;
    }

    setLoading(true);

    try {
      await apiClient.post("/reset-password", {
        email,
        otp,
        new_password: password,
        new_password_confirmation: confirmPassword,
      });
      
      setSuccess("Password reset successful! Redirecting to login...");
      setStep(1);
      // Clear form
      setEmail("");
      setOtp("");
      setPassword("");
      setConfirmPassword("");

      setTimeout(() => {
        router.push("/login");
      }, 3000);
    } catch (err: any) {
      setError(err?.response?.data?.message || "Reset failed.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center px-4 bg-gradient-to-bl from-pink-200 via-white to-rose-200 relative overflow-hidden">
      {/* Decorative Blobs */}
      <div className="absolute top-[-120px] left-[-120px] w-[280px] h-[280px] bg-pink-400 rounded-full blur-3xl opacity-30"></div>
      <div className="absolute bottom-[-140px] right-[-140px] w-[320px] h-[320px] bg-rose-400 rounded-full blur-3xl opacity-30"></div>

      {/* Card */}
      <div className="w-full max-w-md backdrop-blur-xl bg-white/70 border border-white/40 shadow-2xl rounded-3xl p-8 space-y-7 relative z-10">
        {/* Header */}
        <div className="text-center space-y-3">
          <div className="w-16 h-16 mx-auto flex items-center justify-center rounded-2xl bg-gradient-to-r from-pink-500 to-rose-500 shadow-lg">
            {step === 1 ? (
              <Mail className="text-white w-7 h-7" />
            ) : (
              <KeyRound className="text-white w-7 h-7" />
            )}
          </div>

          <h1 className="text-3xl font-extrabold text-gray-900 tracking-tight">
            {step === 1 ? "Forgot Password?" : "Reset Password"}
          </h1>

          <p className="text-gray-600 text-sm leading-relaxed">
            {step === 1
              ? "Enter your email and we’ll send you an OTP."
              : "Enter OTP and set your new password."}
          </p>
        </div>

        {/* Alerts */}
        {error && (
          <p className="text-sm text-red-600 bg-red-50 border border-red-200 p-3 rounded-xl">
            {error}
          </p>
        )}

        {success && (
          <p className="text-sm text-green-700 bg-green-50 border border-green-200 p-3 rounded-xl">
            {success}
          </p>
        )}

        {/* ---------------- FORM STEP 1 ---------------- */}
        {step === 1 && (
          <form onSubmit={handleSendOtp} className="space-y-5">
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                Email Address
              </label>

              <div className="flex items-center gap-2 px-4 py-3 rounded-xl border border-pink-500 bg-white focus-within:ring-1 focus-within:ring-pink-500">
                <Mail className="w-5 h-5 text-gray-400" />
                <input
                  type="email"
                  placeholder="example@gmail.com"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className="w-full outline-none bg-transparent"
                />
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-pink-500 to-rose-500 shadow-lg hover:scale-[1.02] transition disabled:opacity-60 cursor-pointer"
            >
              {loading ? "Sending..." : "Send OTP"}
            </button>
          </form>
        )}

        {/* ---------------- FORM STEP 2 ---------------- */}
        {step === 2 && (
          <form onSubmit={handleResetPassword} className="space-y-5">
            {/* OTP */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                OTP Code
              </label>

              <input
                type="text"
                placeholder="Enter OTP"
                value={otp}
                onChange={(e) => setOtp(e.target.value)}
                className="w-full px-4 py-3 rounded-xl border border-pink-500 focus:ring-1 focus:ring-pink-500 outline-none"
              />
            </div>

            {/* Password */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                New Password
              </label>

              <div className="flex items-center gap-2 px-4 py-3 rounded-xl border border-pink-500 bg-white focus-within:ring-1 focus-within:ring-pink-500">
                <Lock className="w-5 h-5 text-gray-400" />
                <input
                  type="password"
                  placeholder="New password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className="w-full outline-none bg-transparent"
                />
              </div>
            </div>

            {/* Confirm Password */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                Confirm Password
              </label>

              <input
                type="password"
                placeholder="Confirm password"
                value={confirmPassword}
                onChange={(e) => setConfirmPassword(e.target.value)}
                className="w-full px-4 py-3 rounded-xl border border-pink-500 focus:ring-1 focus:ring-pink-500 outline-none"
              />
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-pink-500 to-rose-500 shadow-lg hover:scale-[1.02] transition disabled:opacity-60 cursor-pointer"
            >
              {loading ? "Resetting..." : "Reset Password"}
            </button>
          </form>
        )}

        {/* Back Link */}
        <div className="text-center text-sm text-gray-600">
          Remember your password?{" "}
          <Link
            href="/login"
            className="inline-flex items-center gap-1 font-semibold text-pink-600 hover:text-rose-600 transition"
          >
            <ArrowLeft className="w-4 h-4" />
            Back to Login
          </Link>
        </div>
      </div>
    </div>
  );
}
