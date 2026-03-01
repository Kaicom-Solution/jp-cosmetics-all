"use client";

import { useState } from "react";

type SubmitReviewProps = {
  productId: number | string;
  onSuccess?: () => void;
};

export default function ReviewSubmitComponent({
  productId,
  onSuccess,
}: SubmitReviewProps) {
  const [rating, setRating] = useState(0);
  const [comment, setComment] = useState("");
  const [submitting, setSubmitting] = useState(false);

  const handleSubmit = async () => {
    if (!rating || !comment.trim()) return;

    try {
      setSubmitting(true);

      // 🔥 Replace with your real API call
      await fetch("/api/reviews", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          productId,
          rating,
          comment,
        }),
      });

      setRating(0);
      setComment("");

      onSuccess?.();
    } catch (err) {
      console.error(err);
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
        value={comment}
        onChange={(e) => setComment(e.target.value)}
        placeholder="Write your review..."
        className="w-full rounded-lg border border-gray-300 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500"
        rows={3}
      />

      {/* Submit */}
      <div className="flex justify-end">
        <button
          disabled={!rating || !comment.trim() || submitting}
          onClick={handleSubmit}
          className="rounded-lg bg-gradient-to-r from-pink-500 to-rose-600 px-4 py-1.5 text-xs font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
        >
          {submitting ? "Submitting..." : "Submit Review"}
        </button>
      </div>
    </div>
  );
}
