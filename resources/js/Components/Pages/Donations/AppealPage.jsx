// js/Components/Pages/Donations/CustomDonationPage.jsx
import React, { useState } from 'react';
import { router } from '@inertiajs/react';
import DonationLayout from '../DonationLayout';
import { useCardContext } from '../../../Contexts/CardContext';
import './css/CustomDonation.css';

const AppealPage = ({page}) => {
    console.log(`check`,page);
    const [amount, setAmount] = useState('');
    const [validationError, setValidationError] = useState('');
    const { addCustomDonation } = useCardContext();

    const handleInputChange = (e) => {
        const value = e.target.value;
        setAmount(value);
        if (validationError && parseFloat(value) > 0) {
            setValidationError('');
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        const parsedAmount = parseFloat(amount);

        if (!amount || isNaN(parsedAmount) || parsedAmount <= 0) {
            setValidationError('Please enter a valid donation amount greater than zero.');
            return;
        }

        const success = addCustomDonation(parsedAmount);
        if (success) {
            router.visit('/donation-summary');
        } else {
            setValidationError('Failed to process donation amount. Please try again.');
        }
    };

    return (
       
            <div className="cdp-main-wrapper">
                <div className="cdp-card-box">
                    <div className="cdp-card-header">
                        <h1 className="cdp-title">Enter Donation Amount</h1>
                        <p className="cdp-subtitle">
                            Specify a custom monetary contribution to support our active initiatives.
                        </p>
                    </div>

                    <form onSubmit={handleSubmit} className="cdp-form-container">
                        <div className="cdp-field-group">
                            <label htmlFor="cdp-amount-input" className="cdp-label">
                                Contribution Value ($)
                            </label>
                            <div className="cdp-input-wrapper">
                                <span className="cdp-currency-prefix">$</span>
                                <input
                                    id="cdp-amount-input"
                                    type="number"
                                    min="1"
                                    step="any"
                                    placeholder="0.00"
                                    value={amount}
                                    onChange={handleInputChange}
                                    className={`cdp-input-field ${validationError ? 'cdp-input-error' : ''}`}
                                    required
                                />
                            </div>
                            {validationError && (
                                <p className="cdp-error-text" role="alert">
                                    {validationError}
                                </p>
                            )}
                        </div>

                        <div className="cdp-button-group">
                            <button type="submit" className="cdp-submit-button">
                                Proceed to Donation Summary
                            </button>
                        </div>
                    </form>
                </div>
            </div>
       
    );
};

export default AppealPage;