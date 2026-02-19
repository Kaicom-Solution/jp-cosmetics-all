import { create } from "zustand";
import { persist, createJSONStorage } from "zustand/middleware";

import { authService } from "@/services/auth.service";
import { setAuthToken, clearAuthToken } from "@/lib/authCookies";

import type { AuthState } from "@/types/auth";

const EXPIRY_TIME = 48 * 60 * 60 * 1000;

let logoutTimer: ReturnType<typeof setTimeout> | null = null;

export const useAuthStore = create<AuthState>()(
  persist(
    (set, get) => ({
      user: null,
      loading: true,

      loginTime: null,

      startExpiryTimer: () => {
        const loginTime = get().loginTime;

        if (!loginTime) return;

        const passedTime = Date.now() - loginTime;
        const remainingTime = EXPIRY_TIME - passedTime;

        if (logoutTimer) clearTimeout(logoutTimer);

        if (remainingTime <= 0) {
          console.log("Session expired. Logging out now...");
          get().forceLogout();
          return;
        }

        console.log(
          `Session will expire in ${Math.floor(remainingTime / 1000)} seconds`,
        );

        logoutTimer = setTimeout(() => {
          console.log("Auto session expired. Logging out...");

          get().forceLogout();
        }, remainingTime);
      },

      forceLogout: () => {
        clearAuthToken();

        set({
          user: null,
          loginTime: null,
          loading: false,
        });
      },

      hydrate: async () => {
        try {
          const loginTime = get().loginTime;

          if (loginTime) {
            const isExpired = Date.now() - loginTime > EXPIRY_TIME;

            if (isExpired) {
              console.log("Session expired on reload.");
              get().forceLogout();
              return;
            }
          }

          const user = await authService.me();

          set({
            user,
            loading: false,
          });

          get().startExpiryTimer();
        } catch {
          get().forceLogout();
        }
      },

      login: async (email, password) => {
        const res = await authService.login({ email, password });

        setAuthToken(res.token);

        set({
          user: res.user,
          loginTime: Date.now(),
        });

        get().startExpiryTimer();
      },

      logout: async () => {
        try {
          await authService.logout();
        } finally {
          get().forceLogout();
          window.location.href = "/";
        }
      },

      setUser: (user) => set({ user }),
    }),

    {
      name: "auth-store",

      storage: createJSONStorage(() => localStorage),

      partialize: (state) => ({
        user: state.user,
        loginTime: state.loginTime,
      }),
    },
  ),
);
