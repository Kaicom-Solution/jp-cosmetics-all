<?php

namespace App\Http\Controllers\Api;

use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\ProductResource;
use App\Mail\CustomerPasswordResetMail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\OrderDetailsResource;

class CustomerAuthController extends Controller
{
    public function login(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(
                    'Validation failed',
                    $validator->getMessageBag(),
                    422,
                );
            }
        
            // Attempt login using JWT guard
            $credentials = $request->only('email', 'password');

            if (!$token = Auth::guard('customer')->attempt($credentials)) {
                return $this->responseWithError(
                    'Invalid email or password',
                    [],
                    401
                );
            }

            $customer = Auth::guard('customer')->user();

            if ($customer->status !== 'active') {
                return $this->responseWithError(
                    'Your account is not active. Please contact support.',
                    [],
                    403,
                );
            }

            // Success response
            return $this->responseWithSuccess([
                'token'      => $token,
                'token_type' => 'bearer',
                'customer'   => $customer,
            ], 'Login successful', 200);

        } catch (Exception $e) {

            return $this->responseWithError(
                'Something went wrong',
                [$e->getMessage()],
                500
            );
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:50',
                'email'    => 'required_without:phone|email|unique:customers,email',
                'phone'    => 'required_without:email|string|max:15|unique:customers,phone',
                'password' => 'required|string|min:6',
                'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ], [
                'email.required_without' => 'Please provide email or phone.',
                'phone.required_without' => 'Please provide email or phone.',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(
                    'Validation failed',
                    $validator->getMessageBag(),
                    422
                );
            }

            // Upload image if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('customers', 'public');
            }

            // Generate OTP (optional)
            $otp = rand(100000, 999999);

            // Create customer
            $customer = Customer::create([
                'name'            => $request->name,
                'email'           => $request->email,
                'phone'           => $request->phone,
                'password'        => Hash::make($request->password),
                'image'           => $imagePath,
                'otp'             => $otp,
                'otp_expires_at'  => now()->addMinutes(3),
                'status'          => 'active',
            ]);

            return $this->responseWithSuccess(
                $customer,
                'Registration successful.',
                200
            );

        } catch (\Exception $e) {
            return $this->responseWithError(
                'Something went wrong.',
                [$e->getMessage()],
                500
            );
        }
    }


    public function forgotPassword(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:customers,email'
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(
                    'Validation failed',
                    $validator->getMessageBag(),
                    422
                );
            }

            $customer = Customer::where('email', $request->email)->first();

            if (!$customer) {
                return $this->responseWithError(
                    'User not found.',
                    [],
                    404
                );
            }

            // Generate OTP
            $otp = rand(100000, 999999);

            // Store OTP + Expiry
            $customer->otp = $otp;
            $customer->otp_expires_at = now()->addMinutes(3);
            $customer->save();

            // Send Email
            Mail::to($customer->email)->send(new CustomerPasswordResetMail($otp));

            return $this->responseWithSuccess(
                $customer->otp,
                'OTP has been sent to your email.',
                200,
            );

        } catch (\Exception $e) {
            return $this->responseWithError(
                'Something went wrong.',
                [$e->getMessage()],
                500,
            );
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
            'otp'   => 'required|numeric',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if ($customer->otp != $request->otp) { // Use != instead of !==
            return $this->responseWithError('Invalid OTP', [], 422);
        }

        if (now()->gt($customer->otp_expires_at)) {
            return $this->responseWithError('OTP expired', [], 422);
        }

        return $this->responseWithSuccess(null, 'OTP verified', 200);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                       => 'required|email|exists:customers,email',
            'otp'                         => 'required|numeric',
            'new_password'                => 'required|min:6|confirmed',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if ($customer->otp != $request->otp || now()->gt($customer->otp_expires_at)) {
            return $this->responseWithError('Invalid or expired OTP', [], 422);
        }

        $customer->password = Hash::make($request->new_password);
        $customer->otp = null;
        $customer->otp_expires_at = null;
        $customer->save();

        return $this->responseWithSuccess(null, 'Password reset successfully', 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError('Validation failed', $validator->getMessageBag(), 422);
        }

        $customer = auth('customer')->user();

        if (!Hash::check($request->current_password, $customer->password)) {
            return $this->responseWithError('Current password is incorrect', [], 422);
        }

        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return $this->responseWithSuccess(null, 'Password changed successfully', 200);
    }

    
    public function logout(Request $request)
    {
        try {
            Auth::guard('customer')->logout();

            return $this->responseWithSuccess(null, 'Logout successful', 200);

        } catch (Exception $e) {
            return $this->responseWithError(
                'Something went wrong',
                [$e->getMessage()],
                500
            );
        }
    }




    /////////////////////////////////////////////////////////


    public function dashboard(Request $request)
    {
        try {
            $customer = auth('customer')->user();

            // Total Orders
            $totalOrders = $customer->orders()->count();

            // Wishlist Items
            $wishlistCount = $customer->wishlists()->count();

            // Reward Points
            $rewardPoints = (int) ($customer->reward_points ?? 0);

            // Total Spent
            $totalSpent = $customer->orders()
                ->where('status', 'completed')
                ->where('payment_status', 'success')
                ->sum('payable_total');

            $data = [
                'total_orders'   => $totalOrders,
                'wishlist_items' => $wishlistCount,
                'reward_points'  => $rewardPoints,
                'total_spent'    => number_format($totalSpent, 2),
            ];

            return $this->responseWithSuccess(
                $data,
                'Dashboard fetched successfully',
                200
            );

        } catch (Throwable $e) {
            return $this->responseWithError(
                'Failed to load dashboard',
                [$e->getMessage()],
                500
            );
        }
    }



    public function profile(Request $request)
    {
        $customer = auth('customer')->user(); // Get logged-in customer
        return $this->responseWithSuccess($customer, 'Profile fetched successfully', 200);
    }

    public function updateProfile(Request $request)
    {
        $customer = auth('customer')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:50',
            'phone'      => 'nullable|string|max:15',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError('Validation failed', $validator->getMessageBag(), 422);
        }

        // Update fields
        $customer->name = $request->name ?? $customer->name;
        $customer->phone      = $request->phone ?? $customer->phone;

        // Handle image upload
        if ($request->hasFile('image')) {
            $customer->image = $request->file('image')->store('customers', 'public');
        }

        $customer->save();

        return $this->responseWithSuccess($customer, 'Profile updated successfully', 200);
    }


    ///////////////////////////Address Management///////////////////////////

    public function addresses(Request $request)
    {
        $customer = auth('customer')->user();
        $addresses = $customer->addresses; // Assuming Customer has 'addresses' relationship

        return $this->responseWithSuccess($addresses, 'Addresses fetched successfully', 200);
    }

    public function addAddress(Request $request)
    {
        $customer = auth('customer')->user();

        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'area'         => 'required|string|max:100',
            'address'   => 'required',
            'status'       => 'required|integer|in:0,1',
            'is_default'   => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError('Validation failed', $validator->getMessageBag(), 422);
        }

        if ($request->is_default == 1) {
            $customer->addresses()->update(['is_default' => 0]);
        }

        $address = $customer->addresses()->create([
            'title'       => $request->title,
            'city'        => $request->city,
            'area'       => $request->area,
            'address' => $request->address,
            'status'     => $request->status,
            'is_default' => $request->is_default,
        ]);

        return $this->responseWithSuccess($address, 'Address added successfully', 200);
    }

    public function getAddress(Request $request, $id)
    {
        $customer = auth('customer')->user();
        $address = $customer->addresses()->find($id);

        if (!$address) {
            return $this->responseWithError('Address not found', [], 404);
        }

        return $this->responseWithSuccess($address, 'Address fetched successfully', 200);
    }

    public function updateAddress(Request $request, $id)
    {
        $customer = auth('customer')->user();
        $address = $customer->addresses()->find($id);

        if (!$address) {
            return $this->responseWithError('Address not found', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'         => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'area'         => 'nullable|string|max:100',
            'address'   => 'nullable',
            'status'       => 'nullable|integer|in:0,1',
            'is_default'   => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError('Validation failed', $validator->getMessageBag(), 422);
        }

        if ($request->is_default == 1) {
            $customer->addresses()->update(['is_default' => 0]);
        }

        $address->update($request->only(['title', 'city', 'area', 'address', 'status', 'is_default']));

        return $this->responseWithSuccess($address, 'Address updated successfully', 200);
    }

    public function deleteAddress(Request $request, $id)
    {
        $customer = auth('customer')->user();
        $address = $customer->addresses()->find($id);

        if (!$address) {
            return $this->responseWithError('Address not found', [], 404);
        }

        $address->delete();

        return $this->responseWithSuccess([], 'Address deleted successfully', 200);
    }


    //////////////////////////Order Management///////////////////////////

    public function ordersList(Request $request)
    {
        $customer = auth('customer')->user();
        $orders = $customer->orders()->orderByDesc('id')->get();

        return $this->responseWithSuccess($orders, 'Orders fetched successfully', 200);
    }

    public function orderDetails(Request $request, $order_id)
    {
        $customer = auth('customer')->user();

        $order = $customer->orders()
            ->with([
                'details.productAttribute.attribute_images',
                'details.product:id,name,slug,primary_image',
                'activities',
                'address',
            ])
            ->find($order_id);

        if (!$order) {
            return $this->responseWithError('Order not found', [], 404);
        }

        return $this->responseWithSuccess(
            new OrderDetailsResource($order),
            'Order details fetched successfully',
            200
        );
    }



    /////////////////////Wishlist Management///////////////////////////
    public function index()
    {
        try {
            $customer = auth('customer')->user();

            $products = $customer->wishlists()
 
                ->with([
                    'product.category:id,name,slug',
                    'product.brand:id,name,slug',
                    'product.attributes'
                ])
                ->orderBy('added_at')
                ->get()
                ->pluck('product');


            return response()->json([
                'success' => true,
                'message' => 'Wishlist fetched successfully',
                'data'    => ProductResource::collection($products),
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch wishlist',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            $customer = auth('customer')->user();

            // Find product with attributes
            $product = Product::with('attributes:id,product_id,unit_price,is_default')
                        ->findOrFail($request->product_id);

            // Check if already in wishlist
            $exists = Wishlist::where('customer_id', $customer->id)
                        ->where('product_id', $product->id)
                        ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product already in wishlist',
                ], 409);
            }

            // Add to wishlist
            Wishlist::create([
                'customer_id' => $customer->id,
                'product_id'  => $product->id,
                'added_at'    => now(),
            ]);

            // --- Internal dependency logic ---
            $defaultAttr = null;
            if ($product->attributes->count() > 0) {
                $defaultAttr = $product->attributes->firstWhere('is_default', 1);

                if (!$defaultAttr && $product->attributes->count() === 1) {
                    $defaultAttr = $product->attributes->first();
                }

                if (!$defaultAttr && $product->attributes->count() > 1) {
                    $defaultAttr = $product->attributes->first();
                }

                // Now you have internally:
                // $product->id, $product->name, $product->primary_image, $defaultAttr->unit_price
                // You can use these internally if needed, but you don't return them.
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist',
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to wishlist',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($product_id)
    {
        try {
            $customer = auth('customer')->user();

            // Find the wishlist item for this customer and product
            $wishlist = Wishlist::where('customer_id', $customer->id)
                                ->where('product_id', $product_id)
                                ->first();

            if (!$wishlist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wishlist item not found',
                ], 404);
            }

            // Delete the item
            $wishlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist',
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove wishlist item',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


}