<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Customer_Purchase_Order;
use App\Models\Payment_Method;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Store customer purchase order and payment method
     * Called from checkout modal in POS system
     */
    public function store(CheckoutRequest $request)
    {
        try {
            // Validate the incoming data using CheckoutRequest
            $validated = $request->validated();

            // Log the validated data for debugging
            Log::info('Checkout data received:', $validated);

            $purchaseOrders = [];
            $paymentMethods = [];

            // Process each item in the order
            foreach ($validated['items'] as $item) {
                // Get product by serial number to verify it exists
                $product = Product::where('serial_number', $item['serial_number'])->first();

                if (!$product) {
                    Log::warning('Product not found with serial number: ' . $item['serial_number']);
                    return response()->json([
                        'success' => false,
                        'message' => 'Product with serial number ' . $item['serial_number'] . ' not found'
                    ], 404);
                }

                Log::info('Creating purchase order for product:', [
                    'product_id' => $product->id,
                    'customer_id' => $validated['customer_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price']
                ]);

                // Create customer purchase order
                $purchaseOrder = Customer_Purchase_Order::create([
                    'product_id' => $product->id,
                    'customer_id' => $validated['customer_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'order_date' => now()->toDateString(),
                    'status' => 'Success',
                ]);

                $purchaseOrders[] = $purchaseOrder;
            }

            // Create payment method record for the first purchase order (or you can create one per order)
            // Using the first purchase order as the reference
            if (!empty($purchaseOrders)) {
                $paymentMethod = Payment_Method::create([
                    'customer_purchase_order_id' => $purchaseOrders[0]->id,
                    'method_name' => $validated['payment_method'],
                    'payment_date' => now()->toDateString(),
                    'amount' => $validated['amount'],
                ]);

                $paymentMethods[] = $paymentMethod;
            }

            return response()->json([
                'success' => true,
                'message' => 'Order and payment processed successfully',
                'purchase_orders' => $purchaseOrders,
                'payment_method' => $paymentMethods[0] ?? null,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing checkout: ' . $e->getMessage()
            ], 500);
        }
    }
}
