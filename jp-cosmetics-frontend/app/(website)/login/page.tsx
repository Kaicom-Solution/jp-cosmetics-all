"use client";

import React, { useState } from "react";
import {
  Mail,
  Lock,
  Eye,
  EyeOff,
  ArrowRight,
  CheckCircle,
  Facebook,
  Chrome,
  Apple,
  ShieldCheck,
  Sparkles,
  User,
  Phone,
} from "lucide-react";
import Link from "next/link";

import { useAuthStore } from "@/store/authStore";
import { useRouter, useSearchParams } from "next/navigation";

import { showToast } from "@/utils/toast";

const Login = () => {
  const [showPassword, setShowPassword] = useState(false);
  const [rememberMe, setRememberMe] = useState(false);
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });

  const login = useAuthStore((s) => s.login);
  const router = useRouter();
  const searchParams = useSearchParams();

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await login(formData.email, formData.password);
      const redirectTo = searchParams.get("redirect") || "/user/dashboard";

      router.push(redirectTo);
    } catch (err) {
      console.error(err);
      showToast.error("Invalid email or password");
    }
  };

  const benefits = [
    "Exclusive member discounts",
    "Early access to new products",
    "Free shipping on all orders",
    "Birthday special offers",
    "Personalized recommendations",
  ];

  return (
    <div className="bg-gradient-to-br from-pink-50 via-white to-rose-50 flex items-center justify-center py-12 px-4">
      <div className="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        {/* Left Side - Benefits & Branding */}
        <div className="hidden lg:block">
          <div className="space-y-8">
            {/* Logo & Welcome */}
            <div>
              <div className="flex items-center gap-3 mb-6">
                <div className="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center">
                  <Sparkles className="w-6 h-6 text-white" />
                </div>
                <span className="text-2xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">
                  Cosmetica
                </span>
              </div>
              <h1 className="text-4xl font-bold text-gray-900 mb-4">
                Welcome Back!
              </h1>
              <p className="text-lg text-gray-600">
                Sign in to access your personalized beauty journey
              </p>
            </div>

            {/* Benefits List */}
            <div className="space-y-4">
              <h3 className="text-xl font-bold text-gray-900 flex items-center gap-2">
                <ShieldCheck className="w-6 h-6 text-pink-600" />
                Member Benefits
              </h3>
              {benefits.map((benefit, index) => (
                <div key={index} className="flex items-start gap-3 group">
                  <CheckCircle className="w-5 h-5 text-pink-600 flex-shrink-0 mt-0.5 group-hover:scale-110 transition-transform" />
                  <span className="text-gray-700">{benefit}</span>
                </div>
              ))}
            </div>

            {/* Decorative Image */}
            {/* <div className="relative aspect-video bg-gradient-to-br from-pink-200 to-rose-200 rounded-2xl overflow-hidden">
              <div className="absolute inset-0 flex items-center justify-center">
                <div className="text-center">
                  <Sparkles className="w-20 h-20 text-white opacity-50 mx-auto mb-4" />
                  <p className="text-white font-semibold text-lg">
                    Your Beauty Journey Starts Here
                  </p>
                </div>
              </div>
            </div> */}
          </div>
        </div>

        {/* Right Side - Form */}
        <div className="w-full">
          <div className="bg-white rounded-3xl shadow-2xl p-8 lg:p-10">
            {/* Mobile Logo */}
            <div className="lg:hidden flex items-center justify-center gap-3 mb-8">
              <div className="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center">
                <Sparkles className="w-5 h-5 text-white" />
              </div>
              <span className="text-xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">
                Cosmetica
              </span>
            </div>

            {/* Social Login Buttons */}
            {/* <div className="space-y-3 mb-6">
              <button className="w-full flex items-center justify-center gap-3 px-4 py-3 border-2 border-gray-300 rounded-xl hover:border-pink-500 hover:bg-pink-50 transition-all font-semibold text-gray-700 cursor-pointer">
                <Chrome className="w-5 h-5" />
                Continue with Google
              </button>
            </div>

            <div className="relative mb-6">
              <div className="absolute inset-0 flex items-center">
                <div className="w-full border-t border-gray-300"></div>
              </div>
              <div className="relative flex justify-center text-sm">
                <span className="px-4 bg-white text-gray-500 font-medium">
                  Or continue with email
                </span>
              </div>
            </div> */}

            {/* Form */}
            <form onSubmit={handleSubmit} className="space-y-5">
              {/* Email */}
              <div>
                <label
                  htmlFor="email"
                  className="block text-sm font-semibold text-gray-900 mb-2"
                >
                  Email Address
                </label>
                <div className="relative">
                  <Mail className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                  <input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    required
                    className="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:border-pink-500 focus:outline-none transition-colors"
                    placeholder="your.email@example.com"
                  />
                </div>
              </div>

              {/* Password */}
              <div>
                <label
                  htmlFor="password"
                  className="block text-sm font-semibold text-gray-900 mb-2"
                >
                  Password
                </label>
                <div className="relative">
                  <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                  <input
                    type={showPassword ? "text" : "password"}
                    id="password"
                    name="password"
                    value={formData.password}
                    onChange={handleChange}
                    required
                    min={6}
                    className="w-full pl-12 pr-12 py-3 border-2 border-gray-300 rounded-xl focus:border-pink-500 focus:outline-none transition-colors"
                    placeholder="Enter your password"
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                  >
                    {showPassword ? (
                      <EyeOff className="w-5 h-5" />
                    ) : (
                      <Eye className="w-5 h-5" />
                    )}
                  </button>
                </div>
              </div>

              {/* Remember Me & Forgot Password (Login) */}
              <div className="flex items-center justify-between">
                {/* <label className="flex items-center gap-2 cursor-pointer group">
                  <input
                    type="checkbox"
                    checked={rememberMe}
                    onChange={(e) => setRememberMe(e.target.checked)}
                    className="w-4 h-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500 cursor-pointer"
                  />
                  <span className="text-sm text-gray-700 group-hover:text-pink-600 transition-colors">
                    Remember me
                  </span>
                </label> */}
                <label className="flex items-center gap-2 cursor-pointer group">
                  <span className="text-sm text-gray-700 group-hover:text-pink-600 transition-colors">
                    Don't remember password
                  </span>
                </label>
                <Link
                  href="/forgot-password"
                  className="text-sm font-semibold text-pink-600 hover:text-pink-700 cursor-pointer"
                >
                  Forgot Password?
                </Link>
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                className="w-full py-4 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer"
              >
                Sign In
                <ArrowRight className="w-5 h-5" />
              </button>
            </form>

            {/* Switch Mode */}
            <p className="text-center text-sm text-gray-600 mt-6">
              Don't have an account?{" "}
              <Link href="/signup">
                <button className="font-semibold text-pink-600 hover:text-pink-700 cursor-pointer">
                  Sign Up
                </button>
              </Link>
            </p>

            {/* Security Note */}
            <div className="mt-6 p-4 bg-pink-50 rounded-xl flex items-start gap-3">
              <ShieldCheck className="w-5 h-5 text-pink-600 flex-shrink-0 mt-0.5" />
              <p className="text-xs text-gray-600">
                Your data is protected with 256-bit SSL encryption. We never
                share your personal information.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Login;
