import { create } from "zustand";

interface PromotionPopupState {
  showPromotion: boolean;
  lastShown: number | null;
  checkCanShow: () => void;
  closePromotion: () => void;
}

const ONE_DAY = 24 * 60 * 60 * 1000;

export const usePromotionPopupStore = create<PromotionPopupState>((set) => ({
  showPromotion: false,
  lastShown: null,

  checkCanShow: () => {
    const lastShown = localStorage.getItem("promotion-popup-last-shown");

    if (!lastShown) {
      set({ showPromotion: true });
      return;
    }

    const lastTime = Number(lastShown);
    const now = Date.now();

    if (now - lastTime > ONE_DAY) {
      set({ showPromotion: true });
    }
  },

  closePromotion: () => {
    localStorage.setItem(
      "promotion-popup-last-shown",
      Date.now().toString()
    );

    set({ showPromotion: false });
  },
}));