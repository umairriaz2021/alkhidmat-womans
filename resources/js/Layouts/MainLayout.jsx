import React, { useState, useEffect,Fragment } from 'react';
import { Link } from '@inertiajs/react';
import '../../css/style.css';
import FooterDonation from '@/static-data/footer/footer-links/donation-links/donations.json';
import Address from '@/static-data/footer/footer-links/address/address.json';
import FirstColumn  from '@/static-data/footer/firstColumn.json'
import OurAppeal from '@/static-data/footer/footer-links/our-appeals/appeals.json';
import Resources from '@/static-data/footer/footer-links/our-resources/resources.json'
import AboutUs from '@/static-data/footer/footer-links/about-us/about.json'
import CopyRight from '@/static-data/footer/settings.json' 
export default function MainLayout({ children}) {
    const {settings,menus} = children.props;
    
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [activeAccordion, setActiveAccordion] = useState(null); // Mobile accordion state
    const topLevelMenus = menus ? menus.filter(menu => menu.parent_id === null) : [];
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
                            src={`storage/${settings.site_logo.file_path}`} 
                            alt="Alkhidmat Womans Home Logo" 
                            className="transition-all duration-300"
                            style={{ height: isScrolled ? '45px' : '60px' }} 
                        />
                    </Link>


{/* Desktop Navigation */}
<ul className="hidden lg:flex nav-links items-center">
    {topLevelMenus.filter(menu => menu.status?.name === 'publish').map((menu) => {
        const hasMegaMenu = menu.mega_menus && menu.mega_menus.length > 0;

        return (
            /* FIX 1: Li tag par hamesha unique key honi chahiye */
            <li key={`menu-${menu.id}`} className={`relative group ${hasMegaMenu ? 'ak-has-mega' : ''}`}>
                <Link href={menu.url} className="flex items-center gap-1 uppercase px-4 py-5 hover:text-green-600 transition">
                    {menu.title} {hasMegaMenu && <span className="text-[10px]">▼</span>}
                </Link>

                {hasMegaMenu && (
                    <div className="ak-mega-wrapper shadow-2xl border-t-4 border-green-600">
                        <div className="flex flex-nowrap gap-12 p-8">
                            {menu.mega_menus.map((mega) => (
                                /* FIX 2: Mega Menu columns par bhi key lazmi hai */
                                <div key={`mega-${mega.id}`} className="ak-mega-col min-w-[150px]">
                                    <h4 className="font-bold text-dark-green border-b-2 border-green-500 pb-2 mb-4 uppercase whitespace-nowrap">
                                        {mega.group_name}
                                    </h4>
                                    
                                    <div className="flex flex-col space-y-2">
                                        {mega.links_data && mega.links_data.map((link) => (
                                            
                                            /* FIX 3: Inner Links par unique key */
                                            <Link 
                                                key={`link-${link.id}`} 
                                                href={link.url}
                                                className="text-gray-600 hover:text-green-600 hover:translate-x-1 transition-all"
                                            >
                                                {link.title}
                                            </Link>
                                        ))}
                                    </div>
                                </div>
                            ))}
                            
                            {/* Static content like this Appeal Box doesn't need a key as it's not part of a map loop */}
                            <div className="bg-green-50 p-6 rounded-xl text-center w-[240px] shrink-0 border border-green-100">
                                <span className="text-green-600 font-bold text-sm block mb-2 underline">URGENT APPEAL</span>
                                <p className="text-sm font-semibold mb-4 text-gray-800">Support Gaza Emergency Relief Fund</p>
                                <Link href="/donate" className="bg-green-600 text-white py-2 px-6 rounded-lg text-xs font-bold hover:bg-green-700 transition inline-block">
                                    DONATE NOW
                                </Link>
                            </div>
                        </div>
                    </div>
                )}
            </li>
        );
    })}
</ul>

                    {/* Right Side Buttons */}
                    <div className="flex items-center gap-4">
                        <Link href="/donate" className="btn-donate !hidden sm:!block ">DONATE NOW</Link>
                        
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
        
        {topLevelMenus.filter(menu => menu.status?.name === 'publish').map((menu, index) => {
            const hasMegaMenu = menu.mega_menus && menu.mega_menus.length > 0;

            return (
                <li key={menu.id} className="border-b border-gray-100">
                    {hasMegaMenu ? (
                        /* --- Accordion Header (For Mega Me    nus) --- */
                        <>
                            <div 
                                className="flex justify-between items-center py-4 font-bold text-gray-700 uppercase cursor-pointer"
                                onClick={() => toggleAccordion(index)}
                            >
                                {menu.title} 
                                <span className="text-xl">{activeAccordion === index ? '−' : '+'}</span>
                            </div>

                            {/* --- Accordion Content --- */ }
                            <div className={`overflow-hidden transition-all duration-300 ${activeAccordion === index ? 'max-h-screen mb-4' : 'max-h-0'}`}>
                                <div className="pl-4 flex flex-col gap-6 bg-gray-50 p-4 rounded-lg">
                                    {menu.mega_menus.map((mega) => (
                                        <div key={mega.id} className="flex flex-col gap-2">
                                            {/* Mega Menu Group Name */}
                                            <h5 className="font-bold text-green-600 text-sm border-b border-green-100 pb-1 uppercase">
                                                {mega.group_name}
                                            </h5>
                                            
                                            {/* Links inside this group */}
                                            <div className="flex flex-col gap-3 pl-2 mt-1">
                                                {mega.links_data && mega.links_data.map((link) => (
                                                    <Link 
                                                        key={link.id} 
                                                        href={link.url} 
                                                        className="text-gray-600 text-sm hover:text-green-600"
                                                        onClick={() => setIsMobileMenuOpen(false)} // Close menu on click
                                                    >
                                                        {link.title}
                                                    </Link>
                                                ))}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </>
                    ) : (
                        /* --- Simple Link (For menus with no child/mega menu) --- */
                        <Link 
                            href={menu.url} 
                            className="block py-4 font-bold text-gray-700 uppercase"
                            onClick={() => setIsMobileMenuOpen(false)}
                        >
                            {menu.title}
                        </Link>
                    )}
                </li>
            );
        })}

        {/* --- Static Donate Button at Bottom --- */}
        <li className="mt-8">
            <Link href="/donate" className="btn-donate w-full text-center block py-3 rounded-md bg-green-600 text-white font-bold">
                DONATE NOW
            </Link>
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
                <img src={`storage/${settings.footer_logo.file_path}`} alt="Alkhidmat Womans footer logo" className="ak-f-logo" onClick={() => window.location.href = "/"} />
                {FirstColumn && 
                <>
                <p className="ak-f-text">
                    {FirstColumn.short_description}
                </p>
                <Link to={FirstColumn.link.url} className="ak-f-link-arrow">➔ {FirstColumn.link.link_name}</Link>
                <div className="ak-f-legal">
                    <p>{FirstColumn.digital_id_no}</p>
                    <p>National Taxation Number: {FirstColumn.tax_number}</p>
                    <p>Registration Number: {FirstColumn.reg_number}</p>
                    <p className="ak-f-tax-note">{FirstColumn.tax_exempt}</p>
                </div>
                
                </>
                }
            </div>

            {/* Column 2: Donation & Contact */}
            <div className="ak-f-col">
                {FooterDonation && 
                 <>
                <h4 className="ak-f-heading">{FooterDonation.title}</h4>
                <div className="ak-f-donate-box">
               {FooterDonation.links && FooterDonation.links.map((item, index, array) => {
  const isLast = index === array.length - 1;

  return (
    <Fragment key={index}>
      {!isLast ? (
        <p>{item.title}: <strong>{item.value}</strong></p>
      ) : (
        <>
          <p>{item.title}:</p>
          <Link href={item.value} className="ak-f-link-arrow">
            ➔ At Our Collection Centers
          </Link>
        </>
      )}
    </Fragment>
  );
})}
                   
                    
                </div>
                 </> 
                }
                
                
                

                <div className="ak-f-social">
                    {Address && 
                      <>
                      <h4 className="ak-f-heading mt-8">{Address.title}</h4>
                        <p className="ak-f-address">
                            {Address.text}
                        </p>
                        <p className="ak-f-contact-info">
                            Phone: <Link to={`tel:${Address.phone}`}>{Address.phone}</Link> <br />
                            Email: <Link to={`mail:${Address.email}`}>{Address.email}</Link>
                        </p>
                      <span>{Address.social_link_title}</span>
                      <div className="ak-f-social-icons">
                       {Address.social_links && Address.social_links.map((item,index) => (
                          
                        <Link key={index} to={item.url}>{item.link_name}</Link>
                        ))}
                    </div>
                      </>
                     }
                    
                </div>
            </div>

            {/* Column 3: Orange Quick Links */}
            <div className="ak-f-col ak-f-orange-box">
                <div className="ak-f-link-group">
                    {OurAppeal && 
                    <>
                    <h5>{OurAppeal.title}</h5>
                    <ul>
                        {OurAppeal.links && OurAppeal.links.map((item,index) => (
                            
                            <li key={`appeal-${index}`}><Link key={index} to={item.url}>➔ {item.title}</Link></li>
                        ))}
                        
                    </ul>
                    </>
                    }
                    
                    
                </div>
                <div className="ak-f-link-group">
                     {Resources && 
                    <>
                    <h5>{Resources.title}</h5>
                    <ul>
                        {Resources.links && Resources.links.map((item,index) => (
                            
                            <li key={`res-${index}`}><Link to={item.url}>➔ {item.title}</Link></li>
                        ))}
                        
                    </ul>
                    </>
                    }
                </div>
                <div className="ak-f-link-group">
                    {AboutUs && 
                    <>
                    <h5>{AboutUs.title}</h5>
                    <ul>
                        {AboutUs.links && AboutUs.links.map((item,index) => (
                            
                            <li key={`about-${index}`}><Link to={item.url}>➔ {item.title}</Link></li>
                        ))}
                        
                    </ul>
                    </>
                    }
                </div>
            </div>
        </div>
    </div>

    {/* 3. Bottom Copyright Bar */}
    <div className="ak-f-bottom-bar">
        <div className="container ak-f-bottom-inner">
            {CopyRight && 
            <>
            <p>{CopyRight.copyright}</p>
            <div className="ak-f-bottom-nav">
                {CopyRight.links && CopyRight.links.map((item,index) => (
                <Link key={index} to={item.url}>{item.title}</Link>
                ))}
                </div>
            </>
            }
            
            
        </div>
    </div>
    
    {/* Sticky Button */}
    {/* <div className="ak-f-sticky-btn">Ways To Donate</div> */}
</footer>
        </div>
    );
}