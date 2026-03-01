"use client";

import { useEffect, useState } from "react";

import apiClient from "@/lib/axios";

interface TermsCondition {
  type: string;
  description: string;
}

export default function TACComponent() {
  const [tacData, setTacData] = useState<TermsCondition>();
  const [loading, setLoading] = useState(true);

  const getTacData = async () => {
    setLoading(true);
    try {
      const response = await apiClient.get(`/settings/terms_conditions`);
      const data: TermsCondition = response?.data?.data || {};
      setTacData(data);
    } catch (error: any) {
      // console.error(error);
      // toast.error(error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    getTacData();
  }, []);
  return (
    <div className="bg-gradient-to-b from-pink-50/30 to-white min-h-screen">
      <div className="bg-gradient-to-r from-pink-600 to-rose-600 text-white py-16">
        <div className="max-w-7xl mx-auto px-[5%]">
          <h1 className="text-4xl md:text-5xl font-bold mb-4 text-center">
            Terms & Condition
          </h1>
          <p className="text-xl text-center text-pink-50 max-w-2xl mx-auto mb-8">
           Read our terms and condition
          </p>
        </div>
      </div>
      <div className="max-w-7xl mx-auto px-[5%] py-12">
        <div
          className=""
          dangerouslySetInnerHTML={{
            __html: tacData?.description ?? "",
          }}
        />
      </div>
    </div>
  );
}
