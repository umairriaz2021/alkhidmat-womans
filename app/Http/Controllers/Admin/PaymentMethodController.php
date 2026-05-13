<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    // 1. Key nikalne ka behtar tarika (Loop ke bajaye direct find karein agar possible ho)
    // Note: Make sure $paymentMethod model upar import ho ya sahi variable use ho
    $paymentGatewayData = \App\Models\PaymentMethod::all(); 
    
    $secretKey = '';
   
    foreach($paymentGatewayData as $method) {
        if(strtolower($method->name) === 'stripe') {
            // Aapke array structure ke mutabiq keys 'general' JSON column mein hain
            $secretKey = $method->general['secret_key'] ?? '';
           
            break;
        }
    }

    if (empty($secretKey)) {
        return response()->json(['error' => 'Stripe secret key not found'], 404);
    }

    // IMPORTANT: print_r ya die() ko remove karein warna React crash ho jayega
    // Kyunki React ko sirf JSON chahiye, HTML/Text nahi.
    
    \Stripe\Stripe::setApiKey($secretKey);

    try {
        // 2. Stripe Checkout Session create karein
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd', 
                    'product_data' => [
                        'name' => 'Donation Payment',
                    ],
                    'unit_amount' => (int)($request->amount * 100), // Ensure it's an integer
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/success') .'?session_id={CHECKOUT_SESSION_ID}', 
            'cancel_url' => 'http://localhost:5173/cancel',
        ]);
        //$method_data = \App\Models\PaymentMethod::where('name','Stripe')->first();
        $createTransaction = Transaction::create(
            [
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
            'stripe_session_id' => $session->id,
            'status'         => 'processing', // 1 = Pending
            'link_status'       => 'active', // Link abhi valid hai
        ]
        );
        
        if (!$createTransaction) {
        throw new \Exception("Database Error: Failed to save the transaction record.");
    }

        return response()->json([
        'id' => $session->id,
        'url' => $session->url // Yeh line sabse zaroori hai
    ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
