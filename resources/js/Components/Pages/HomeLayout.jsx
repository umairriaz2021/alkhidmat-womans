import React, { useState } from 'react';

// Swiper.js slider ke liye (npm install swiper install karlein)
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import '../../../css/style.css';
import { Head } from '@inertiajs/react'


const HomeLayout = ({ page,sliders }) => {
     console.log(sliders);
     const [activeType, setActiveType] = useState('Sadaqah for Gaza');
    const [amount, setAmount] = useState('');

    const donationOptions = [
        'Water Filtration Plant',
        'Zakat for Gaza',
        'Sadaqah for Gaza'
    ];
    const causes = [
    {
      id: 1,
      image: "assets/images/home/cause-1.webp",
      title: "Rebuild Gaza",
      desc: "The most beloved deeds to Allah are those done regularly, even if small. (Bukhari, 6465)"
    },
    {
      id: 2,
      image: "assets/images/home/cause-2.webp",
      title: "Give Zakat",
      desc: "\"Zakat is for the poor, the needy, those in debt, and in the cause of Allah.\" Surah At-Tawbah (9:60)"
    },
    {
      id: 3,
      image: "assets/images/home/cause-3.webp",
      title: "Give Sadaqah",
      desc: "\"Whatever you spend in Sadaqah, Allah will replace it; and He is the Best of Providers.\" Surah Saba (34:39)"
    }
  ];
  const cards = [
    {
      title: "Become Volunteer",
      text: "Are you looking for an opportunity to make a difference guided by Islamic principles? Become a volunteer with Alkhidmat Foundation and discover the transformative power of volunteerism and change lives.",
      btnText: "REGISTER NOW",
      link: "#"
    },
    {
      title: "Bank Transfer",
      text: "Direct deposits or transfers can be made into our accounts at any of the banks. Select your preferred bank from the list.",
      btnText: "VIEW BANK LIST",
      link: "#"
    },
    {
      title: "Doorstep Collection",
      text: "You can donate through cheques or bank drafts, simply call at 0800 44 44 8 or 0304 111 4 22 and Alkhidmat's representative will collect it.",
      btnText: "CASH PICKUP",
      link: "#"
    }
  ];
  const projects = [
    {
      title: "Aghosh College Murree",
      desc: "Empowering orphans with top-tier education in a serene and secure residential environment nestled in the hills of Murree.",
      img: "https://alkhidmat.org/backend/images/modules/home/slider/169147657964d1e263a454e.jpg"
    },
    {
      title: "Alkhidmat Hospital Tharparkar",
      desc: "Delivering quality healthcare to remote communities in Tharparkar—equipped with modern facilities and compassionate care.",
      img: "https://alkhidmat.org/backend/images/modules/home/slider/175160454768675d4370f58.jpg"
    },
    {
      title: "Mobile Health Unit",
      desc: "Healthcare on wheels—reaching underserved areas with essential medical services, diagnostics, and emergency care.",
      img: "https://alkhidmat.org/backend/images/modules/home/slider/175160471568675debc70f4.jpg"
    },
    {
      title: "Submersible Water Pump",
      desc: "Providing deep water access to drought-hit regions—ensuring clean, safe drinking water for families in need.",
      img: "https://alkhidmat.org/backend/images/modules/home/slider/175160492968675ec15cdeb.jpg"
    },
    {
      title: "Submersible Water Pump 1",
      desc: "Providing deep water access to drought-hit regions—ensuring clean, safe drinking water for families in need.",
      img: "https://alkhidmat.org/backend/images/modules/home/slider/169147598764d1e0137b4dd.jpg"
    }
  ];
   return (
        <div className="home-template">
          <Head>
            <title>Home Page</title>
            <meta name="description" content="Your page description" />
            </Head>
            
            <section className="hero-slider-section">
                <Swiper
                    modules={[Pagination, Autoplay]}
                    effect="fade"
                    
                    pagination={{ clickable: true }}
                    autoplay={{ delay: 5000 }}
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
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381096864f58118b25fe.svg" />
                              <div className="donation-content">
                                <h4>24,640,000</h4>
                                <p>Lives Impacted</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381080764f58077d2fab.svg" />
                              <div className="donation-content">
                                <h4>281,240</h4>
                                <p>Food Packs</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381077464f58056eeb41.svg" />
                              <div className="donation-content">
                                <h4>280,846</h4>
                                <p>Meat Packs Distributed</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381073964f58033e30d2.svg" />
                              <div className="donation-content">
                                <h4>25,809</h4>
                                <p>Water Projects</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381070564f580111bc66.svg" />
                              <div className="donation-content">
                                <h4>34,113</h4>
                                <p>Orphans Sponsored</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381066664f57feab775e.svg" />
                              <div className="donation-content">
                                <h4>24</h4>
                                <p>Aghosh Homes</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381062064f57fbc67017.svg" />
                              <div className="donation-content">
                                <h4>57</h4>
                                <p>Hospitals</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381058764f57f9b58d94.svg" />
                              <div className="donation-content">
                                <h4>296</h4>
                                <p>Ambulances</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381054964f57f75adae1.svg" />
                              <div className="donation-content">
                                <h4>80,000</h4>
                                <p>Volunteers</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381054964f57f75adae1.svg" />
                              <div className="donation-content">
                                <h4>866,000,000</h4>
                                <p>Loan Portfolio (PKR)</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381054964f57f75adae1.svg" />
                              <div className="donation-content">
                                <h4>1,569</h4>
                                <p>Alkhidmat Academic Scholarships</p>
                              </div>
                          </div>
                          <div className="donation-card">
                              <img src="https://alkhidmat.org/backend/images/modules/home/area-of-working/169381011664f57dc40d3a1.svg" />
                              <div className="donation-content">
                                <h4>60</h4>
                                <p>Alkhidmat Schools</p>
                              </div>
                          </div>
                     </div>
                </div>
                </div>
            </section>
            <section className="whoWeAre">
                <div className="container">
                    <h2>Who We Are</h2>
                    <div className="whowearecontent">
                        <p>We are a network of community leaders – made up of committed volunteers, donors, members of media and civil society, partners and staff – endeavouring together to make a difference in people and communities’ lives.</p>
                        <p>We seek to improve the lives of some of the world’s poorest and most vulnerable people especially orphan and street children, widows and unemployed men and women through relief, development and community work.</p>
                        <p>We do our best to provide solutions to serious human problems such as poverty, hunger, unemployment, orphanage and widowhood through our sustainable development projects.</p>
                    </div>
                </div>
            </section>
            <section className="help-card-wraper mt-10">    
                <div className="container">
                     <div className="helpWrapper">
                     <a className="help-card">
                        <div className="imgwrap">
                            <img src="https://alkhidmat.org/_next/image?url=https%3A%2F%2Falkhidmat.org%2Fbackend%2Fimages%2Fmodules%2Fhome%2Fwho-we-are%2F1770803624698c51a84e95b.png&w=640&q=75" alt="" />
                            <h5>Disaster Management</h5>
                        </div>
                     </a>
                     <a className="help-card">
                        <div className="imgwrap">
                            <img src="https://alkhidmat.org/_next/image?url=https%3A%2F%2Falkhidmat.org%2Fbackend%2Fimages%2Fmodules%2Fhome%2Fwho-we-are%2F1770803609698c51990112c.png&w=640&q=75" alt="" />
                            <h5>Health Services</h5>
                        </div>
                     </a>
                     <a className="help-card">
                        <div className="imgwrap">
                            <img src="https://alkhidmat.org/_next/image?url=https%3A%2F%2Falkhidmat.org%2Fbackend%2Fimages%2Fmodules%2Fhome%2Fwho-we-are%2F1770803579698c517b7f429.png&w=640&q=75" alt="" />
                            <h5>Education Program</h5>
                        </div>
                     </a>
                     <a className="help-card">
                        <div className="imgwrap">
                            <img src="https://alkhidmat.org/_next/image?url=https%3A%2F%2Falkhidmat.org%2Fbackend%2Fimages%2Fmodules%2Fhome%2Fwho-we-are%2F1770803563698c516ba69e8.png&w=640&q=75" alt="" />
                            <h5>WASH Program</h5>
                        </div>
                     </a>
                     <a className="help-card">
                        <div className="imgwrap">
                            <img src="https://alkhidmat.org/_next/image?url=https%3A%2F%2Falkhidmat.org%2Fbackend%2Fimages%2Fmodules%2Fhome%2Fwho-we-are%2F1770803549698c515d123bf.png&w=640&q=75" alt="" />
                            <h5>Orphan Care Program</h5>
                        </div>
                     </a>
                     <a className="help-card">
                        <div className="imgwrap">
                            <img src="https://alkhidmat.org/_next/image?url=https%3A%2F%2Falkhidmat.org%2Fbackend%2Fimages%2Fmodules%2Fhome%2Fwho-we-are%2F1770803438698c50eeba516.png&w=640&q=75" alt="" />
                            <h5>Islamic Microfinance</h5>
                        </div>
                     </a>
                     <a className="help-card">
                        <div className="imgwrap">
                            <img src="https://alkhidmat.org/_next/image?url=https%3A%2F%2Falkhidmat.org%2Fbackend%2Fimages%2Fmodules%2Fhome%2Fwho-we-are%2F1770803455698c50ff7e805.png&w=640&q=75" alt="" />
                            <h5>Islamic Microfinance</h5>
                        </div>
                     </a>
                     </div>
                </div>
            </section>
           <section className="volunteer">
            <div className="volunteer-container container">
         <div className="content-side">
        <h2 className="title">Our Volunteers, Our Hero</h2>
        <p className="description">
          Volunteers are very interesting people who have passion to serve humanity 
          without asking for any favor in return. They do not necessarily have extra 
          time, yet the passion that drives them to attentively be involved in 
          humanitarian work inspires and impacts millions of lives around the world. 
          Volunteering is not only about giving back to the community, but it is also a 
          great way to learn new skills, meet new people, and gain valuable 
          experiences that can benefit both personal and professional development.
        </p>
        <button className="volunteer-btn">
          Become a Volunteer <span className="arrow">→</span>
        </button>
      </div>

      <div className="image-side">
        {/* Is div mein aap apni image ya image collage set kar sakte hain */}
        <div className="image-wrapper">
           <img 
            src="/assets/images/home/volunteer-sec.webp" 
            alt="Volunteers helping people" 
            className="main-image" 
          />
        </div>
      </div>
      </div>
    </section>
    <section className="featured-section">
        <div className="container">
      <div className="header-text">
        <h2>Featured Causes</h2>
        <p>This year has been like no other. We've carried out a record-breaking number of relief activities</p>
      </div>

      <div className="causes-grid">
        {causes.map((cause) => (
          <div key={cause.id} className="cause-card">
            <div className="image-container">
              <img src={cause.image} alt={cause.title} />
            </div>
            <div className="card-content">
              <h3>{cause.title}</h3>
              <p>{cause.desc}</p>
              <button className="donate-btn">DONATE NOW</button>
            </div>
          </div>
        ))}
      </div>
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
                  <img src="https://placehold.co/400x225/000000/ffffff?text=LIVE+STREAM" alt="Live" class="w-full max-w-full" />
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
        <h2 className="gi-section-title">Get Involved</h2>
        
        <div className="gi-grid">
          {cards.map((card, index) => (
            <div key={index} className="gi-card">
              <div className="gi-card__content">
                <h3 className="gi-card__title">{card.title}</h3>
                <p className="gi-card__description">{card.text}</p>
              </div>
              <a href={card.link} className="gi-card__button">
                {card.btnText}
              </a>
            </div>
          ))}
        </div>
      </div>
    </section>
<section className="soa-section">
      <div className="container">
        <div className="soa-header">
          <h2 className="soa-title">State of the Arts Project</h2>
          <p className="soa-subtitle">
            Alkhidmat Foundation Pakistan is one of the leading, non-profit organization, fully dedicated to humanitarian services since 1990. Alkhidmat's workers and volunteers continue to work tirelessly.
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
          {projects.map((item, index) => (
            <SwiperSlide key={index} className="soa-slide">
              <div className="soa-card">
                <img src={item.img} alt={item.title} className="soa-card-img" />
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
      </div>
    </section>
        </div>
    );
};

export default HomeLayout;