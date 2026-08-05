import React, { useState } from 'react';
import './VerticalTabs.css'; // Styling file

export default function VerticalTabs ({ data = [] }) {
  // Safe fallbacks agar data empty ho
  const initialActiveTab = data.length > 0 ? data[0].id : '';
  const [activeTab, setActiveTab] = useState(initialActiveTab);

  if (!data || data.length === 0) {
    return <div className="ak-vtab-empty">No tab data available.</div>;
  }

  // Active tab ka object evaluate karna
  const currentTab = data.find((tab) => tab.id === activeTab) || data[0];

  return (
    <div className="ak-vtab-wrapper">
      {/* Left Navigation Tabs */}
      <div className="ak-vtab-nav">
        {data.map((tab) => (
          <button
            key={tab.id}
            type="button"
            className={`ak-vtab-btn ${activeTab === tab.id ? 'ak-active' : ''}`}
            onClick={() => setActiveTab(tab.id)}
          >
            {tab.label}
          </button>
        ))}
      </div>

      {/* Right Content Panel */}
      <div className="ak-vtab-content-container">
        <div key={currentTab.id} className="ak-vtab-pane ak-active text-left">
          

          {/* Paragraphs loop render */}
          {Array.isArray(currentTab.content) ? (
            currentTab.content.map((paragraph, index) => (
               <div key={index}
                        className="ak-vtab-text flex flex-col gap-4" 
                        dangerouslySetInnerHTML={{ __html: paragraph }}
                    ></div>
              
            ))
          ) : (
            <p className="ak-vtab-text">{currentTab.content}</p>
          )}
        </div>
      </div>
    </div>
  );
};
