import React from 'react'
import Breadcrumb from '@/Components/Pages/aboutPage/breadcrumbs/BreadCrumb';
import AboutData from '@/static-data/about/aboutData.json';
const DonationLayout = ({page}) => {
console.log(page.template.template_name);
    const crumbName = page.template.template_name === 'donations_layout' ? {
         page:page.title,
         title:"appeals",
         dynamic_page:"" 
    } : "";  
    console.log(crumbName)
  return (
    <div className="ap-main-wrapper">
      {/* Introduction Section */}
      <div className="ap-intro-section">
        <div className="ap-intro-content">
          {AboutData && 
           <>
           <h1 className="ap-main-title">{AboutData.first_sec.title}</h1>
          <p className="ap-main-desc">
            {AboutData.first_sec.description}
          </p>
           </>
          }
          
        </div>
      </div>
      
      {/* Bottom Breadcrumb Bar */}
     <Breadcrumb crum={crumbName} />
      {/* Cards Grid Section */}
      <div className="ap-cards-container">
        <div className="ap-cards-grid">
          {AboutData && AboutData.second_sec.data.length > 0 && AboutData.second_sec.data.map((card, index) => (
            <div key={index} className="ap-info-card">
              {card.img &&
              <div className="ap-card-image-box">
                <img src={`assets/images/about${card.img}.jpg`} alt={card.name} />
              </div>
              }
              <div className="ap-card-body">
                <h3 className="ap-card-title">{card.name}</h3>
                <button onClick={() => window.location.href(card.slug)} className="ap-learn-more-btn">
                  Learn More <span className="ap-arrow">→</span>
                </button>
              </div>
            </div>
          ))}
        </div>
      </div>

    </div>
  );
};

export default DonationLayout;