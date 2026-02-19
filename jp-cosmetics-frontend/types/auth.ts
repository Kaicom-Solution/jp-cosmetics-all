export type User = {
  id: number;
  name?: string;
  email: string;
  phone?: string;
};

export type LoginPayload = {
  email: string;
  password: string;
};

export type LoginApiResponse = {
  success: boolean;
  message: string;
  data: {
    token: string;
    token_type: "bearer";
    customer: User;
  };
};

export type AuthState = {
  user: User | null;
  loading: boolean;
  loginTime: number | null;

  hydrate: () => Promise<void>;
  login: (email: string, password: string) => Promise<void>;
  logout: () => void;
  setUser: (user: User | null) => void;

  forceLogout: () => void;
  startExpiryTimer: () => void;
};
