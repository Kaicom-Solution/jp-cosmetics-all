"use client";

import React, { useState } from "react";
import Image from "next/image";
import {
  Mail,
  MapPin,
  Phone,
  Facebook,
  Twitter,
  Send,
  InstagramIcon,
  Youtube,
} from "lucide-react";
import { BusinessInfo } from "@/types";
import Link from "next/link";

interface FooterProps {
  data: BusinessInfo;
}

export default function Footer({ data }: FooterProps) {
  const [email, setEmail] = useState("");

  const subscribe = (e: React.MouseEvent) => {
    e.preventDefault();
    if (!email) return;
    alert(`Subscribed with: ${email}`);
    setEmail("");
  };

  const footerLogo = data?.footer_logo || "/assets/img/jp-cosmetica-logo.png";

  return (
    <footer className="bg-gradient-to-br from-pink-50 via-white to-rose-50">
      {/* Newsletter Section */}
      <div className="px-[5%] py-16 md:py-20">
        <div className="max-w-4xl mx-auto text-center">
          <div className="inline-block mb-4 px-4 py-1.5 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full text-white text-xs font-semibold tracking-wide">
            NEWSLETTER
          </div>
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4 bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">
            Stay Beautiful with Our Newsletter
          </h2>
          <p className="text-gray-600 mb-8 max-w-2xl mx-auto">
            Get exclusive beauty tips, product launches, and special offers
            delivered straight to your inbox.
          </p>

          <div className="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
            <div className="relative flex-1">
              <Mail className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                type="email"
                placeholder="Enter your email address"
                required
                className="w-full pl-12 pr-4 py-4 rounded-xl bg-white text-sm border-2 border-gray-200 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all shadow-sm"
              />
            </div>

            <button
              onClick={subscribe}
              className="group relative overflow-hidden px-8 py-4 rounded-xl bg-gradient-to-r from-pink-500 to-rose-600 font-semibold text-white shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 cursor-pointer"
            >
              <span className="absolute inset-0 bg-gradient-to-r from-rose-600 to-pink-700 translate-x-full group-hover:translate-x-0 transition-transform duration-500"></span>
              <span className="relative z-10 flex items-center justify-center gap-2">
                Subscribe
                <Send className="w-4 h-4" />
              </span>
            </button>
          </div>
        </div>
      </div>

      {/* Main Footer Content */}
      <div className="border-t border-gray-200">
        <div className="px-[5%] py-12 md:py-16">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            {/* Brand Section */}
            <div className="lg:col-span-1">
              <Link href="/" className="flex-shrink-0 mb-3">
                <Image
                  src={footerLogo}
                  alt="Footer Logo"
                  width={256}
                  height={50}
                  className="w-64 h-auto"
                  priority
                />
              </Link>
              <p className="text-sm text-gray-600 leading-relaxed mb-6 font-medium mt-1">
                Discover elegance and self-care with our exclusive collection of
                premium beauty products.
              </p>

              {/* Social Media */}
              <div className="flex gap-3">
                <Link
                  href={data.facebook_url || "#"}
                  className="group p-3 rounded-full bg-pink-100 text-pink-600 hover:bg-gradient-to-r hover:from-pink-500 hover:to-rose-600 hover:text-white transition-all duration-300 hover:scale-110"
                  target="_blank"
                >
                  <Facebook className="w-4 h-4" />
                </Link>
                <Link
                  href={data.instagram_url || "#"}
                  className="group p-3 rounded-full bg-pink-100 text-pink-600 hover:bg-gradient-to-r hover:from-pink-500 hover:to-rose-600 hover:text-white transition-all duration-300 hover:scale-110"
                  target="_blank"
                >
                  <InstagramIcon className="w-4 h-4" />
                </Link>
                <Link
                  href={data.twitter_url || "#"}
                  className="group p-3 rounded-full bg-pink-100 text-pink-600 hover:bg-gradient-to-r hover:from-pink-500 hover:to-rose-600 hover:text-white transition-all duration-300 hover:scale-110"
                  target="_blank"
                >
                  <Twitter className="w-4 h-4" />
                </Link>
                <Link
                  href={data.youtube_url || "#"}
                  className="group p-3 rounded-full bg-pink-100 text-pink-600 hover:bg-gradient-to-r hover:from-pink-500 hover:to-rose-600 hover:text-white transition-all duration-300 hover:scale-110"
                  target="_blank"
                >
                  <Youtube className="w-4 h-4" />
                </Link>
              </div>
            </div>

            {/* Help & Information */}
            <div>
              <h3 className="text-lg font-bold text-gray-900 mb-5 relative inline-block">
                Help & Information
                <span className="absolute -bottom-2 left-0 w-12 h-1 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full"></span>
              </h3>
              <ul className="space-y-3 text-sm font-medium">
                
                <li>
                  <Link
                    href="/return-policy"
                    className="text-gray-600 hover:text-pink-600 hover:translate-x-1 inline-block transition-all duration-300"
                  >
                    Return Policy
                  </Link>
                </li>
                <li>
                  <Link
                    href="/shipping-delivery"
                    className="text-gray-600 hover:text-pink-600 hover:translate-x-1 inline-block transition-all duration-300"
                  >
                    Shipping Delivery
                  </Link>
                </li>
                <li>
                  <Link
                    href="#"
                    className="text-gray-600 hover:text-pink-600 hover:translate-x-1 inline-block transition-all duration-300"
                  >
                    Voucher Codes
                  </Link>
                </li>
                <li>
                  <Link
                    href="/contact"
                    className="text-gray-600 hover:text-pink-600 hover:translate-x-1 inline-block transition-all duration-300"
                  >
                    Contact Us
                  </Link>
                </li>
              </ul>
            </div>

            {/* About */}
            <div>
              <h3 className="text-lg font-bold text-gray-900 mb-5 relative inline-block">
                About
                <span className="absolute -bottom-2 left-0 w-12 h-1 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full"></span>
              </h3>
              <ul className="space-y-3 text-sm font-medium">
                <li>
                  <Link
                    href="/about-Us"
                    className="text-gray-600 hover:text-pink-600 hover:translate-x-1 inline-block transition-all duration-300"
                  >
                    About Us
                  </Link>
                </li>
                
                <li>
                  <Link
                    href="/privacy-policy"
                    className="text-gray-600 hover:text-pink-600 hover:translate-x-1 inline-block transition-all duration-300"
                  >
                    Privacy Policy
                  </Link>
                </li>
                <li>
                  <Link
                    href="/cookie-policy"
                    className="text-gray-600 hover:text-pink-600 hover:translate-x-1 inline-block transition-all duration-300"
                  >
                    Cookie Policy
                  </Link>
                </li>
                <li>
                  <Link
                    href="/terms-Conditions"
                    className="text-gray-600 hover:text-pink-600 hover:translate-x-1 inline-block transition-all duration-300"
                  >
                    Terms & Conditions
                  </Link>
                </li>
                
              </ul>
            </div>

            {/* Contact */}
            <div>
              <h3 className="text-lg font-bold text-gray-900 mb-5 relative inline-block">
                Get In Touch
                <span className="absolute -bottom-2 left-0 w-12 h-1 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full"></span>
              </h3>

              <ul className="space-y-4 text-sm font-medium">
                <li className="flex items-start gap-3 text-gray-600">
                  <MapPin className="w-5 h-5 text-pink-600 flex-shrink-0 mt-0.5" />
                  <div>
                    {data?.address || "123 Beauty St., Glamour City, PC 45678"}
                  </div>
                </li>
                <li className="flex items-center gap-3 text-gray-600">
                  <Phone className="w-5 h-5 text-pink-600 flex-shrink-0" />
                  <div>
                    <p>{data?.business_phone || "+0.888.456.668"}</p>
                    <p className="text-xs text-gray-500 mt-0.5">
                      (+0122.33.44.55)
                    </p>
                  </div>
                </li>
                <li className="flex items-center gap-3 text-gray-600">
                  <Mail className="w-5 h-5 text-pink-600 flex-shrink-0" />
                  <a
                    href={`mailto:${
                      data?.business_email || "support@cosmetica.com"
                    }`}
                    className="hover:text-pink-600 transition-colors"
                  >
                    {data?.business_email || "support@cosmetica.com"}
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Bar */}
      <div className="border-t border-gray-200 bg-white/50">
        <div className="px-[5%] py-6">
          <div className="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600">
            <p>
              © {new Date().getFullYear()}{" "}
              <span className="font-semibold text-pink-600">Cosmetica</span>.
              All rights reserved.
            </p>
            <div className="flex items-center gap-6 font-medium">
              <Link href="#" className="hover:text-pink-600 transition-colors">
                Privacy Policy
              </Link>
              <a href="#" className="hover:text-pink-600 transition-colors">
                Terms & Conditions
              </a>
              
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}
