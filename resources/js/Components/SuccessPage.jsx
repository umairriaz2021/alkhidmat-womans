import React from 'react';
import { Head, Link } from '@inertiajs/react';

const SuccessPage = ({ sessionId }) => {
    return (
        <div className="success-wrapper" style={{ textAlign: 'center', padding: '50px' }}>
            <Head title="Donation Successful" />
            
            <h1 style={{ color: '#28a745' }}>✅ Thank You!</h1>
            <p>Aapki donation kamyabi se receive ho gayi hai.</p>
            
            <div className="details" style={{ margin: '20px 0', background: '#f8f9fa', padding: '15px' }}>
                <strong>Session ID:</strong> 
                <code style={{ display: 'block', wordBreak: 'break-all' }}>{sessionId}</code>
            </div>

            <Link 
                href="/" 
                className="btn-back"
                style={{ color: '#007bff', textDecoration: 'underline' }}
            >
                Wapas Home Par Jayein
            </Link>
        </div>
    );
};

export default SuccessPage;