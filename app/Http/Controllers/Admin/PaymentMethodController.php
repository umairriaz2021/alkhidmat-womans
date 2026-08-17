<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Status;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::with(['status'])->latest()->get();
        return view('admin.dashboard.payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = Status::all();
        
        return view('admin.dashboard.payment_methods.create_edit', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
        'name' => 'required|string|max:255',
        'status_id' => 'required|exists:status,id',
    ];
        $messages = [
        'name.required'      => 'Method name is required.',
        'name.max'           => 'Method name should not be more than 255 characters.',
        'status_id.required'      => 'Status is required',
        'status_id.exists'   => 'Invalid selected status.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }


    PaymentMethod::create([
        'name' => $request->name,
        'image_id' => $request->image_id,
        'status_id' => $request->status_id,
        'general' => $request->general ? $request->general : null,
    ]);

    return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $paymentMethod = PaymentMethod::with('image')->findOrFail($id);
       $statuses = Status::all();
       return view('admin.dashboard.payment_methods.create_edit', compact('paymentMethod', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $rules = [
        'name'      => 'required|string|max:255',
        'status_id' => 'required|exists:status,id',
        'image_id'  => 'nullable|exists:media,id',
        'general'   => 'nullable|array',
        ];
        $messages = [
            'name.required'      => 'Method name is required.',
            'status_id.required' => 'Please select a status.',
            'status_id.exists'   => 'The selected status is invalid.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
        }
        try {
        // 4. Update the Record
        $paymentMethod->update([
            'name'      => $request->name,
            'image_id'  => $request->image_id,
            'status_id' => $request->status_id,
            'general'   => $request->general, // Laravel automatic array-to-json conversion handles this
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment method updated successfully!'
        ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createSession(Request $request)
{
    $request->validate([
        'amount'            => 'required|numeric|min:1',
        'first_name'        => 'required|string',
        'email'             => 'required|email',
        'payment_method_id' => 'required',
    ]);

    // 1. Config se Credentials fetch karein
    $baseUrl  = config('services.meezan.base_url', 'https://test-securepayment.meezanbank.com:9716');
    $userName = config('services.meezan.username');
    $password = config('services.meezan.password');
    $currency = config('services.meezan.currency', '586');

    if (empty($userName) || empty($password)) {
        return response()->json(['error' => 'Meezan Bank credentials not configured properly'], 500);
    }

    // 2. Unique Order Number aur Amount (Meezan expects amount in minor units/paisa i.e. x100)
    $orderNumber = 'ORD-' . strtoupper(uniqid());
    $amountInPaisa = (int)($request->amount * 100);
    $returnUrl = url('/payment/meezan/callback'); // Apne callback route ka URL dein
    //$returnUrl = "https://www.google.com"; // Apne callback route ka URL dein

    try {
        // 3. Meezan Bank register.do API Call
        $apiUrl = rtrim($baseUrl, '/') . '/payment/rest/register.do';
       
        $response = Http::withoutVerifying()
            ->timeout(30)
            ->withQueryParameters([
                'userName'    => $userName,
                'password'    => $password,
                'amount'      => $amountInPaisa,
                'currency'    => $currency,
                'orderNumber' => $orderNumber,
                'returnUrl'   => $returnUrl,
            ])
            ->post($apiUrl);
            
        if (!$response->successful()) {
            return response()->json([
                'error' => 'Failed to communicate with Meezan Payment Gateway',
                'details' => $response->body()
            ], 502);
        }

        $resData = $response->json();

        // Meezan response validation: Check for gateway errors (errorCode != 0)
        if (isset($resData['errorCode']) && (int)$resData['errorCode'] !== 0) {
            return response()->json([
                'error' => $resData['errorMessage'] ?? 'Gateway returned an error',
                'error_code' => $resData['errorCode']
            ], 400);
        }

        $meezanOrderId = $resData['orderId'] ?? null;
        $redirectPaymentUrl = $resData['formUrl'] ?? null;

        if (!$redirectPaymentUrl) {
            return response()->json(['error' => 'Payment redirection URL not received from gateway'], 500);
        }

        // 4. Transaction record Database mein save karein
        $transaction = Transaction::create([
            'payment_method_id' => $request->payment_method_id,
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'address'           => $request->address,
            'city'              => $request->city,
            'postal_code'       => $request->postal_code,
            'amount'            => $request->amount,
            'currency'          => 'PKR',
            'order_number'      => $orderNumber,
            'stripe_session_id' => $meezanOrderId, // Ya meezan_order_id column
            'status'            => 'processing',
            'link_status'       => 'active',
        ]);

        if (!$transaction) {
            throw new \Exception("Database Error: Failed to save the transaction record.");
        }

        // 5. Frontend redirect URL send karein
        return response()->json([
            'success'  => true,
            'id'       => $meezanOrderId,
            'order_no' => $orderNumber,
            'url'      => $redirectPaymentUrl // React is URL par user ko Meezan ke payment portal pe redirect karega
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
