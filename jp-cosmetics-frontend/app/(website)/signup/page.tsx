"use client";

import React, { useState } from "react";
import {
  Mail,
  Lock,
  Eye,
  EyeOff,
  User,
  Phone,
  CheckCircle,
  AlertCircle,
  ShieldCheck,
  Sparkles,
  Gift,
  Truck,
  Crown,
  Heart,
} from "lucide-react";

import Link from "next/link";
import { useRouter } from "next/navigation";

import apiClient from "@/lib/axios";
import { showToast } from "@/utils/toast";

const Signup = () => {
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [agreeToTerms, setAgreeToTerms] = useState(false);
  const [subscribeNewsletter, setSubscribeNewsletter] = useState(true);
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    password: "",
    confirmPassword: "",
  });

  const [passwordStrength, setPasswordStrength] = useState({
    score: 0,
    feedback: "",
  });

  const router = useRouter();

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const res = await apiClient.post("/register", formData);
      router.push("/login");
    } catch (err: any) {
      const errors = err?.response?.data?.errors;

      if (errors) {
        Object.values(errors).forEach((messages: any) => {
          messages.forEach((msg: string) => {
            showToast.error(msg);
          });
        });
      } else {
        showToast.error("Something went wrong");
      }
    }
  };

  const benefits = [
    {
      icon: <Gift className="w-6 h-6" />,
      title: "Welcome Gift",
      description: "Get 20% off your first purchase",
    },
    {
      icon: <Truck className="w-6 h-6" />,
      title: "Free Shipping",
      description: "On all orders above ৳500",
    },
    {
      icon: <Crown className="w-6 h-6" />,
      title: "VIP Access",
      description: "Early access to new collections",
    },
    {
      icon: <Heart className="w-6 h-6" />,
      title: "Birthday Rewards",
      description: "Special gifts on your birthday",
    },
  ];

  return (
    <div className="min-h-screen bg-gradient-to-br from-pink-50 via-white to-rose-50 py-12 px-4">
      <div className="max-w-7xl mx-auto">
        {/* Header */}
        <div className="text-center mb-8">
          <Link
            href="/"
            className="inline-flex items-center gap-3 mb-6 group cursor-pointer"
          >
            <div className="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
              <Sparkles className="w-6 h-6 text-white" />
            </div>
            <span className="text-2xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">
              Cosmetica
            </span>
          </Link>
          <h1 className="text-4xl font-bold text-gray-900 mb-3">
            Create Your Account
          </h1>
          <p className="text-lg text-gray-600">
            Join thousands of beauty enthusiasts today!
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-8">
          {/* Main Form */}
          <div className="order-2 lg:order-1">
            <div className="bg-white rounded-3xl shadow-2xl p-8 lg:p-10">
              {/* Form */}
              <form onSubmit={handleSubmit} className="space-y-5">
                {/* Personal Info Fields */}
                <div className="grid grid-cols-1 gap-4">
                  <div>
                    <label
                      htmlFor="name"
                      className="block text-sm font-semibold text-gray-900 mb-2"
                    >
                      Name *
                    </label>
                    <div className="relative">
                      <User className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                      <input
                        type="text"
                        id="name"
                        name="name"
                        value={formData.name}
                        onChange={handleChange}
                        required
                        className="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:border-pink-500 focus:outline-none transition-colors"
                        placeholder="John"
                      />
                    </div>
                  </div>
                </div>

                <div>
                  <label
                    htmlFor="email"
                    className="block text-sm font-semibold text-gray-900 mb-2"
                  >
                    Email Address *
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
                      placeholder="john.doe@example.com"
                    />
                  </div>
                </div>

                <div>
                  <label
                    htmlFor="phone"
                    className="block text-sm font-semibold text-gray-900 mb-2"
                  >
                    Phone Number *
                  </label>
                  <div className="relative">
                    <Phone className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input
                      type="tel"
                      id="phone"
                      name="phone"
                      value={formData.phone}
                      onChange={handleChange}
                      required
                      className="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:border-pink-500 focus:outline-none transition-colors"
                      placeholder="+880 1234 567890"
                    />
                  </div>
                </div>

                <div>
                  <label
                    htmlFor="password"
                    className="block text-sm font-semibold text-gray-900 mb-2"
                  >
                    Password *
                  </label>
                  <div className="relative">
                    <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input
                      type={showPassword ? "text" : "password"}
                      id="password"
                      name="password"
                      value={formData.password}
                      onChange={handleChange}
                      min={6}
                      required
                      className="w-full pl-12 pr-12 py-3 border-2 border-gray-300 rounded-xl focus:border-pink-500 focus:outline-none transition-colors"
                      placeholder="Create a strong password"
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

                <div>
                  <label
                    htmlFor="confirmPassword"
                    className="block text-sm font-semibold text-gray-900 mb-2"
                  >
                    Confirm Password *
                  </label>
                  <div className="relative">
                    <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input
                      type={showConfirmPassword ? "text" : "password"}
                      id="confirmPassword"
                      name="confirmPassword"
                      value={formData.confirmPassword}
                      onChange={handleChange}
                      required
                      min={6}
                      className="w-full pl-12 pr-12 py-3 border-2 border-gray-300 rounded-xl focus:border-pink-500 focus:outline-none transition-colors"
                      placeholder="Re-enter your password"
                    />
                    <button
                      type="button"
                      onClick={() =>
                        setShowConfirmPassword(!showConfirmPassword)
                      }
                      className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                    >
                      {showConfirmPassword ? (
                        <EyeOff className="w-5 h-5" />
                      ) : (
                        <Eye className="w-5 h-5" />
                      )}
                    </button>
                  </div>
                  {formData.confirmPassword &&
                    formData.password !== formData.confirmPassword && (
                      <div className="flex items-center gap-2 mt-2 text-red-600">
                        <AlertCircle className="w-4 h-4" />
                        <span className="text-xs">Passwords do not match</span>
                      </div>
                    )}
                </div>

                <div className="p-4 bg-gray-50 rounded-xl">
                  <ul className="space-y-1 text-xs text-gray-600">
                    <li className="flex items-center gap-2">
                      <CheckCircle className={`w-3.5 h-3.5 text-rose-600`} />
                      Premium Quality
                    </li>
                    <li className="flex items-center gap-2">
                      <CheckCircle className={`w-3.5 h-3.5 text-rose-600`} />
                      Best Prices
                    </li>
                    <li className="flex items-center gap-2">
                      <CheckCircle className={`w-3.5 h-3.5 text-rose-600`} />
                      Gift Voucher
                    </li>
                    <li className="flex items-center gap-2">
                      <CheckCircle className={`w-3.5 h-3.5 text-rose-600`} />
                      Fast Delivery
                    </li>
                  </ul>
                </div>

                {/* Newsletter Subscription */}
                <div className="space-y-3">
                  <label className="flex items-start gap-3 cursor-pointer group">
                    <input
                      type="checkbox"
                      checked={subscribeNewsletter}
                      onChange={(e) => setSubscribeNewsletter(e.target.checked)}
                      className="w-5 h-5 rounded border-gray-300 text-pink-600 focus:ring-pink-500 cursor-pointer mt-0.5"
                    />
                    <div>
                      <span className="text-sm font-medium text-gray-900 group-hover:text-pink-600 transition-colors">
                        Subscribe to our newsletter
                      </span>
                      <p className="text-xs text-gray-600 mt-1">
                        Get exclusive offers, beauty tips, and early access to
                        new products
                      </p>
                    </div>
                  </label>

                  <label className="flex items-start gap-3 cursor-pointer group">
                    <input
                      type="checkbox"
                      checked={agreeToTerms}
                      onChange={(e) => setAgreeToTerms(e.target.checked)}
                      required
                      className="w-5 h-5 rounded border-gray-300 text-pink-600 focus:ring-pink-500 cursor-pointer mt-0.5"
                    />
                    <span className="text-sm text-gray-700">
                      I agree to the{" "}
                      <Link
                        href="/terms"
                        className="text-pink-600 hover:text-pink-700 font-semibold cursor-pointer"
                      >
                        Terms & Conditions
                      </Link>{" "}
                      and{" "}
                      <Link
                        href="/privacy"
                        className="text-pink-600 hover:text-pink-700 font-semibold cursor-pointer"
                      >
                        Privacy Policy
                      </Link>
                    </span>
                  </label>
                </div>

                {/* Submit Button */}
                <button
                  type="submit"
                  className="w-full py-4 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl hover:scale-[1.02] cursor-pointer"
                >
                  Create Account
                </button>
              </form>

              {/* Sign In Link */}
              <p className="text-center text-sm text-gray-600 mt-6">
                Already have an account?{" "}
                <Link
                  href="/login"
                  className="font-semibold text-pink-600 hover:text-pink-700 cursor-pointer"
                >
                  Sign In
                </Link>
              </p>
            </div>
          </div>

          {/* Benefits Sidebar */}
          <div className="order-1 lg:order-2 space-y-6">
            {/* Welcome Offer */}
            <div className="bg-gradient-to-br from-pink-500 to-rose-600 rounded-3xl shadow-2xl p-8 text-white">
              <div className="flex items-center gap-3 mb-4">
                <div className="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                  <Gift className="w-6 h-6" />
                </div>
                <div>
                  <p className="text-sm font-medium text-pink-100">
                    Welcome Offer
                  </p>
                  <p className="text-2xl font-bold">20% OFF</p>
                </div>
              </div>
              <p className="text-pink-50 text-sm">
                Join now and get an exclusive 20% discount on your first
                purchase. Limited time offer!
              </p>
            </div>

            {/* Benefits List */}
            <div className="bg-white rounded-3xl shadow-lg p-6">
              <h3 className="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <Sparkles className="w-5 h-5 text-pink-600" />
                Member Benefits
              </h3>
              <div className="space-y-4">
                {benefits.map((benefit, index) => (
                  <div
                    key={index}
                    className="flex items-start gap-3 p-3 rounded-xl hover:bg-pink-50 transition-colors"
                  >
                    <div className="w-10 h-10 bg-gradient-to-br from-pink-100 to-rose-100 rounded-lg flex items-center justify-center text-pink-600 flex-shrink-0">
                      {benefit.icon}
                    </div>
                    <div>
                      <h4 className="font-semibold text-gray-900 text-sm">
                        {benefit.title}
                      </h4>
                      <p className="text-xs text-gray-600">
                        {benefit.description}
                      </p>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Security Badge */}
            <div className="bg-white rounded-3xl shadow-lg p-6">
              <div className="flex items-start gap-3">
                <ShieldCheck className="w-8 h-8 text-pink-600 flex-shrink-0" />
                <div>
                  <h4 className="font-bold text-gray-900 mb-2">
                    Your Data is Safe
                  </h4>
                  <p className="text-sm text-gray-600 leading-relaxed">
                    We use industry-standard 256-bit SSL encryption to protect
                    your personal information. Your privacy is our top priority.
                  </p>
                </div>
              </div>
            </div>

            {/* Trust Indicators */}
            <div className="bg-gray-50 shadow-lg rounded-3xl p-6">
              <p className="text-xs text-gray-600 text-center mb-3">
                Trusted by over 50,000+ customers
              </p>
              <div className="flex items-center justify-center gap-6">
                <div className="text-center">
                  <p className="text-2xl font-bold text-pink-600">50k+</p>
                  <p className="text-xs text-gray-600">Happy Customers</p>
                </div>
                <div className="w-px h-12 bg-gray-300"></div>
                <div className="text-center">
                  <p className="text-2xl font-bold text-pink-600">4.8★</p>
                  <p className="text-xs text-gray-600">Average Rating</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Signup;
