"use client";

import { X } from "lucide-react";
import { useState, useEffect } from "react";
import apiClient from "@/lib/axios";

interface Promotion {
  id: number;
  title: string | null;
  description: string | null;
  image: string | null;
  button_text: string | null;
  button_url: string | null;
}

export default function PopUpPromotion() {
  const [showPromotion, setShowPromotion] = useState(false);
  const [promotion, setPromotion] = useState<Promotion | null>(null);

  const getPopPromotion = async () => {
    try {
      const response = await apiClient.get(`/settings/promotion_popup`);
      const data = response?.data?.data;

      if (data) {
        setPromotion(data);
        setShowPromotion(true);
      }
    } catch (error) {
      console.error(error);
    }
  };

  useEffect(() => {
    getPopPromotion();
  }, []);

  if (!showPromotion || !promotion) return null;

  const {
    title,
    description,
    image,
    button_text,
    button_url,
  } = promotion;

  const hasText = title || description;

  return (
    <div className="fixed z-50 inset-0 bg-black/60 p-5 flex justify-center items-center">
      <div className="max-w-4xl w-full bg-white rounded-xl relative overflow-hidden">

        {/* Close Button */}
        <X
          onClick={() => setShowPromotion(false)}
          className="absolute top-3 right-3 bg-red-500 text-white rounded-full p-1 cursor-pointer hover:rotate-90 duration-300 z-10"
        />

        {/* If only image */}
        {image && !hasText && (
          <img
            src={image}
            alt="Promotion"
            className="w-full h-auto object-cover"
          />
        )}

        {/* If image + text */}
        {image && hasText && (
          <div className="grid md:grid-cols-2">
            <img
              src={image}
              alt="Promotion"
              className="w-full h-full object-cover"
            />

            <div className="p-6 flex flex-col justify-center space-y-4">
              {title && (
                <h2 className="text-2xl font-bold">{title}</h2>
              )}

              {description && (
                <p className="text-gray-600">{description}</p>
              )}

              {button_url && (
                <a
                  href={button_url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition"
                >
                  {button_text || "Learn More"}
                </a>
              )}
            </div>
          </div>
        )}

        {/* If no image but text exists */}
        {!image && hasText && (
          <div className="p-6 text-center space-y-4">
            {title && (
              <h2 className="text-2xl font-bold">{title}</h2>
            )}

            {description && (
              <p className="text-gray-600">{description}</p>
            )}

            {button_url && (
              <a
                href={button_url}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition"
              >
                {button_text || "Learn More"}
              </a>
            )}
          </div>
        )}
      </div>
    </div>
  );
}