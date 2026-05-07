import React from 'react';
import Breadcrumb from '@/Components/Pages/aboutPage/breadcrumbs/BreadCrumb';
import {router} from '@inertiajs/react';

const Blogs = ({page,data}) => {
     
    const crumbName = {
        page: page.title,
        title: "",
        dynamic_page: ""
    };
   
    const blogData = [
        {
            id: 1,
            title: "Helping Communities Through Donations",
            image: "/assets/images/blog/blog-1.jpg",
            slug: "/blog/helping-communities"
        },
        {
            id: 2,
            title: "Food Drive Campaign 2026",
            image: "/assets/images/blog/blog-2.jpg",
            slug: "/blog/food-drive-campaign"
        },
        {
            id: 3,
            title: "Education Support For Children",
            image: "/assets/images/blog/blog-3.jpg",
            slug: "/blog/education-support"
        },
        {
            id: 4,
            title: "Healthcare Support Program",
            image: "/assets/images/blog/blog-4.jpg",
            slug: "/blog/healthcare-support"
        }
    ];

    return (
        <div className="ap-main-wrapper">

            {/* Intro Section */}
            <div className="ap-intro-section">
                <div className="ap-intro-content">

                    <h1 className="ap-main-title">
                        Our Latest Blogs
                    </h1>

                    <p className="ap-main-desc">
                        Explore our latest news, donation campaigns, and community support
                        initiatives designed to make a positive impact around the world.
                    </p>

                </div>
            </div>

            {/* Breadcrumb */}
            <Breadcrumb crum={crumbName} />

            {/* Blog Cards */}
            <div className="ap-cards-container">
                <div className="ap-cards-grid">

                 {data.posts.length > 0 && data.posts.map((d) => (
                        <>
                        {console.log('check',d)}
                        
                        <div key={d.id} className="ap-info-card">

                            <div className="ap-card-image-box">
                                {d.profile_image !== null ? 
                                <img src={`storage/${d.profile_image.file_path}`} alt={d.title} />
                                 : <img src="https://picsum.photos/id/237/200/300" />
                                }
                            </div>

                            <div className="ap-card-body">

                                <h3 className="ap-card-title">
                                    {d.title}
                                </h3>

                                <button
                                    onClick={() => router.visit(`/${data.slug}/${d.slug}`)}
    className="ap-learn-more-btn"
                                >
                                    Read More
                                    <span className="ap-arrow">→</span>
                                </button>

                            </div>

                        </div>
                        </>
                    ))}
                </div>
            </div>

        </div>
    );
};

export default Blogs;