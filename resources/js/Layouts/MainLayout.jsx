import React, { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';
import '../../css/style.css';

export default function MainLayout({ children }) {
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [activeAccordion, setActiveAccordion] = useState(null); // Mobile accordion state

    useEffect(() => {
        const handleScroll = () => setIsScrolled(window.scrollY > 50);
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const toggleAccordion = (index) => {
        setActiveAccordion(activeAccordion === index ? null : index);
    };

    return (
        <div className="layout-wrapper overflow-x-hidden">
            {/* --- TOP BAR --- */}
            <div className="top-bar hidden md:block">
                <div className="top-bar-container">
                    <div>
                        <span className="mr-4">📞 +92 21 111 503 503</span>
                        <span>✉️ info@alkhidmat.org</span>
                    </div>
                    <div className="flex gap-4">
                        <Link href="/careers" className="hover:text-green-400">Careers</Link>
                        <Link href="/news" className="hover:text-green-400">News</Link>
                        <Link href="/contact" className="hover:text-green-400">Contact Us</Link>
                    </div>
                </div>
            </div>

            {/* --- STICKY HEADER --- */}
            <header className={`main-header bg-white ${isScrolled ? 'header-scrolled' : 'header-default'}`}>
                <nav className="nav-container relative flex items-center justify-between px-4">
                    {/* Logo */}
                    <Link href="/" className="z-50">
                        <img 
                            src="/assets/images/logo-AKFP.png" 
                            alt="Logo" 
                            className="transition-all duration-300"
                            style={{ height: isScrolled ? '45px' : '60px' }} 
                        />
                    </Link>

                    {/* Desktop Navigation */}
                    <ul className="hidden lg:flex nav-links items-center">
                        <li className="ak-has-mega">
                            <Link href="/" className="flex items-center gap-1">
                                HOME <span className="text-[10px]">▼</span>
                            </Link>
                            
                            {/* Mega Menu Desktop */}
                            <div className="ak-mega-wrapper shadow-2xl border-t-4 border-green-600">
                                <div className="max-w-7xl mx-auto grid grid-cols-4 gap-8 p-10">
                                    <div className="ak-mega-col">
                                        <h4 className="font-bold text-dark-green border-b-2 border-green-500 pb-2 mb-4">WHO WE ARE</h4>
                                        <Link href="/history">Our History</Link>
                                        <Link href="/vision">Vision & Mission</Link>
                                        <Link href="/team">Our Team</Link>
                                    </div>
                                    <div className="ak-mega-col">
                                        <h4 className="font-bold text-dark-green border-b-2 border-green-500 pb-2 mb-4">PROGRAMS</h4>
                                        <Link href="/health">Health Services</Link>
                                        <Link href="/education">Education</Link>
                                        <Link href="/orphan-care">Orphan Care</Link>
                                    </div>
                                    <div className="ak-mega-col">
                                        <h4 className="font-bold text-dark-green border-b-2 border-green-500 pb-2 mb-4">EMERGENCY</h4>
                                        <Link href="/rescue">Rescue 1023</Link>
                                        <Link href="/disaster">Disaster Relief</Link>
                                        <Link href="/ambulance">Ambulance</Link>
                                    </div>
                                    <div className="bg-green-50 p-6 rounded-xl text-center">
                                        <span className="text-green-600 font-bold text-sm block mb-2 underline">URGENT APPEAL</span>
                                        <p className="text-sm font-semibold mb-4">Support Gaza Emergency Relief Fund</p>
                                        <Link href="/donate" className="ak-btn-primary py-2 px-4 text-xs">DONATE NOW</Link>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><Link href="/who-we-are">WHO WE ARE</Link></li>
                        <li><Link href="/services">SERVICES</Link></li>
                        <li><Link href="/disaster-management">DISASTER</Link></li>
                    </ul>

                    {/* Right Side Buttons */}
                    <div className="flex items-center gap-4">
                        <Link href="/donate" className="hidden sm:block btn-donate">DONATE NOW</Link>
                        
                        {/* Mobile Menu Toggle */}
                        <button 
                            className="lg:hidden p-2 text-2xl text-dark-green"
                            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                        >
                            {isMobileMenuOpen ? '✖' : '☰'}
                        </button>
                    </div>

                    {/* Mobile Navigation Drawer (Accordion Style) */}
                    <div className={`ak-mobile-drawer lg:hidden fixed inset-0 top-[70px] bg-white z-40 transition-transform duration-300 ${isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'}`}>
                        <ul className="flex flex-col p-6 overflow-y-auto h-full pb-20">
                            {/* Accordion Item 1 */}
                            <li className="border-b border-gray-100">
                                <div 
                                    className="flex justify-between items-center py-4 font-bold text-gray-700"
                                    onClick={() => toggleAccordion(0)}
                                >
                                    HOME {activeAccordion === 0 ? '−' : '+'}
                                </div>
                                <div className={`overflow-hidden transition-all duration-300 ${activeAccordion === 0 ? 'max-h-screen mb-4' : 'max-h-0'}`}>
                                    <ul className="pl-4 flex flex-col gap-3 text-gray-600 bg-gray-50 p-4 rounded-lg">
                                        <li><Link href="/history">Our History</Link></li>
                                        <li><Link href="/vision">Vision & Mission</Link></li>
                                        <li><Link href="/health">Health Services</Link></li>
                                        <li><Link href="/education">Education</Link></li>
                                    </ul>
                                </div>
                            </li>
                            <li className="border-b border-gray-100 py-4"><Link href="/who-we-are" className="font-bold">WHO WE ARE</Link></li>
                            <li className="border-b border-gray-100 py-4"><Link href="/services" className="font-bold">SERVICES</Link></li>
                            <li className="border-b border-gray-100 py-4"><Link href="/disaster-management" className="font-bold">DISASTER MANAGEMENT</Link></li>
                            <li className="mt-8">
                                <Link href="/donate" className="btn-donate w-full text-center">DONATE NOW</Link>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            <main className="flex-1">{children}</main>

           {/* --- UNIQUE FOOTER SECTION --- */}
<footer className="ak-f-wrapper">
    {/* 1. Newsletter Strip */}
    <div className="ak-f-newsletter">
        <div className="container ak-f-newsletter-inner">
            <h3 className="ak-f-newsletter-title">Subscribe for Newsletter</h3>
            <div className="ak-f-newsletter-form">
                <input type="text" placeholder="Name" className="ak-f-input" />
                <input type="email" placeholder="Email" className="ak-f-input" />
                <button type="submit" className="ak-f-subscribe-btn">Subscribe</button>
            </div>
        </div>
    </div>

    {/* 2. Main Footer Content */}
    <div className="ak-f-main-content">
        <div className="container ak-f-grid">
            
            {/* Column 1: Organization Info */}
            <div className="ak-f-col">
                <img src="https://alkhidmat.org/images/footer-logo.svg" className="ak-f-logo" />
                <p className="ak-f-text">
                    Alkhidmat Foundation is one of the leading, non-profit organization in Pakistan, 
                    fully dedicated to humanitarian services since 1990.
                </p>
                <Link href="#" className="ak-f-link-arrow">➔ Donate for Gaza from Pakistan</Link>
                <div className="ak-f-legal">
                    <p>D&B D-U-N-S: 64-579-3014</p>
                    <p>National Taxation Number: C777982</p>
                    <p>Registration Number: RP/4243/L/S/90/375</p>
                    <p className="ak-f-tax-note">Alkhidmat Foundation Pakistan is tax exempted under FBR act 2(36)c.</p>
                </div>
            </div>

            {/* Column 2: Donation & Contact */}
            <div className="ak-f-col">
                <h4 className="ak-f-heading">Donate Now</h4>
                <div className="ak-f-donate-box">
                    <p>Meezan Bank: <strong>021401008611151</strong></p>
                    <p>Cash Pick-Up: <strong>0800-44448</strong></p>
                    <p>Donate In-Person:</p>
                    <Link href="#" className="ak-f-link-arrow">➔ At Our Collection Centers</Link>
                </div>
                
                <h4 className="ak-f-heading mt-8">Address</h4>
                <p className="ak-f-address">
                    Alkhidmat Foundation Headoffice, 3km Khayaban-e-Jinnah, Lahore, Punjab, Pakistan
                </p>
                <p className="ak-f-contact-info">
                    Phone: +92 42 3802 0222<br />
                    Email: info@alkhidmat.org
                </p>

                <div className="ak-f-social">
                    <span>Connect With Us</span>
                    <div className="ak-f-social-icons">
                        <a href="#">f</a> <a href="#">x</a> <a href="#">yt</a> <a href="#">in</a> <a href="#">tk</a>
                    </div>
                </div>
            </div>

            {/* Column 3: Orange Quick Links */}
            <div className="ak-f-col ak-f-orange-box">
                <div className="ak-f-link-group">
                    <h5>Our Appeals</h5>
                    <ul>
                        <li><Link href="#">➔ Ramadan 2025</Link></li>
                        <li><Link href="#">➔ Zakat</Link></li>
                        <li><Link href="#">➔ Fidya</Link></li>
                        <li><Link href="#">➔ Food Pack</Link></li>
                        <li><Link href="#">➔ Palestine Emergency Appeal</Link></li>
                    </ul>
                </div>
                <div className="ak-f-link-group">
                    <h5>Resources</h5>
                    <ul>
                        <li><Link href="#">➔ Our Resources</Link></li>
                        <li><Link href="#">➔ Women Philanthropy</Link></li>
                        <li><Link href="#">➔ Our Impact</Link></li>
                    </ul>
                </div>
                <div className="ak-f-link-group">
                    <h5>About Us</h5>
                    <ul>
                        <li><Link href="#">➔ Our Journey</Link></li>
                        <li><Link href="#">➔ Regions</Link></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {/* 3. Bottom Copyright Bar */}
    <div className="ak-f-bottom-bar">
        <div className="container ak-f-bottom-inner">
            <p>© Copyright 1990-2025 Alkhidmat Foundation Pakistan</p>
            <div className="ak-f-bottom-nav">
                <Link href="#">Home</Link>
                <Link href="#">Careers</Link>
                <Link href="#">Contact Us</Link>
                <Link href="#">HRMS</Link>
                <Link href="#">FAQs</Link>
                <Link href="#">Privacy Policy</Link>
            </div>
        </div>
    </div>
    
    {/* Sticky Button */}
    <div className="ak-f-sticky-btn">Ways To Donate</div>
</footer>
        </div>
    );
}