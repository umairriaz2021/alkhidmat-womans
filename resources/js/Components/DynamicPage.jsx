import React from 'react';
import MainLayout from '@/Layouts/MainLayout'; // Header/Footer yahan hain
import HomeLayout from '@/Components/Pages/HomeLayout';
import AboutLayout from '@/Components/Pages/AboutLayout';
import DonationLayout from './Pages/DonationLayout';
import SingleBlog from '@/Components/Pages/Blogs/SingleBlog';
import Blogs from '@/Components/Pages/Blogs/Blogs';
import DonationPage from './Pages/Donations/Donation';
import DonationSummary from './Pages/Donations/DonationSummary';

const DynamicPage = (props) => {
    const { page, template_name } = props;
     console.log(props);
    // Template Mapping
    const components = {
        'home_layout': HomeLayout,
        'about_layout': AboutLayout,
        'donations_layout':DonationLayout,
        'single_blog_page': SingleBlog,
        'blog_template':Blogs,
        'donation_page':DonationPage,
        'donation_summary':DonationSummary
    };

    const SelectedLayout = components[template_name] || HomeLayout;

    return <SelectedLayout {...props} />;
};

export default DynamicPage;