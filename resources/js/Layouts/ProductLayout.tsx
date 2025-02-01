import Footer from '@/Components/Footer';
import Header from '@/Components/Header';
import { ThemeProvider } from '@/Components/ThemeProvider';
import type { ReactNode } from 'react';

interface ProductLayoutProps {
    children: ReactNode;
}

export default function ProductLayout({ children }: ProductLayoutProps) {
    return (
        <ThemeProvider>
            <div className="flex flex-col min-h-screen">
                <Header />
                <main>{children}</main>
                <Footer />
            </div>
        </ThemeProvider>
    );
}
