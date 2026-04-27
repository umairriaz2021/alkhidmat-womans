import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import MainLayout from '@/Layouts/MainLayout';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    // Isse ensure hota hai ke title template sahi hai
    title: (title) => title ? `${title} - ${appName}` : appName,
    
    resolve: async (name) => {
            
        const page = await resolvePageComponent(
            `./Components/${name}.jsx`,
            import.meta.glob('./Components/**/*.jsx')
        );

        // FIX: Layout assign karne ka sahi professional tarika
        if (page.default.layout === undefined && !name.startsWith('Admin/')) {
            page.default.layout = (page) => <MainLayout>{page}</MainLayout>;
        }
        
        return page;
    },
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#28a745', 
        showSpinner: true,
    },
});