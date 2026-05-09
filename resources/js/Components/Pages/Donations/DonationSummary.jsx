import React, { useState } from 'react';
import { useCart } from '@/Contexts/CardContext';

export default function DonationSummary() {
  const { cart, addToCart } = useCart();
  const [step, setStep] = useState(1); // 1: Summary, 2: Details, 3: Payment
  const [paymentMethod, setPaymentMethod] = useState('');
  const [formData, setFormData] = useState({
    firstName: '', lastName: '', email: '', phone: '',
    address: '', city: '', country: 'Pakistan', postalCode: ''
  });

  // Handlers
  const handleUpdateQuantity = (item, newQty) => addToCart(item, newQty);
  
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleFinalCheckout = () => {
    if (!paymentMethod) {
      alert("Please select a payment method");
      return;
    }
    console.log("Processing Payment...", { method: paymentMethod, donor: formData, cart });
    alert(`Redirecting to ${paymentMethod} Secure Gateway...`);
  };

  return (
    <div className="akf-combined-wrapper">
      <div className="akf-container">
        
        {/* STEP INDICATOR */}
        <div className="akf-steps">
          <div className={`akf-step ${step >= 1 ? 'active' : ''}`}>1. Summary</div>
          <div className={`akf-step ${step >= 2 ? 'active' : ''}`}>2. Details</div>
          <div className={`akf-step ${step >= 3 ? 'active' : ''}`}>3. Payment</div>
        </div>

        <div className="akf-main-grid">
          
          {/* LEFT COLUMN: CONDITIONAL STEPS */}
          <div className="akf-content-area">
            
            {/* STEP 1: SUMMARY TABLE */}
            {step === 1 && (
              <div className="akf-card">
                <div className="akf-card-header"><h2>Donation Summary</h2></div>
                {cart.items.length > 0 ? (
                  <table className="akf-table">
                    <thead>
                      <tr>
                        <th>Cause</th>
                        <th className="text-center">Qty</th>
                        <th className="text-right">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      {cart.items.map((item, i) => (
                        <tr key={i}>
                          <td>
                            <strong>{item.title}</strong>
                            <button className="remove-link" onClick={() => handleUpdateQuantity(item, 0)}>Remove</button>
                          </td>
                          <td className="text-center">
                            <div className="qty-btns">
                              <button onClick={() => handleUpdateQuantity(item, item.quantity - 1)}>-</button>
                              <span>{item.quantity}</span>
                              <button onClick={() => handleUpdateQuantity(item, item.quantity + 1)}>+</button>
                            </div>
                          </td>
                          <td className="text-right">PKR {item.total.toLocaleString()}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                ) : (
                  <div className="empty-msg">Your cart is empty.</div>
                )}
              </div>
            )}

            {/* STEP 2: BILLING FORM */}
            {/* STEP 2: BILLING FORM */}
{step === 2 && (
  <div className="akf-card">
    <div className="akf-card-header"><h2>Donor Details</h2></div>
    <div className="akf-form">
      <div className="form-row">
        <div className="akf-input-group">
          <label>First Name *</label>
          <input 
            type="text" 
            name="firstName" 
            value={formData.firstName} // Yeh value ko bind karega
            placeholder="First Name" 
            onChange={handleInputChange} 
            required 
          />
        </div>
        <div className="akf-input-group">
          <label>Last Name *</label>
          <input 
            type="text" 
            name="lastName" 
            value={formData.lastName} 
            placeholder="Last Name" 
            onChange={handleInputChange} 
            required 
          />
        </div>
      </div>

      <div className="form-row">
        <div className="akf-input-group">
          <label>Email Address *</label>
          <input 
            type="email" 
            name="email" 
            value={formData.email} 
            placeholder="Email Address" 
            onChange={handleInputChange} 
            required 
          />
        </div>
        <div className="akf-input-group">
          <label>Phone Number *</label>
          <input 
            type="tel" 
            name="phone" 
            value={formData.phone} 
            placeholder="Phone Number" 
            onChange={handleInputChange} 
            required 
          />
        </div>
      </div>

      <div className="akf-input-group full-width">
        <label>Full Address *</label>
        <input 
          type="text" 
          name="address" 
          value={formData.address} 
          placeholder="Full Address" 
          onChange={handleInputChange} 
          required 
        />
      </div>

      <div className="form-row">
        <div className="akf-input-group">
          <label>City *</label>
          <input 
            type="text" 
            name="city" 
            value={formData.city} 
            placeholder="City" 
            onChange={handleInputChange} 
            required 
          />
        </div>
        <div className="akf-input-group">
          <label>Postal Code</label>
          <input 
            type="text" 
            name="postalCode" 
            value={formData.postalCode} 
            placeholder="Postal Code" 
            onChange={handleInputChange} 
          />
        </div>
      </div>
      
      <button className="back-link" onClick={() => setStep(1)}>
        ← Back to Summary
      </button>
    </div>
  </div>
)}

            {/* STEP 3: PAYMENT METHODS */}
            {step === 3 && (
              <div className="akf-card">
                <div className="akf-card-header"><h2>Select Payment Method</h2></div>
                <div className="akf-payment-options">
                  <label className={`akf-pay-card ${paymentMethod === 'Stripe' ? 'selected' : ''}`}>
                    <input type="radio" name="pay" value="Stripe" onChange={(e) => setPaymentMethod(e.target.value)} />
                    <div className="pay-info">
                      <strong>Credit / Debit Card</strong>
                      <span>Pay via Stripe Secure (Visa/Mastercard)</span>
                    </div>
                  </label>

                  <label className={`akf-pay-card ${paymentMethod === 'EasyPaisa' ? 'selected' : ''}`}>
                    <input type="radio" name="pay" value="EasyPaisa" onChange={(e) => setPaymentMethod(e.target.value)} />
                    <div className="pay-info">
                      <strong>EasyPaisa</strong>
                      <span>Pay using your EasyPaisa Wallet</span>
                    </div>
                  </label>

                  <label className={`akf-pay-card ${paymentMethod === 'JazzCash' ? 'selected' : ''}`}>
                    <input type="radio" name="pay" value="JazzCash" onChange={(e) => setPaymentMethod(e.target.value)} />
                    <div className="pay-info">
                      <strong>JazzCash</strong>
                      <span>Pay using your JazzCash Mobile Account</span>
                    </div>
                  </label>
                  <button className="back-link" onClick={() => setStep(2)}>← Back to Details</button>
                </div>
              </div>
            )}
          </div>

          {/* RIGHT COLUMN: STICKY SIDEBAR */}
          <div className="akf-sidebar">
            <div className="akf-total-card">
              <h3>Order Total</h3>
              <div className="total-row"><span>Subtotal</span><span>PKR {cart.totalAmount.toLocaleString()}</span></div>
              <div className="total-row"><span>Donations</span><span>{cart.items.length}</span></div>
              <hr />
              <div className="grand-total">
                <span>Total Payable</span>
                <span className="amt">PKR {cart.totalAmount.toLocaleString()}</span>
              </div>
              
              {step === 1 && (
                <button className="main-action-btn" disabled={cart.items.length === 0} onClick={() => setStep(2)}>
                  Proceed to Details
                </button>
              )}
              
              {step === 2 && (
                <button className="main-action-btn" onClick={() => setStep(3)}>
                  Pay Now
                </button>
              )}

              {step === 3 && (
                <button className="main-action-btn checkout" onClick={handleFinalCheckout}>
                  Complete Donation
                </button>
              )}
              
              <p className="secure-text">🔒 256-bit Secure Encryption</p>
            </div>
          </div>

        </div>
      </div>

      <style>{`
        .akf-combined-wrapper { background: #f4f7fa; padding: 40px 0; min-height: 90vh; font-family: 'Inter', sans-serif; }
        .akf-container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
        .akf-steps { display: flex; justify-content: center; gap: 10px; margin-bottom: 30px; }
        .akf-step { padding: 8px 25px; background: #e2e8f0; border-radius: 20px; font-size: 13px; color: #64748b; font-weight: 600; }
        .akf-step.active { background: #042c5c; color: #fff; }
        
        .akf-main-grid { display: grid; grid-template-columns: 1fr 350px; gap: 25px; }
        .akf-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden; min-height: 350px; }
        .akf-card-header { padding: 15px 20px; border-bottom: 1px solid #eee; background: #fafafa; }
        .akf-card-header h2 { margin: 0; font-size: 18px; color: #042c5c; }
        
        /* Step 1 Table */
        .akf-table { width: 100%; border-collapse: collapse; }
        .akf-table th { text-align: left; padding: 12px 20px; font-size: 12px; color: #888; background: #fcfcfc; }
        .akf-table td { padding: 15px 20px; border-bottom: 1px solid #eee; }
        .qty-btns { display: flex; align-items: center; gap: 8px; background: #f0f2f5; padding: 4px; border-radius: 6px; width: fit-content; margin: 0 auto; }
        .qty-btns button { border: none; background: #fff; width: 24px; height: 24px; border-radius: 4px; cursor: pointer; }
        .remove-link { background: none; border: none; color: #ef4444; font-size: 11px; cursor: pointer; display: block; margin-top: 5px; }

        /* Step 2 Form */
        .akf-form { padding: 25px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .akf-form input { padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; width: 100%; box-sizing: border-box; }
        .full-width { margin-bottom: 15px; }
        .back-link { background: none; border: none; color: #64748b; cursor: pointer; margin-top: 20px; display: block; font-weight: 500; }

        /* Step 3 Payment */
        .akf-payment-options { padding: 25px; display: flex; flex-direction: column; gap: 15px; }
        .akf-pay-card { display: flex; align-items: center; gap: 15px; padding: 15px; border: 1px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: 0.2s; }
        .akf-pay-card:hover { border-color: #042c5c; background: #f8fafc; }
        .akf-pay-card.selected { border-color: #00c389; background: #f0fff4; border-width: 2px; }
        .pay-info strong { display: block; color: #042c5c; }
        .pay-info span { font-size: 12px; color: #64748b; }

        /* Sidebar */
        .akf-total-card { background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 10px 15px rgba(0,0,0,0.05); position: sticky; top: 20px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: #475569; }
        .grand-total { display: flex; justify-content: space-between; margin: 20px 0; font-weight: bold; }
        .grand-total .amt { color: #00a86b; font-size: 24px; }
        
        .main-action-btn { width: 100%; padding: 16px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; background: #042c5c; color: #fff; font-size: 16px; transition: 0.3s; }
        .main-action-btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .main-action-btn.checkout { background: #00c389; }
        .secure-text { text-align: center; font-size: 11px; color: #94a3b8; margin-top: 15px; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .empty-msg { padding: 40px; text-align: center; color: #64748b; }

        @media (max-width: 850px) {
          .akf-main-grid { grid-template-columns: 1fr; }
          .akf-sidebar { order: -1; }
        }
      `}</style>
    </div>
  );
}