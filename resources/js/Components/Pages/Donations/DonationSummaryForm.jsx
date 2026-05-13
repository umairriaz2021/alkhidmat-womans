import React from 'react';

const DonationSummaryForm = ({ formData, setFormData, errors }) => {
   const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };
  
    return (

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
            className={`border p-2 rounded ${errors.firstName ? 'border-red-500' : 'border-gray-300'}`} 
            onChange={handleInputChange} 
            required 
          />
          {errors.firstName && <span className="text-red-500 text-sm">{errors.firstName}</span>}
        </div>
        <div className="akf-input-group">
          <label>Last Name *</label>
          <input 
            type="text" 
            name="lastName" 
            value={formData.lastName} 
            placeholder="Last Name" 
            onChange={handleInputChange}
            className={`border p-2 rounded ${errors.lastName ? 'border-red-500' : 'border-gray-300'}`}

            required 
          />
          {errors.lastName && <span className="text-red-500 text-sm">{errors.lastName}</span>}
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
            className={`border p-2 rounded ${errors.email ? 'border-red-500' : 'border-gray-300'}`} 
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
            className={`border p-2 rounded ${errors.phone ? 'border-red-500' : 'border-gray-300'}`} 
            onChange={handleInputChange} 
            required 
          />
          {errors.phone && <span className="text-red-500 text-sm">{errors.phone}</span>}
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
          className={`border p-2 rounded ${errors.address ? 'border-red-500' : 'border-gray-300'}`} 
          required 
        />
        {errors.address && <span className="text-red-500 text-sm">{errors.address}</span>}
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
            className={`border p-2 rounded ${errors.city ? 'border-red-500' : 'border-gray-300'}`} 
            required 
          />
          {errors.city && <span className="text-red-500 text-sm">{errors.city}</span>}
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

       
    );
}

export default DonationSummaryForm;