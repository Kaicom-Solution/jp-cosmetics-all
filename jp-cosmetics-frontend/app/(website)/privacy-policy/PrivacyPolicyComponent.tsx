"use client";

import { useEffect, useState } from "react";

import apiClient from "@/lib/axios";

interface PrivacyPolicy {
  type: string;
  description: string;
}

export default function PrivacyPolicyComponent() {
  const [privacyPolicyData, setPrivacyPolicyData] = useState<PrivacyPolicy>();
  const [loading, setLoading] = useState(true);

  const getPrivacyPolicyData = async () => {
    setLoading(true);
    try {
      const response = await apiClient.get(`/settings/privacy_policy`);
      const data: PrivacyPolicy = response?.data?.data || {};
      setPrivacyPolicyData(data);
    } catch (error: any) {
      // console.error(error);
      // toast.error(error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    getPrivacyPolicyData();
  }, []);
  return (
    <div className="bg-gradient-to-b from-pink-50/30 to-white min-h-screen">
      <div className="bg-gradient-to-r from-pink-600 to-rose-600 text-white py-16">
        <div className="max-w-7xl mx-auto px-[5%]">
          <h1 className="text-4xl md:text-5xl font-bold mb-4 text-center">
            Privacy Policy
          </h1>
          <p className="text-xl text-center text-pink-50 max-w-2xl mx-auto mb-8">
           Read how we are concern about your privacy
          </p>
        </div>
      </div>
      <div className="max-w-7xl mx-auto px-[5%] py-12">
        <div
          className=""
          dangerouslySetInnerHTML={{
            __html: privacyPolicyData?.description ?? "",
          }}
        />
      </div>
    </div>
  );
}
