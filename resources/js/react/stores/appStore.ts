import { create } from "zustand";
import { persist, createJSONStorage } from "zustand/middleware";

interface AppState {
  // UI Preferences
  sidebarOpen: boolean;
  theme: "light" | "dark";

  // Global loading states
  globalLoading: boolean;

  // Toast/Notification state
  notifications: Array<{
    id: string;
    type: "success" | "error" | "info" | "warning";
    message: string;
    duration?: number;
  }>;

  // Page metadata
  pageTitle: string;
  pageDescription: string;

  // Actions
  setSidebarOpen: (open: boolean) => void;
  setTheme: (theme: "light" | "dark") => void;
  setGlobalLoading: (loading: boolean) => void;

  // Notification actions
  addNotification: (
    notification: Omit<AppState["notifications"][0], "id">
  ) => void;
  removeNotification: (id: string) => void;
  clearNotifications: () => void;

  // Page metadata actions
  setPageMeta: (title: string, description?: string) => void;

  // Utility actions
  reset: () => void;
}

export const useAppStore = create<AppState>()(
  persist(
    (set, get) => ({
      // Initial state
      sidebarOpen: false,
      theme: "light",
      globalLoading: false,
      notifications: [],
      pageTitle: "Azhar Material",
      pageDescription: "Construction Materials Supplier",

      // UI Actions
      setSidebarOpen: (sidebarOpen) => set({ sidebarOpen }),
      setTheme: (theme) => set({ theme }),
      setGlobalLoading: (globalLoading) => set({ globalLoading }),

      // Notification actions
      addNotification: (notification) => {
        const id =
          Date.now().toString() + Math.random().toString(36).substr(2, 9);
        const newNotification = { ...notification, id };

        set((state) => ({
          notifications: [...state.notifications, newNotification],
        }));

        // Auto remove after duration (default 5 seconds)
        const duration = notification.duration || 5000;
        setTimeout(() => {
          get().removeNotification(id);
        }, duration);
      },

      removeNotification: (id) => {
        set((state) => ({
          notifications: state.notifications.filter((n) => n.id !== id),
        }));
      },

      clearNotifications: () => set({ notifications: [] }),

      // Page metadata actions
      setPageMeta: (pageTitle, pageDescription) => {
        set({
          pageTitle,
          pageDescription: pageDescription || "Construction Materials Supplier",
        });

        // Update document title
        if (typeof document !== "undefined") {
          document.title = pageTitle;
        }
      },

      // Utility actions
      reset: () => {
        set({
          sidebarOpen: false,
          theme: "light",
          globalLoading: false,
          notifications: [],
          pageTitle: "Azhar Material",
          pageDescription: "Construction Materials Supplier",
        });
      },
    }),
    {
      name: "app-store",
      storage: createJSONStorage(() => localStorage),
      partialize: (state) => ({
        // Only persist UI preferences
        sidebarOpen: state.sidebarOpen,
        theme: state.theme,
      }),
    }
  )
);
