<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MeezanPaymentController extends Controller
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected string $currency;

    public function __construct()
    {
        
        $this->baseUrl  = rtrim(config('services.meezan.base_url'), '/');
        $this->username = config('services.meezan.username');
        $this->password = config('services.meezan.password');
        $this->currency = config('services.meezan.currency');
    }
    public function showCheckout()
    {  
        
        return view('checkout');
    }

    public function processPayment(Request $request)
    {
        // Example order details (Inhein apne order/cart system se replace karein)
        $orderNumber = 'ORD-' . strtoupper(uniqid());
        $amountInPkr = 1000.00; // Rs. 1,000

        // Amount ko minor units (paisa) mein convert karein (1000 * 100 = 100000)
        $amountInMinorUnits = (int) round($amountInPkr * 100);

        $payload = [
            'userName'    => $this->username,
            'password'    => $this->password,
            'orderNumber' => $orderNumber,
            'amount'      => $amountInMinorUnits,
            'currency'    => $this->currency,
            'returnUrl'   => route('meezan.callback'),
            'description' => 'Order Payment ' . $orderNumber,
        ];

        try {
            // Note: SSL verification disable 'withoutVerifying()' sirf testing/sandbox environment ke liye hai
            $response = Http::withoutVerifying()
                ->asForm()
                ->post("{$this->baseUrl}/register.do", $payload);

            if ($response->successful()) {
                $data = $response->json();

                // Check errorCode 0 for success
                if (isset($data['errorCode']) && (int)$data['errorCode'] === 0) {
                    // Local DB mein order state save/update karein
                    // session(['meezan_order_id' => $data['orderId']]);

                    // User ko bank ke secure hosted payment page par redirect karein
                    return redirect()->away($data['formUrl']);
                }

                Log::error('Meezan Registration Error', $data);
                return back()->with('error', $data['errorMessage'] ?? 'Gateway Error: Transaction failed to register.');
            }

            Log::error('Meezan HTTP Failed', ['status' => $response->status(), 'body' => $response->body()]);
            return back()->with('error', 'Unable to connect with Meezan Payment Gateway.');

        } catch (\Exception $e) {
            Log::error('Meezan Exception: ' . $e->getMessage());
            return back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * 2. Handle Payment Callback & Verify Transaction
     */
    public function handleCallback(Request $request)
    {
        // Bank user ko returnUrl par mdOrder ya orderId parameter bhejta hai
        $orderId = $request->input('orderId') ?? $request->input('mdOrder');

        if (!$orderId) {
            return redirect()->route('meezan.checkout')->with('error', 'Invalid callback response from gateway.');
        }

        try {
            // Status double-check karne ke liye status check API call karein
            $response = Http::withoutVerifying()
                ->asForm()
                ->post("{$this->baseUrl}/getOrderStatus.do", [
                    'userName' => $this->username,
                    'password' => $this->password,
                    'orderId'  => $orderId,
                    'language' => 'en',
                ]);

            if ($response->successful()) {
                $statusData = $response->json();

                // Order Status Codes:
                // 2 = Deposited / Payment Completed Successfully
                // 1 = Approved / Held
                // 6 = Declined
                if (isset($statusData['orderStatus']) && (int)$statusData['orderStatus'] === 2) {
                    
                    // Database Status Update Code Here:
                    // $order = Order::where('gateway_order_id', $orderId)->first();
                    // $order->update(['status' => 'paid']);

                    return redirect()->route('payment.success')->with('success', 'Payment processed successfully!');
                }

                $errorMessage = $statusData['errorMessage'] ?? 'Payment was declined or cancelled.';
                return redirect()->route('checkout')->with('error', 'Transaction Failed: ' . $errorMessage);
            }

            return redirect()->route('checkout')->with('error', 'Could not verify payment status with bank.');

        } catch (\Exception $e) {
            Log::error('Meezan Callback Exception: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'Error verifying payment status.');
        }
    }
}
