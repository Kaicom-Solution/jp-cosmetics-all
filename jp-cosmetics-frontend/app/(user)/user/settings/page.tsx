"use client";

import { Suspense, useState } from "react";
import SettingItem from "@/components/user/SettingItem";
import PersonalInfoModal from "@/components/user/PersonalInfoModal";
import PasswordModal from "@/components/user/PasswordModal";
import UserLoader from "@/components/user/UserLoader";

export default function Settings() {
  const [showPersonalModal, setShowPersonalModal] = useState(false);
  const [showPasswordModal, setShowPasswordModal] = useState(false);

  return (
    <Suspense fallback={<UserLoader />}>
      <div className="py-8 px-4 sm:px-6 lg:px-8">
        <div className="">
          <div className="mb-8">
            <h1 className="text-3xl font-bold text-gray-900">Settings</h1>
            <p className="text-gray-600 mt-2">
              Manage your account preferences and security
            </p>
          </div>

          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-2xl mx-auto">
            <h2 className="text-2xl font-bold text-gray-900 mb-6">
              Account Settings
            </h2>

            <div className="space-y-6">
              <SettingItem
                title="Personal Information"
                description="Update your name, email, and phone number"
                onClick={() => setShowPersonalModal(true)}
              />

              <SettingItem
                title="Password & Security"
                description="Change your password and security settings"
                onClick={() => setShowPasswordModal(true)}
              />
            </div>
          </div>
        </div>

        {/* Modals */}
        <PersonalInfoModal
          isOpen={showPersonalModal}
          onClose={() => setShowPersonalModal(false)}
        />

        <PasswordModal
          isOpen={showPasswordModal}
          onClose={() => setShowPasswordModal(false)}
        />
      </div>
    </Suspense>
  );
}
