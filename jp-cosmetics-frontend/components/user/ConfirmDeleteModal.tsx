import { AlertTriangle } from "lucide-react";
export function ConfirmDeleteModal({
  isOpen,
  onClose,
  onConfirm,
  addressTitle,
  isDafault,
}: {
  isOpen: boolean;
  onClose: () => void;
  onConfirm: () => void;
  addressTitle: string;
  isDafault: number;
}) {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-opacity backdrop-blur-sm flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-2xl shadow-2xl max-w-sm w-full">
        {/* Header */}
        <div className="p-6 text-center">
          <div className="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <AlertTriangle className="w-8 h-8 text-red-600" />
          </div>
          <h2 className="text-xl font-bold text-gray-900 mb-2">
            Delete Address?
          </h2>
          {isDafault ? (
            <p className="text-gray-600">
              You can not delete your default address{" "}
              <span className="font-semibold text-gray-900">
                "{addressTitle}" .
              </span>{" "} If you want to delete set another default address.
            </p>
          ) : (
            <p className="text-gray-600">
              Are you sure you want to delete{" "}
              <span className="font-semibold text-gray-900">
                "{addressTitle}"
              </span>{" "}
              address? This action cannot be undone.
            </p>
          )}
        </div>

        {/* Action Buttons */}
        <div className="flex gap-3 p-6 pt-0">
          <button
            onClick={onClose}
            className="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors cursor-pointer"
          >
            Cancel
          </button>
          <button
            disabled={isDafault == 1}
            onClick={() => {
              onConfirm();
              onClose();
            }}
            className={`flex-1 px-4 py-2.5 rounded-xl font-semibold  transition-colors ${isDafault == 1 ? "bg-white text-black border border-gray-300 cursor-not-allowed" : "bg-red-600 text-white hover:bg-red-700 cursor-pointer"}`}
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  );
}
