import React, { useState } from 'react';
import { useCart } from '@/Contexts/CardContext';

export default function DonationPage() {
 const { cart, addToCart } = useCart();

  const donationPrograms = [
    {
      title: 'Emergency Relief Fund',
      desc: 'Support families affected by disasters and emergencies across the country.',
      amount: 5000,
    },
    {
      title: 'Food Support Program',
      desc: 'Provide ration packages and meals to deserving families.',
      amount: 3000,
    },
    {
      title: 'Clean Water Project',
      desc: 'Help install water filtration plants and hand pumps.',
      amount: 10000,
    },
    {
      title: 'Education Support',
      desc: 'Sponsor books, uniforms and educational support for children.',
      amount: 7000,
    },
  ];

const handleAddDonation = (item, quantity) => {
    addToCart(item, quantity);
};

  return (
    <div className="akf-donation-wrapper">
      {/* HERO SECTION */}
      <section className="akf-donation-hero">
        <div className="akf-donation-overlay"></div>

        <div className="akf-donation-container akf-donation-hero-inner">
          <div className="akf-donation-left">
            <span className="akf-donation-badge">100% Secure Donations</span>
            <h1>Make a Difference <br /> Through Your Donation</h1>
            <p>
              Support humanitarian causes including food, education, clean water,
              emergency relief and healthcare for deserving families.
            </p>

            <div className="akf-donation-features">
              <div className="akf-donation-feature-card">
                <h3>250K+</h3>
                <span>Families Supported</span>
              </div>
              <div className="akf-donation-feature-card">
                <h3>24/7</h3>
                <span>Online Donations</span>
              </div>
              <div className="akf-donation-feature-card">
                <h3>100%</h3>
                <span>Transparent Process</span>
              </div>
            </div>
          </div>

          {/* DONATION BOX */}
          <div className="akf-donation-box">
            <div className="akf-donation-box-header">
              <h2>Donate Now</h2>
              <p>Select a cause and contribute instantly.</p>
            </div>

            {/* Added onAdd prop here */}
            <DonationPagination 
              donationPrograms={donationPrograms} 
              onAdd={handleAddDonation} 
            />

            <div className="akf-donation-total-box">
              <div>
                <span>Total Donation</span>
                <h3>PKR {cart.totalAmount.toLocaleString()}</h3>
              </div>
              <button className="akf-donation-main-btn">Continue</button>
            </div>
          </div>
        </div>
      </section>

      {/* IMPACT SECTION */}
      <section className="akf-impact-section">
        <div className="akf-donation-container">
          <div className="akf-impact-heading">
            <span>OUR IMPACT</span>
            <h2>Your Donation Changes Lives</h2>
            <p>Every contribution helps provide basic necessities to thousands of families.</p>
          </div>

          <div className="akf-impact-grid">
            <ImpactCard title="1M+" label="Meals Distributed" />
            <ImpactCard title="500+" label="Water Projects" />
            <ImpactCard title="20K+" label="Students Supported" />
            <ImpactCard title="50K+" label="Medical Treatments" />
          </div>
        </div>
      </section>

      {/* FAQ SECTION */}
      <section className="akf-faq-section">
        <div className="akf-donation-container">
          <div className="akf-faq-heading">
            <span>FAQS</span>
            <h2>Frequently Asked Questions</h2>
          </div>
          <div className="akf-faq-wrapper">
            <Accordion title="How can I donate online?" content="You can donate securely using debit card, credit card, bank transfer or mobile wallets." />
            <Accordion title="Is my donation secure?" content="Yes, all transactions are encrypted and processed through secure payment gateways." />
            <Accordion title="Can I donate monthly?" content="Yes, recurring monthly donation options can be integrated easily." />
            <Accordion title="Will I receive a receipt?" content="Yes, a confirmation receipt can be sent instantly via email." />
          </div>
        </div>
      </section>

      <style>{`
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', Arial, sans-serif; background: #f4f7fb; }
        .akf-donation-wrapper { width: 100%; overflow: hidden; background: #f4f7fb; }
        .akf-donation-container { width: 100%; max-width: 1320px; margin: auto; padding: 0 20px; }
        .akf-donation-hero { position: relative; padding: 90px 0; background: linear-gradient(135deg, #042c5c 0%, #005f73 100%); }
        .akf-donation-overlay { position: absolute; inset: 0; background: radial-gradient(circle at top right, rgba(255,255,255,0.08), transparent 40%); }
        .akf-donation-hero-inner { position: relative; z-index: 2; display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 40px; align-items: center; }
        .akf-donation-badge { display: inline-flex; padding: 8px 16px; border-radius: 50px; background: rgba(255,255,255,0.12); color: #fff; font-size: 14px; margin-bottom: 24px; border: 1px solid rgba(255,255,255,0.15); }
        .akf-donation-left h1 { font-size: clamp(32px, 5vw, 64px); line-height: 1.1; color: #fff; margin: 0 0 24px; font-weight: 800; }
        .akf-donation-left p { color: rgba(255,255,255,0.82); font-size: 18px; line-height: 1.8; max-width: 650px; margin-bottom: 40px; }
        .akf-donation-features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .akf-donation-feature-card { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 25px 15px; backdrop-filter: blur(10px); text-align: center; }
        .akf-donation-feature-card h3 { margin: 0 0 5px; color: #fff; font-size: 28px; }
        .akf-donation-feature-card span { color: rgba(255,255,255,0.7); font-size: 13px; }
        .akf-donation-box { background: #fff; border-radius: 30px; padding: 30px; box-shadow: 0 25px 60px rgba(0,0,0,0.15); }
        .akf-donation-box-header h2 { margin: 0; font-size: 30px; color: #042c5c; }
        .akf-donation-box-header p { margin: 10px 0 25px; color: #6f7d92; }
        .akf-donation-program-list { display: flex; flex-direction: column; gap: 15px; }
        .akf-donation-card { border: 1px solid #dce5f2; border-radius: 18px; padding: 20px; background: #fff; transition: 0.3s; }
        .akf-donation-card:hover { border-color: #042c5c; transform: translateY(-2px); }
        .akf-donation-card-top { display: flex; justify-content: space-between; gap: 15px; }
        .akf-donation-card-content h3 { margin: 0 0 8px; color: #042c5c; font-size: 18px; }
        .akf-donation-card-content p { color: #6b7b8f; font-size: 14px; margin: 0; }
        .akf-quantity-box { display: flex; align-items: center; gap: 12px; background: #f4f7fb; border-radius: 50px; padding: 5px 12px; height: fit-content; }
        .akf-quantity-btn { width: 30px; height: 30px; border-radius: 50%; border: none; background: #042c5c; color: #fff; cursor: pointer; }
        .akf-donation-card-bottom { margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .akf-donation-price { color: #00a86b; font-size: 20px; font-weight: 700; }
        .akf-donation-add-btn { border: none; background: #042c5c; color: #fff; padding: 10px 20px; border-radius: 50px; font-weight: 600; cursor: pointer; }
        .akf-donation-total-box { margin-top: 25px; padding: 20px; border-radius: 20px; background: #042c5c; display: flex; justify-content: space-between; align-items: center; }
        .akf-donation-total-box h3 { margin: 5px 0 0; color: #fff; font-size: 28px; }
        .akf-donation-main-btn { border: none; background: #00c389; color: #fff; padding: 12px 25px; border-radius: 50px; font-weight: 700; cursor: pointer; }
        .akf-pagination-wrapper { display: flex; justify-content: center; gap: 8px; margin-top: 20px; }
        .akf-pagination-number { width: 35px; height: 35px; border-radius: 8px; border: none; background: #edf2f8; cursor: pointer; }
        .akf-pagination-number.active { background: #042c5c; color: #fff; }
        .akf-impact-section { padding: 80px 0; }
        .akf-impact-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 40px; }
        .akf-impact-card { background: #fff; padding: 30px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .akf-impact-card h3 { font-size: 32px; color: #042c5c; margin: 0; }
        .akf-faq-wrapper { max-width: 800px; margin: 40px auto; display: flex; flex-direction: column; gap: 15px; }
        .akf-accordion-item { background: #fff; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .akf-accordion-header { padding: 20px; cursor: pointer; font-weight: 700; color: #042c5c; list-style: none; display: flex; justify-content: space-between; }
        .akf-accordion-content { padding: 0 20px 20px; color: #6f7d92; }

        @media(max-width: 992px) {
          .akf-donation-hero-inner { grid-template-columns: 1fr; }
          .akf-impact-grid { grid-template-columns: 1fr 1fr; }
        }
      `}</style>
    </div>
  );
}

// Separate Sub-components for better readability
function DonationCard({ item, onAdd }) {
  const [quantity, setQuantity] = useState(1);
  return (
    <div className="akf-donation-card">
      <div className="akf-donation-card-top">
        <div className="akf-donation-card-content">
          <h3>{item.title}</h3>
          <p>{item.desc}</p>
        </div>
        <div className="akf-quantity-box">
          <button className="akf-quantity-btn" onClick={() => setQuantity(q => Math.max(0, q - 1))}>-</button>
          <span>{quantity}</span>
          <button className="akf-quantity-btn" onClick={() => setQuantity(q => q + 1)}>+</button>
        </div>
      </div>
      <div className="akf-donation-card-bottom">
        <div className="akf-donation-price">PKR {(item.amount * quantity).toLocaleString()}</div>
        <button className="akf-donation-add-btn" onClick={() => onAdd(item, quantity)}>Add</button>
      </div>
    </div>
  );
}

function DonationPagination({ donationPrograms, onAdd }) {
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 2;
  const totalPages = Math.ceil(donationPrograms.length / itemsPerPage);
  const currentItems = donationPrograms.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

  return (
    <>
      <div className="akf-donation-program-list">
        {currentItems.map((item, index) => (
          <DonationCard key={index} item={item} onAdd={onAdd} />
        ))}
      </div>
      <div className="akf-pagination-wrapper">
        {[...Array(totalPages)].map((_, i) => (
          <button key={i} onClick={() => setCurrentPage(i + 1)} className={`akf-pagination-number ${currentPage === i + 1 ? 'active' : ''}`}>
            {i + 1}
          </button>
        ))}
      </div>
    </>
  );
}

function ImpactCard({ title, label }) {
  return (
    <div className="akf-impact-card">
      <h3>{title}</h3>
      <p>{label}</p>
    </div>
  );
}

function Accordion({ title, content }) {
  return (
    <details className="akf-accordion-item">
      <summary className="akf-accordion-header">{title} <span>+</span></summary>
      <div className="akf-accordion-content"><p>{content}</p></div>
    </details>
  );
}