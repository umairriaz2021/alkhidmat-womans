import React from 'react';
import MainLayout from '@/Layouts/MainLayout'; // Header/Footer yahan hain
import HomeLayout from '@/Components/Pages/HomeLayout';
import AboutLayout from '@/Components/Pages/AboutLayout';

const DynamicPage = (props) => {
    const { page, template_name } = props;

    // Template Mapping
    const components = {
        'home_layout': HomeLayout,
        'about_layout': AboutLayout,
    };

    const SelectedLayout = components[template_name] || HomeLayout;

    return <SelectedLayout {...props} />;
};

export default DynamicPage;