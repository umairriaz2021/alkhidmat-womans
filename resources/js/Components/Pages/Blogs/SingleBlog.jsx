import React from 'react';
import '@/Components/Pages/Blogs/css/blog.css';
import { Link } from '@inertiajs/react';

const SingleBlog = ({ page,data }) => {
   console.log('categories',data);
    return (

        <div className="akf-blog-page-wrapper">

          

            {/* =========================
                HERO SECTION
            ========================== */}

            <section className="akf-blog-hero-section">
              
                <img
                    src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1600&auto=format&fit=crop"
                    alt="Flood Relief"
                    className="akf-blog-hero-image"
                />

                <div className="akf-blog-hero-overlay"></div>

                <div className="akf-blog-container">

                    <div className="akf-blog-hero-content">

                        <h1 className="akf-blog-main-title">
                            {page.title}
                        </h1>

                    </div>

                </div>

            </section>
              {/* =========================
                BREADCRUMBS
            ========================== */}

            <div className="akf-blog-breadcrumb-main-wrap">

                <div className="akf-blog-container">

                    <div className="akf-blog-breadcrumb-row">

                        <Link href="/blogs">
                            Blogs
                        </Link>
                        <span>/</span>

                        <p>
                            {page.title}
                        </p>

                    </div>

                </div>

            </div>        
            {/* =========================
                MAIN CONTENT
            ========================== */}

            <section className="akf-blog-main-section">

                <div className="akf-blog-container">

                    <div className="akf-blog-layout-grid">

                        {/* LEFT CONTENT */}

                        <div className="akf-blog-article-content" >
                            <div className="akf-blogContent" dangerouslySetInnerHTML={{ __html: page.content }}>
                            
                            </div>
                            {/* CTA */}

                            <div className="akf-blog-donation-box">

                                <h3>
                                    Support Flood Victims
                                </h3>

                                <p>
                                    Your donation can provide food, shelter, clean water, and emergency support to affected families.
                                </p>

                                <Link href="/donations">
                                    Donate Now
                                </Link>

                            </div>

                        </div>

                        {/* SIDEBAR */}

                        <aside className="akf-blog-sidebar-wrapper">

                            <div className="akf-blog-sidebar-card">

                                <h3>
                                    Recent Posts
                                </h3>
                                  {data.posts.data.length > 0 && data.posts.data.map((post) => (
                                  <>
                                   <div key={post.id} className="akf-blog-recent-post-item">
                                  
                                    
                                     <img
                                        src={`/storage/${post.profile_image.file_path}`}
                                        alt={post.title}
                                    />

                                    <div>
                                        <h4>
                                            {post.title}
                                        </h4>
                                    </div>
                                        
                                    
                                    

                                </div>
                                 </> 
                                 ))}           
                              

                            </div>

                            <div className="akf-blog-sidebar-card">

                                <h3>
                                    Categories
                                </h3>

                                <ul className="akf-blog-category-list">
                                  {data.categories.length > 0 && data.categories.map((category) => (
                                    <li key={category.id}><Link href={`/${category.slug}`}>{category.name}</Link></li>
                                  ))}

                                </ul>

                            </div>

                        </aside>

                    </div>

                </div>

            </section>

        </div>

    );

};

export default SingleBlog;