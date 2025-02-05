import { Button } from '@/Components/ui/button';
import ProductLayout from '@/Layouts/ProductLayout';
import { Head, Link } from '@inertiajs/react';
import { Building2, Home, Scale } from 'lucide-react';

const services = [
    {
        title: 'Rent or Buy Property',
        description:
            'Find your dream home from our extensive collection of properties available for rent or purchase. We offer a wide range of options to suit your needs and budget.',
        icon: Home,
        link: '/listings',
    },
    {
        title: 'List Your Property',
        description:
            'Want to rent out or sell your property? List it on our platform to reach thousands of potential buyers and tenants. We make the process simple and efficient.',
        icon: Building2,
        link: '/listings/create',
    },
    {
        title: 'Property Appraisal',
        description:
            "Get an accurate estimate of your property's value with our advanced appraisal service. Our algorithm considers multiple factors to provide you with a fair market value.",
        icon: Scale,
        link: '/appraisal',
    },
];

export default function Services() {
    return (
        <ProductLayout>
            <Head title="Our Services" />

            <div className="min-h-screen py-16 sm:py-24">
                <div className="mx-auto max-w-7xl px-6 lg:px-8">
                    <div className="mx-auto max-w-2xl text-center">
                        <h2 className="text-3xl font-bold tracking-tight before:mx-auto before:mb-4 before:block before:h-1 before:w-16 before:bg-primary before:content-[''] sm:text-4xl">
                            Our Services
                        </h2>
                        <p className="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-400">
                            Discover how we can help you with your property
                            needs, whether you're looking to buy, rent, sell, or
                            get a property valuation.
                        </p>
                    </div>

                    <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                        <dl className="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                            {services.map((service) => (
                                <div
                                    key={service.title}
                                    className="flex flex-col"
                                >
                                    <dt className="flex items-center gap-x-3 text-base font-semibold leading-7">
                                        <service.icon
                                            className="h-5 w-5 flex-none text-primary"
                                            aria-hidden="true"
                                        />
                                        {service.title}
                                    </dt>
                                    <dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-slate-600 dark:text-slate-400">
                                        <p className="flex-auto">
                                            {service.description}
                                        </p>
                                        <Button asChild className="mt-6">
                                            <Link
                                                prefetch="hover"
                                                href={service.link}
                                                className="w-fit text-sm font-semibold leading-6 text-background dark:text-foreground"
                                            >
                                                Learn more{' '}
                                                <span aria-hidden="true">
                                                    →
                                                </span>
                                            </Link>
                                        </Button>
                                    </dd>
                                </div>
                            ))}
                        </dl>
                    </div>
                </div>
            </div>
        </ProductLayout>
    );
}
