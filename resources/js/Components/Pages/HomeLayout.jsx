import React, { useState } from 'react';

// Swiper.js slider ke liye (npm install swiper install karlein)
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import '../../../css/style.css';
import donationData from '@/static-data/Home/donations.json';
import helpData from '@/static-data/Home/help.json';
import WhoWeAre from '@/static-data/Home/whoWeArea.json';
import OurHero from '@/static-data/Home/ourHero.json';
import featuredCauses from '@/static-data/Home/featuredCauses.json';
import getInvolved from '@/static-data/Home/getInvolved.json';
import sprojects from '@/static-data/Home/sprojects.json';
import MetaData from '@/Layouts/MetaData';


const HomeLayout = ({ page,sliders }) => {
     console.log(page);
     const [activeType, setActiveType] = useState('Sadaqah for Gaza');
    const [amount, setAmount] = useState('');

    const donationOptions = [
        'Water Filtration Plant',
        'Zakat for Gaza',
        'Sadaqah for Gaza'
    ];
    const data = {
         description: "Hello"
    }
   return (
        <div className="home-template">
           <MetaData title={page.title} data={data} />
            
            <section className="hero-slider-section">
                <Swiper
                    modules={[Pagination, Autoplay]}
                    effect="fade"
                    
                    pagination={{ clickable: true }}
                    //autoplay={{ delay: 5000 }}
                    autoplay={false}
                    loop={sliders?.length > 1}
                    className="h-full w-full"
                >
                    {sliders && sliders.length > 0 ? (
                        sliders.map((slider, index) => (
                            <SwiperSlide key={index}>
                                <div 
                                    className="slider-slide-container"
                                    // Agar profile_image relationship hai toh uski file_path uthayen
                                    style={{ backgroundImage: `url('/storage/${slider.profile_image?.file_path || slider.image_id}')` }}
                                >
                                    <div className="slider-overlay"></div>
                                    
                                    <div className="slider-content" >
                                        <div className={`sliderWrapper ${slider.cta_text == null && 'invisible'}`}>
                                        <h2 style={{ fontSize: '1.5rem', marginBottom: '10px' }}>
                                            {slider.tagline}
                                        </h2>
                                        <h1 className="slider-heading">
                                            {slider.main_heading}
                                        </h1>
                                        {slider.cta_text && (
                                            <a href={slider.cta_url} className="btn-donate">
                                                {slider.cta_text}
                                            </a>
                                        )}
                                        </div>
                                        <div className="gt-hero-form-wrapper">
            <div className="gt-donation-card">
                <form className="gt-donation-form">
                    <h1 className="gt-donation-title">Donate</h1>
                    
                    <p className="gt-form-subtitle">Please select the Project to Donate</p>
                                            
                        {slider.donation_projects  && slider.donation_projects.length > 0 && <div className="gt-form-group">
                        <select className="gt-form-select">
                          {slider.donation_projects.map((project,index) => (
                               
                               <option key={index} value={project}>{project}</option>
                          ))} 
                            
                        </select>
                    </div>}
                    
                    {slider.donation_types && slider.donation_types.length > 0 && 
                    <div className="gt-form-group">
                        <label className="gt-form-label-small">Choose Amount</label>
                        
                        
                        <div className="gt-type-grid">
                            {slider.donation_types.map((option,index) => (
                                <button
                                    key={index}
                                    type="button"
                                    className={`gt-type-box ${activeType === option ? 'active' : ''}`}
                                    onClick={() => setActiveType(option)}
                                >
                                    {option}
                                </button>
                            ))}
                        </div>
                       
                    </div>
                     }

                    <div className="gt-form-group">
                        <label className="gt-form-label-small">Donation Amount (PKR)</label>
                        <input 
                            type="number" 
                            className="gt-form-input" 
                            placeholder="Enter your amount"
                            value={amount}
                            onChange={(e) => setAmount(e.target.value)}
                        />
                    </div>

                    <button type="submit" className="gt-submit-btn">
                        Donate Now
                    </button>
                </form>
            </div>
        </div>
                                    </div>

                                </div>
                            </SwiperSlide>
                        ))
                    ) : (
                        <SwiperSlide>
                            <div style={{ height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', background: '#eee' }}>
                                No Sliders Available
                            </div>
                        </SwiperSlide>
                    )}
                </Swiper>
                <div className="donation_form">
                     <div className="formWrapper">
                          <h2>Donate</h2>
                          <p>Please Select the Project to Donate</p>
                          <form id="submitDonateForm">
                             
                          </form>
                        </div>   
                </div>
            </section>

            {/* --- ABOUT SECTION --- */}
            <section className="about-section">
                <div className="container">
                    <div className="your-impact">
                     <h2>Your Impact In 2025</h2>
                     <p>In 2025, amidst global hardships, Alkhidmat Foundation continues to create a lasting impact through your unwavering support and generosity.</p>
                     <div className="donations">
                          {donationData && donationData.map((item,index) => (
                           <div key={index} className="donation-card">
                              <img src={item.image_url} />
                              <div className="donation-content">
                                <h4>{item.count}</h4>
                                <p>{item.title}</p>
                              </div>
                          </div>

                          ))}
                          
                          
                     </div>
                </div>
                </div>
            </section>
            <section className="whoWeAre">
                <div className="container">
                   {WhoWeAre && 
                   <>
                   <h2>{WhoWeAre.title}</h2>
                   <div className="whowearecontent">
                        {WhoWeAre.para && WhoWeAre.para.map((item,index) => (
                        <p key={index}>{item.pd}</p>
                        ))}
                        
                    </div>
                   </>
                   }
                    
                </div>
            </section>
            <section className="help-card-wraper mt-10">    
                <div className="container">
                     <div className="helpWrapper">
                      {helpData && helpData.map((item,index) => (
                        <a key={`help-${item.id}`} className="help-card">
                        <div className="imgwrap">
                            <img src={`assets/images/home${item.image}`} alt={item.title} />
                            <h5>{item.title}</h5>
                        </div>
                     </a>    
                      ))}
                    
                     </div>
                </div>
            </section>
           <section className="volunteer">
            <div className="volunteer-container container">
         
        {OurHero && 
        <>
        <div className="content-side">
        <h2 className="title">{OurHero.title}</h2>
        <p className="description">
          {OurHero.para}
        </p>
          <button className="volunteer-btn" onClick={() => window.location.href = OurHero.button_url }>
          {OurHero.button_text.toUpperCase()} <span className="arrow">→</span>
        </button>
      </div>
    <div className="image-side">
        {/* Is div mein aap apni image ya image collage set kar sakte hain */}
        <div className="image-wrapper">
           <img 
            src={`/assets/images/home/${OurHero.image}`} 
            alt={OurHero.title} 
            className="main-image" 
          />
        </div>
      </div>
        </>
        }
        
      </div>
    </section>
    <section className="featured-section">
        <div className="container">
      {featuredCauses && 
        <>
          <div className="header-text">
        <h2>{featuredCauses.title}</h2>
        <p>{featuredCauses.para}</p>
      </div>
       <div className="causes-grid">
        {featuredCauses.causes && featuredCauses.causes.map((cause,index) => (
          <div key={cause.id} className="cause-card">
            <div className="image-container">
              <img src={`assets/images/home/${cause.image}`} alt={cause.title} />
            </div>
            <div className="card-content">
              <h3>{cause.title}</h3>
              <p>{cause.para}</p>
              <button className="donate-btn" onClick={() => window.location.href = cause.button_url}>{cause.button_text.toUpperCase()}</button>
            </div>
          </div>
        ))}
      </div>  
        </>
      }
      </div>
    </section>
    <section className="ak-dashboard-wrapper">
      <div className="container">
        <div className="ak-main-grid">
          
          {/* COLUMN 1: LATEST NEWS */}
          <div className="ak-column">
            <div className="ak-header-tabs">
              <div className="ak-tab ak-active">Latest News</div>
              <div className="ak-tab">Opinion</div>
            </div>
            <div className="ak-scroll-area">
              {/* News Card 1 */}
              <div className="ak-news-card">
                <img src="https://placehold.co/400x240/1a3350/ffffff?text=Flood+Relief+Kasur" alt="News" className="ak-news-img" />
                <div className="ak-card-body">
                  <span className="ak-date">October 5, 2025</span>
                  <h4 className="ak-news-title">Alkhidmat Delivers Food to 2,000 Flood Hit Families in Kasur</h4>
                  <div className="ak-card-footer">
                    <a href="#" className="ak-read-more">Read more <span>&gt;</span></a>
                    <div className="ak-social-icons">
                      <span className="ak-icon fb">f</span>
                      <span className="ak-icon tw">t</span>
                      <span className="ak-icon in">in</span>
                    </div>
                  </div>
                </div>
              </div>

              {/* News Card 2 */}
              <div className="ak-news-card">
                <img src="https://placehold.co/400x240/1a3350/ffffff?text=Alkhidmat+Foundation+Update" alt="News" className="ak-news-img" />
                <div className="ak-card-body">
                  <span className="ak-date">September 26, 2025</span>
                  <h4 className="ak-news-title">Alkhidmat Foundation Spends Rs 1.10 Billion on Flood Relief and Rehabilitation Across Pakistan</h4>
                </div>
              </div>
            </div>
          </div>

          {/* COLUMN 2: EVENTS & LIVE */}
          <div className="ak-column ak-transparent">
            {/* Events Box */}
            <div className="ak-inner-box">
              <div className="ak-header-simple">Events</div>
              <div className="ak-event-content">
                <img src="https://placehold.co/400x220/00305b/ffffff?text=Grand+Iftar+Event" alt="Event" className="ak-event-img w-full max-w-full " />
                <div className="ak-event-details">
                  <div className="ak-status-bar">
                    <span className="ak-pill">Closed</span>
                    <span className="ak-meta">March 14</span>
                    <span className="ak-meta">13:00 To 19:00</span>
                  </div>
                  <h4 className="ak-event-title">Grand Iftar</h4>
                  <p className="ak-location">Location: UMT, Lahore</p>
                </div>
              </div>
            </div>

            {/* Video Box */}
            <div className="ak-inner-box">
              <div className="ak-header-simple">Alkhidmat LIVE</div>
              <div className="ak-video-container">
                <div className="ak-video-placeholder">
                  <img src="https://placehold.co/400x225/000000/ffffff?text=LIVE+STREAM" alt="Live" className="w-full max-w-full" />
                  <div className="ak-play-btn">▶</div>
                </div>
              </div>
              <div className="ak-video-info">
                <div className="ak-info-flex">
                   <div className="ak-mini-logo">AK</div>
                   <div>
                      <strong>Alkhidmat Foundation Pakistan ▼</strong>
                      <p>@AlkhidmatOrg</p>
                   </div>
                </div>
              </div>
            </div>
          </div>

          {/* COLUMN 3: SOCIAL STREAM */}
          <div className="ak-column">
            <div className="ak-header-simple">Social Stream</div>
            <div className="ak-scroll-area ak-social-bg">
              <div className="ak-fb-post">
                <div className="ak-fb-header">
                  <div className="ak-avatar">AK</div>
                  <div className="ak-user">
                    <h5>Alkhidmat Foundation Pakistan</h5>
                    <span>23 hours ago</span>
                  </div>
                  <div className="ak-fb-logo">f</div>
                </div>
                <div className="ak-fb-body" dir="rtl">
                  الخدمت فاؤنڈیشن پاکستان: تھر کے دور دراز علاقوں میں پینے کے صاف پانی کی فراہمی کے لیے 215 نئے سولر پمپس لگا دیئے گئے ہیں۔
                </div>
                <img src="https://placehold.co/400x300/f0f2f5/000000?text=Social+Post+Image" alt="Social" className="ak-post-img w-full max-w-full" />
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
    <section className="gi-section">
      <div className="container">
        {getInvolved && 
         <>
         <h2 className="gi-section-title">{getInvolved.title}</h2>
         <div className="gi-grid">
          {getInvolved.cards && getInvolved.cards.map((card, index) => (
            <div key={index} className="gi-card">
              <div className="gi-card__content">
                <h3 className="gi-card__title">{card.title}</h3>
                <p className="gi-card__description">{card.text}</p>
              </div>
              <button onClick={() => window.Location.href = card.button_url} className="gi-card__button">
                {card.button_text}
              </button>
            </div>
          ))}
        </div>
         </> 
        }
        
        
        
      </div>
    </section>
<section className="soa-section">
      <div className="container">
        {sprojects && 
           <>
           <div className="soa-header">
          
          <h2 className="soa-title">{sprojects.title}</h2>
          <p className="soa-subtitle">
            {sprojects.text}
          </p>
          
          {/* Custom Navigation Arrows */}
          <div className="soa-nav-wrapper">
            <div className="soa-prev">←</div>
            <div className="soa-next">→</div>
          </div>
        </div>
         <Swiper
          modules={[Navigation]}
          spaceBetween={0}
          slidesPerView={1}
          navigation={{
            prevEl: '.soa-prev',
            nextEl: '.soa-next',
          }}
          breakpoints={{
            640: { slidesPerView: 2 },
            1024: { slidesPerView: 4 },
          }}
          className="soa-swiper"
        >
          {sprojects.projects && sprojects.projects.map((item, index) => (
            <SwiperSlide key={item.id} className="soa-slide">
              <div className="soa-card">
                <img src={`assets/images/home/sprojects/${item.image}`} alt={item.title} className="soa-card-img" />
                <div className="soa-overlay">
                  <div className="soa-content">
                    <h3 className="soa-card-title">{item.title}</h3>
                    <p className="soa-card-desc">{item.desc}</p>
                  </div>
                </div>
              </div>
            </SwiperSlide>
          ))}
        </Swiper>
           </>
          }
        

       
      </div>
    </section>
        </div>
    );
};

export default HomeLayout;