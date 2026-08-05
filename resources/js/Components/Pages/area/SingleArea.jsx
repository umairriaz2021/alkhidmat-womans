import React from 'react';
import { Link } from '@inertiajs/react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import CommunityTabs from '@/static-data/community-services/tabs.json';
import HealthServices from '@/static-data/health-services/metalic_disorder.json';
import EducationServices from '@/static-data/education/data.json';
import StorySection from '@/Components/Commons/StorySection';
import VerticalTabs from '@/Components/Commons/VerticalTabs';
import MetabolicSection from '@/Components/Commons/MetalicSection';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import '@/Components/Pages/Blogs/css/blog.css';
import '@/Components/Pages/area/css/area.css';
import '@/Components/Commons/VerticalTabs.css';
import '@/Components/Commons/MetalicStyle.css';

const getSanitizedKey = (titleStr) => {
  if (typeof titleStr !== 'string' || !titleStr.trim()) return null;
  return titleStr
    .trim()
    .toLowerCase()
    .replace(/\s+/g, '_');
};

const getMatchedHealthData = (titleKey) => {
  if (!HealthServices || typeof HealthServices !== 'object' || !titleKey) {
    return null;
  }
  return HealthServices[titleKey] || null;
};

// Education JSON Data safe fetcher
const getMatchedEducationData = (titleKey) => {
  if (!EducationServices || typeof EducationServices !== 'object') return null;

  
  // 2. Title key match check
  if (titleKey && EducationServices[titleKey]) {
    return EducationServices[titleKey];
  }

  return null;
};

const SingleArea = ({ page, data }) => {
  const titleKey = getSanitizedKey(page?.title);

  // Tabs Data Match
  const tabData =
    titleKey && typeof CommunityTabs === 'object' && CommunityTabs !== null
      ? CommunityTabs[titleKey]
      : null;
    
  // Health Section Data Match
  const matchedContent = getMatchedHealthData(titleKey);

  // Education Section Data Match (Check via slug or title)
  const matchedEducationContent = getMatchedEducationData(titleKey);
  console.log('education_data',EducationServices);

  let our_services = [];
  try {
    our_services =
      typeof page?.our_services === 'string'
        ? JSON.parse(page.our_services)
        : page?.our_services || [];
  } catch (e) {
    console.error('Services JSON parsing error:', e);
  }

  // Safe JSON Parsing for Gallery
  let galleryImages = [];
  try {
    galleryImages =
      typeof page?.gallery === 'string'
        ? JSON.parse(page.gallery)
        : page?.gallery || [];
  } catch (e) {
    console.error('Gallery JSON parsing error:', e);
  }

  // Helper Function to Fix Image Absolute Paths
  const getImageUrl = (path) => {
    if (!path) return '';
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    const cleanPath = path.replace(/^\/+/, '');
    return `/${cleanPath}`;
  };

  return (
    <div className="akf-blog-page-wrapper">
      {/* Hero Section */}
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
            <Link href="/area-of-work">Area of Work</Link>
            <span>/</span>
            <p>{page?.title}</p>
          </div>
        </div>
      </div>

      {/* Services Section */}
      <div className="akd-services-container">
        <h2 className="akd-services-title">
          Our <span>Services</span>
        </h2>
        <div className="akd-services-row">
          {our_services.length > 0 &&
            our_services.map((service, index) => (
              <div key={index} className="akd-pills-group">
                <div className="akd-service-pill">
                  {service.service_title}{' '}
                  <strong>{service.service_cost}</strong>
                </div>
              </div>
            ))}
        </div>
        <a href="#" className="akd-donate-btn-red mt-6">
          Donate Now
        </a>
      </div>

      {/* Area Content Section */}
      <section className="akd-dm-section-wrapper">
        <div className="akd-dm-container">
          <h2 className="akd-dm-heading">{page?.area_heading}</h2>
          {(() => {
            const tabsDataArray = Array.isArray(tabData)
              ? tabData
              : tabData && typeof tabData === 'object'
              ? Object.values(tabData).find(
                  (val) => Array.isArray(val) && val.length > 0
                )
              : null;

            return tabsDataArray && tabsDataArray.length > 0 ? (
              <VerticalTabs data={tabsDataArray} />
            ) : (
              <div
                className="akd-dm-paragraph"
                dangerouslySetInnerHTML={{ __html: page?.area_content }}
              ></div>
            );
          })()}
        </div>
      </section>

      {/* Banner V2 Section */}
      <section className="akd-banner-v2-section">
        <div className="akd-banner-v2-container">
          <div className="akd-banner-v2-quote-icon">“</div>
          <div
            className="akd-banner-v2-text"
            dangerouslySetInnerHTML={{ __html: page?.area_quote }}
          ></div>
          <a href="#" className="akd-banner-v2-btn">
            <span>Donate Now</span>
            <svg viewBox="0 0 24 24">
              <path
                d="M5 12h14M12 5l7 7-7 7"
                stroke="currentColor"
                strokeWidth="2.5"
                strokeLinecap="round"
                strokeLinejoin="round"
              />
            </svg>
          </a>
        </div>
      </section>

      {/* Dynamic Conditional Sections */}
      
      {/* 1. Health Services Section */}
      {matchedContent && <MetabolicSection contentData={matchedContent} />}

      {/* 2. Education Story Section */}
      {(page?.slug === 'education' || matchedEducationContent) && (
        <StorySection data={matchedEducationContent} />
      )}

      {/* Photo Gallery Section - 4 Slides Per Row */}
      <section className="custom-gallery-wrapper">
        <div className="custom-gallery-header">
          <h2 className="akd-services-title">
            Photo <span>Gallery</span>
          </h2>

          <div className="custom-gallery-arrows">
            <div className="custom-gallery-prev-btn">
              <i className="fas fa-arrow-left"></i>
            </div>
            <div className="custom-gallery-next-btn">
              <i className="fas fa-arrow-right"></i>
            </div>
          </div>
        </div>

        <Swiper
          modules={[Navigation, Pagination, Autoplay]}
          slidesPerView={4}
          spaceBetween={20}
          loop={true}
          autoplay={{ delay: 3500, disableOnInteraction: false }}
          navigation={{
            nextEl: '.custom-gallery-next-btn',
            prevEl: '.custom-gallery-prev-btn',
          }}
          pagination={{
            el: '.custom-gallery-pagination',
            clickable: true,
          }}
          breakpoints={{
            0: { slidesPerView: 1, spaceBetween: 10 },
            576: { slidesPerView: 2, spaceBetween: 15 },
            850: { slidesPerView: 3, spaceBetween: 15 },
            1100: { slidesPerView: 4, spaceBetween: 20 },
          }}
          className="custom-gallery-slider"
        >
          {galleryImages.map((imgPath, index) => (
            <SwiperSlide key={index} className="custom-gallery-slide">
              <img
                src={getImageUrl(imgPath)}
                className="custom-gallery-img"
                alt={`Gallery Image ${index + 1}`}
              />
              <div className="custom-gallery-zoom-icon">
                <i className="fas fa-expand"></i>
              </div>
              <div className="custom-gallery-overlay">
                <span className="custom-gallery-tag">Media Gallery</span>
                <div className="custom-gallery-card-title">
                  Photo {index + 1}
                </div>
              </div>
            </SwiperSlide>
          ))}

          <div className="swiper-pagination custom-gallery-pagination"></div>
        </Swiper>
      </section>
    </div>
  );
};

export default SingleArea;