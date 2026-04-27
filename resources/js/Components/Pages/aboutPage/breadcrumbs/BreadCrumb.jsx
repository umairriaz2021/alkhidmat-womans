import React from "react";

const Breadcrumb = ({crum}) => {
    return (
        <>
        <div className="ap-breadcrumb-bar">
        <div className="ap-breadcrumb-inner">
          <span className="ap-bc-link">{crum.page}</span>
          <span className="ap-bc-sep">/</span>
          <span className="ap-bc-current">{crum.title}</span>
          {crum.dynamic_page && 
          <span className="ap-bc-current">{crum.dynamic_page}</span>
          }
        </div>
      </div>
        </>
    )
}

export default Breadcrumb;