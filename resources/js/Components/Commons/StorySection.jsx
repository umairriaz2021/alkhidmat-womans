import React from 'react';
import './StorySection.css'

const StorySection = ({ data }) => {
  if (!data) return null;

  const { quote, author, imageSrc, imageAlt } = data;

  return (
    <section className="akd-story-section-wrapper">
      <div className="akd-story-container">
        
        {/* Left Column: Quote & Author */}
        <div className="akd-story-content-col">
          {quote && (
            <p className="akd-story-paragraph">
              {quote}
            </p>
          )}
          {author && (
            <div className="akd-story-author-meta mt-5">
              {author}
            </div>
          )}
        </div>

        {/* Right Column: Image */}
        {imageSrc && (
          <div className="akd-story-image-col">
            <div className="akd-story-image-card">
              <img 
               src={`/assets/images${imageSrc}`}
                alt={imageAlt || 'Story Image'} 
                className="akd-story-main-img" 
              />
            </div>
          </div>
        )}

      </div>
    </section>
  );
};

export default StorySection;