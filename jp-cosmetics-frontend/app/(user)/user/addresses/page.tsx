"use client";

import { useState, useEffect } from "react";

import { useAuthStore } from "@/store/authStore";
import { showToast } from "@/utils/toast";

import AddressCard from "@/components/user/AddressCard";
import { AddressModal } from "@/components/user/AddressModal";
import { ConfirmDeleteModal } from "@/components/user/ConfirmDeleteModal";

import { addressService } from "@/services/user.service";
import type { Address, AddressPayload } from "@/types/user";

function AddressesSection() {
  const [showAddModal, setShowAddModal] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [selectedAddress, setSelectedAddress] = useState<Address | null>(null);
  const [address, setAddress] = useState<Address[]>([]);
  const [loading, setLoading] = useState(true);

  const user = useAuthStore().user;

  const handleSaveAddress = async (data: AddressPayload) => {
    // console.log("Saved address:", data);

    try {
      if (showAddModal) {
        const res = await addressService.create(data);
      } else {
        if (!selectedAddress) return;
        const res = await addressService.update(selectedAddress.id, data);
      }
      fetchAddress();
      showToast.success(
        `Address ${showAddModal ? "add " : "update"} successfully`,
      );
    } catch (error) {
      // console.error(
      //   `Failed to ${showAddModal ? "add " : "update"} address`,
      //   error
      // );
      showToast.error(`Failed to ${showAddModal ? "add " : "update"} address`);
    } finally {
      setLoading(false);
    }
  };

  const handleDeleteAddress = () => {
    console.log("Deleted address");
  };

  const fetchAddress = async () => {
    try {
      const data = await addressService.list();
      setAddress(data);
    } catch (error) {
      // console.error("Failed to fetch orders", error);
      showToast.error("Failed to fetch address");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchAddress();
  }, []);

  if (loading) return <div>Loading address...</div>;

  // if (!address.length)
  //   return (
  //
  //   );

  return (
    <>
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-2xl font-bold text-gray-900">Saved Addresses</h2>
          <button
            onClick={() => setShowAddModal(true)}
            className="px-4 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all cursor-pointer"
          >
            Add New
          </button>
        </div>
        {!address.length ? (
          <div className="size-full flex justify-center items-center text-gray-500 text-lg">
            No address found
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {address.map((item) => (
              <AddressCard
                key={item.id}
                title={item.title}
                name={user?.name}
                address={item.address}
                area={item.area}
                city={item.city}
                phone={item.phone || user?.phone}
                isDefault={item.is_default}
                onEdit={() => {
                  setSelectedAddress(item);
                  setShowEditModal(true);
                }}
                onRemove={() => {setShowDeleteModal(true) ; setSelectedAddress(item)}}
              />
            ))}
          </div>
        )}
      </div>
      {/* Modals */}
      <AddressModal
        isOpen={showAddModal}
        onClose={() => setShowAddModal(false)}
        onSave={handleSaveAddress}
      />

      <AddressModal
        isOpen={showEditModal}
        onClose={() => {
          setShowEditModal(false);
          setSelectedAddress(null);
        }}
        onSave={handleSaveAddress}
        address={selectedAddress}
      />

      <ConfirmDeleteModal
        isOpen={showDeleteModal}
        onClose={() => setShowDeleteModal(false)}
        onConfirm={handleDeleteAddress}
        addressTitle={selectedAddress?.title || ''}
        isDafault = {selectedAddress?.is_default || 0}
      />
    </>
  );
}

export default AddressesSection;
