import React from 'react';
import MainLayout from '@/Layouts/MainLayout'; // Header/Footer yahan hain
import HomeLayout from '@/Components/Pages/HomeLayout';
import AboutLayout from '@/Components/Pages/AboutLayout';
import DonationLayout from './Pages/DonationLayout';

const DynamicPage = (props) => {
    const { page, template_name } = props;
     console.log(props);
    // Template Mapping
    const components = {
        'home_layout': HomeLayout,
        'about_layout': AboutLayout,
        'donations_layout':DonationLayout
    };

    const SelectedLayout = components[template_name] || HomeLayout;

    return <SelectedLayout {...props} />;
};

export default DynamicPage;