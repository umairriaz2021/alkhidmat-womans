<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
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
         echo "<pre>"; print_r($request->all());die;
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
        
        $orderId = $request->input('order_id') ;

        if (!$orderId) {
            return redirect()->route('meezan.checkout')->with('error', 'Invalid callback response from gateway.');
        }
        $order_ref = Transaction::where('order_number',$orderId)->first();
         
        try {
            // Status double-check karne ke liye status check API call karein
            $response = Http::withoutVerifying()
                ->get("{$this->baseUrl}/epg/rest/getOrderStatusExtended.do", [
                    'userName' => $this->username,
                    'password' => $this->password,
                    'orderId'  => $order_ref->meezan_order_ref,
                    'orderNumber' => $orderId,
                    'language' => 'en',
                ]);
            
            if ($response->successful()) {
                $statusData = $response->json();
              
              
                if (isset($statusData['orderStatus'])) {
                    $statusCode = (int) $statusData['orderStatus'];
                    $status = match ($statusCode) {
                    2       => 'complete',
                    1       => 'processing',
                    0       => 'pending',
                    3       => 'reversed',
                    4       => 'refunded',
                    6       => 'declined',
                    default => 'failed',
                };

                $order_ref->update([
                    'status'      => $status,
                    'link_status' => ($status === 'complete') ? 'inactive' : $order_ref->link_status,
                ]);

                if ($statusCode === 2) {
                    return redirect()->route('payment.success')->with('success', 'Payment processed successfully!');
                }

                    $errorMessage = $statusData['actionCodeDescription'] 
                             ?? $statusData['errorMessage'] 
                             ?? 'Payment was not successful.';

                    return redirect()->route('meezan.checkout')->with('error', 'Transaction status: ' . ucfirst($status) . ' - ' . $errorMessage);
                }

                return redirect()->route('meezan.checkout')->with('error', 'Order status not found in bank response.');
            }

            return redirect()->route('meezan.checkout')->with('error', 'Could not verify payment status with bank.');

        } catch (\Exception $e) {
            Log::error('Meezan Callback Exception: ' . $e->getMessage());
            return redirect()->route('meezan.checkout')->with('error', 'Error verifying payment status.');
        }
    }

    public function transactionsDetails()
    {
         $transactions = Transaction::paginate()->toArray();
         return view('admin.dashboard.transactions.index',compact('transactions'));
    }
}
