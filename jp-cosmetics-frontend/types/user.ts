export type ApiResponse<T> = {
  success: boolean;
  message: string;
  data: T;
};


// user type

export type Profile = {
  name: string;
  email: string;
  phone: string;
};

export type UpdateProfilePayload = {
  name: string;
  email: string;
  phone: string;
};

export type UpdatePasswordPayload = {
  current_password: string;
  new_password: string;
  new_password_confirmation: string;
};

// order type

export type OrderProduct = {
  id: number;
  name: string;
  primary_image: string;
};

export type ItemAttribute = {
  id: number;
  product_id: number;
  attribute_name: string;
  attribute_value: string;
};

export type OrderItem = {
  id:number
  product: OrderProduct;
  product_attribute: ItemAttribute;
  quantity: number;
  unit_price: string;
  sub_total: string;
  discount_amount: string;
  payable: string;
  coupon: number | null;
};

export type OrderReceiver = {
  name: string;
  email: string;
  phone: string;
};

export type OrderShipping = {
  city: string;
  area: string;
  location: string;
};

export type OrderAddress = {
  id: number;
  title: string;
  city: string;
  area: string;
  address: string;
  status: 0 | 1;
  is_default: 0 | 1;
};

export type Order = {
  id: number;
  customer_id: number;
  order_number: string;
  sub_total_amount: string;
  delivery_charge: string;
  discount_amount: string;
  payable_total: string;
  payment_status:
    | "pending"
    | "processing"
    | "cancel"
    | "failed"
    | "success"
    | "refunded";
  transaction_id: string | null;
  payment_method: string;
  payment_channel: string | null;
  additionals: string | null;
  status:
    | "pending"
    | "confirm"
    | "dispatched"
    | "delivered"
    | "cancelled"
    | "returned"
    | "success";
  receiver_name: string;
  receiver_email: string;
  receiver_phone: string;
  shipping_city: string;
  shipping_area: string;
  shipping_location: string;
  customer_address_id: number;
  order_note: string | null;
  remarks: string | null;
  created_at: string;
  updated_at: string;
  details: OrderItem[];
};

export type OrderDetail = {
  order_number: string;
  sub_total_amount: string;
  delivery_charge: string;
  discount_amount: string;
  payable_total: string;

  payment_status:
    | "pending"
    | "processing"
    | "cancel"
    | "failed"
    | "success"
    | "refunded";
  payment_method: string;
  transaction_id: string | null;

  order_status:
    | "pending"
    | "confirm"
    | "dispatched"
    | "delivered"
    | "cancelled"
    | "returned"
    | "success";
  current_status: string;

  last_update_at: string;
  created_at: string;

  receiver: OrderReceiver;
  shipping: OrderShipping;
  address: OrderAddress;

  order_note: string | null;
  remarks: string | null;
};

export type OrderListResponse = {
  data: Order[];
};

export type OrderDetailResponse = {
  id:number,
  order: OrderDetail;
  items: OrderItem[];
  timeline: any[];
};

// address type

export type Address = {
  id: number;
  title: string;
  name: string;
  city: string;
  area: string;
  address: string;
  phone: string;
  status: 0 | 1;
  is_default: 0 | 1;
};

export type AddressPayload = {
  title: string;
  name: string;
  city: string;
  area: string;
  address: string;
  phone: string;
  is_default: 0 | 1;
  status: number;
};


export type DashboardResponse = {
  total_orders: number;
  wishlist_items: number;
  reward_points: number;
  total_spent: string;
};

