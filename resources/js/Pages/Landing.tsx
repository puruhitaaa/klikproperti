import Hero from '@/Components/landing/Hero';
import MostSearched from '@/Components/landing/MostSearched';
import Newsletter from '@/Components/landing/Newsletter';
import PopularProperties from '@/Components/landing/PopularProperties';
import Stats from '@/Components/landing/Stats';
import ProductLayout from '@/Layouts/ProductLayout';
import type { CompleteProperty, Location } from '@/types';
import { Head } from '@inertiajs/react';

type LandingPageProps = {
    popularProperties: CompleteProperty[];
    mostSearchedLocations: Location[];
};

export default function LandingPage({
    popularProperties,
    mostSearchedLocations,
}: LandingPageProps) {
    return (
        <>
            <Head title="Landing" />
            <ProductLayout>
                <div className="flex flex-col min-h-screen">
                    <main className="flex-1">
                        {/* Hero Section */}
                        <Hero />

                        {/* Most Searched Locations */}
                        <MostSearched
                            mostSearchedLocations={mostSearchedLocations}
                        />

                        {/* Stats Section */}
                        <Stats />

                        {/* Popular Properties */}
                        <PopularProperties properties={popularProperties} />

                        {/* Newsletter */}
                        <Newsletter />
                    </main>
                </div>
            </ProductLayout>
        </>
    );
}
