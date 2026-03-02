"use client";

import { useState } from "react";
import apiClient from "@/lib/axios";
import { showToast } from "@/utils/toast";

type SubmitReviewProps = {
  productId: number | string;
  onSuccess?: () => void;
  orderId: number | null;
};

export default function ReviewSubmitComponent({
  productId,
  orderId,
  onSuccess,
}: SubmitReviewProps) {
  const [rating, setRating] = useState(0);
  const [review, setReview] = useState("");
  const [submitting, setSubmitting] = useState(false);

  const [loading, setLoading] = useState(false);

  const handleSubmit = async () => {
    if (!rating || rating <= 0) {
      showToast.error("Please select a rating");
      return;
    }

    if (!review.trim()) {
      showToast.error("Please write a review");
      return;
    }

    setSubmitting(true);

    const payload = {
      rating,
      review: review.trim(),
    };

    try {
      const response = await apiClient.post(
        `order/${orderId}/${productId}/review`,
        payload,
      );

      showToast.success("Review submitted successfully");

      setRating(0);
      setReview("");

      onSuccess?.();
    } catch (error: any) {
      showToast.error(error?.response?.data?.message || "Something went wrong");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="mt-3 space-y-3 rounded-xl border border-gray-200 p-4 bg-white">
      {/* Rating */}
      <div className="flex items-center gap-2">
        {[1, 2, 3, 4, 5].map((star) => (
          <button
            key={star}
            type="button"
            onClick={() => setRating(star)}
            className={`text-xl transition ${
              star <= rating ? "text-yellow-400" : "text-gray-300"
            }`}
          >
            ★
          </button>
        ))}
      </div>

      {/* Comment */}
      <textarea
        value={review}
        onChange={(e) => setReview(e.target.value)}
        placeholder="Write your review..."
        className="w-full rounded-lg border border-gray-300 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500"
        rows={3}
      />

      {/* Submit */}
      <div className="flex justify-end">
        <button
          disabled={!rating || !review.trim() || submitting}
          onClick={handleSubmit}
          className="rounded-lg bg-gradient-to-r from-pink-500 to-rose-600 px-4 py-1.5 text-xs font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
        >
          {submitting ? "Submitting..." : "Submit Review"}
        </button>
      </div>
    </div>
  );
}
