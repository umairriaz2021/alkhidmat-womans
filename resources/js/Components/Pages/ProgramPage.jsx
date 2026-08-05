import React, { useState } from 'react';
import { router,Link } from '@inertiajs/react';

import '@/Components/Pages/Blogs/css/blog.css';
import '@/Components/Pages/Donations/css/CustomDonation.css';
import { useCardContext } from '@/Contexts/CardContext';
import dynamicJsonData  from '@/static-data/programs/palestine.json';
export default function ProgramPage({page})
{
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
     const getImageUrl = (path) => {
    if (!path) return '';
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    const cleanPath = path.replace(/^\/+/, '');
    return `/${cleanPath}`;
  };

    const handleSubmit = (e) => {
            e.preventDefault();
            const parsedAmount = parseFloat(amount);
    
            if (!amount || isNaN(parsedAmount) || parsedAmount <= 0) {
                setValidationError('Please enter a valid donation amount greater than zero.');
                return;
            }
    
            const success = addCustomDonation(parsedAmount,page?.title);
            if (success) {
                router.visit('/donation-summary');
            } else {
                setValidationError('Failed to process donation amount. Please try again.');
            }
        };
   return (
    <>
             <div className="akf-blog-page-wrapper">
                <section className="akf-blog-hero-section">
        <img
          src={getImageUrl(`storage/${page?.profile_image?.file_path}`)}
          alt={page?.title || 'Hero Image'}
          className="akf-blog-hero-image"
        />
        <div className="akf-blog-hero-overlay"></div>
        <div className="akf-blog-container">
          <div className="akf-blog-hero-content">
            <h1 className="akf-blog-main-title">{page?.title}</h1>
          </div>
        </div>
      </section>
       {/* Breadcrumb Section */}
      <div className="akf-blog-breadcrumb-main-wrap">
        <div className="akf-blog-container">
          <div className="akf-blog-breadcrumb-row">
            <Link href="/donations">Donations</Link>
            <span>/</span>
            <Link href="/programs">Programs</Link>
            <span>/</span>
            <p>{page?.title}</p>
          </div>
        </div>
      </div>
             </div>
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
           {(() => {
    
    const firstWord = page?.title?.trim().split(' ')[0]?.toLowerCase();
    const finalFirst = firstWord+'_content';
    
    const matchedData = firstWord ? dynamicJsonData[finalFirst] : null;

    if (!matchedData?.content || matchedData.content.length === 0) {
        return null; 
    }

    return (
        <div className="contentWrapper">
            {matchedData.content.map((text, idx) => (
                <div key={idx} className="akd-md-text" dangerouslySetInnerHTML={{ __html: text }}>
                    
                </div>
            ))}
        </div>
    );
})()}
            
       </>
    );
}

