import ProductLayout from '@/Layouts/ProductLayout';
import { Head } from '@inertiajs/react';
import { Building, Shield, Target, Users } from 'lucide-react';

const values = [
    {
        title: 'Trust & Transparency',
        description:
            'We believe in complete transparency in every transaction. Our platform ensures that all property listings are verified and authentic.',
        icon: Shield,
    },
    {
        title: 'Customer First',
        description:
            'Your satisfaction is our priority. We strive to provide the best possible experience in your property journey.',
        icon: Users,
    },
    {
        title: 'Innovation',
        description:
            'We continuously improve our platform with cutting-edge technology to make property transactions simpler and more efficient.',
        icon: Target,
    },
    {
        title: 'Market Expertise',
        description:
            'Our team brings years of real estate experience, ensuring you get the most accurate and reliable property services.',
        icon: Building,
    },
];

export default function AboutUs() {
    return (
        <ProductLayout>
            <Head title="About Us" />

            <div className="min-h-screen">
                <div className="relative isolate overflow-hidden bg-[url('/images/about-us/hero.webp')] bg-cover bg-center before:absolute before:inset-0 before:z-10 before:bg-gradient-to-b before:from-transparent before:to-black/50">
                    <div className="relative z-20 mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                        <div className="mx-auto max-w-2xl lg:mx-0">
                            <h1 className="text-4xl font-bold tracking-tight sm:text-6xl">
                                About KlikProperti
                            </h1>
                            <p className="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-400">
                                We're revolutionizing the way people buy, sell,
                                and rent properties in Indonesia. Our platform
                                combines technology with real estate expertise
                                to make property transactions simple,
                                transparent, and efficient.
                            </p>
                        </div>
                    </div>
                </div>

                <div className="mx-auto max-w-7xl px-6 py-16 sm:py-24 lg:px-8">
                    <div className="mx-auto max-w-2xl lg:text-center">
                        <h2 className="text-base font-semibold leading-7 text-primary">
                            Our Mission
                        </h2>
                        <p className="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                            Making Property Transactions Simple and Accessible
                        </p>
                        <p className="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-400">
                            Our mission is to democratize access to property
                            transactions, making it easier for everyone to find
                            their dream home or investment opportunity. We
                            believe that property transactions should be
                            straightforward, transparent, and stress-free.
                        </p>
                    </div>
                </div>

                <div className="mx-auto max-w-7xl px-6 py-16 sm:py-24 lg:px-8">
                    <div className="mx-auto max-w-2xl lg:text-center">
                        <h2 className="text-base font-semibold leading-7 text-primary">
                            Our Values
                        </h2>
                        <p className="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                            What Drives Us Forward
                        </p>
                    </div>
                    <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                        <dl className="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-2">
                            {values.map((value) => (
                                <div
                                    key={value.title}
                                    className="flex flex-col"
                                >
                                    <dt className="flex items-center gap-x-3 text-base font-semibold leading-7">
                                        <value.icon
                                            className="h-5 w-5 flex-none text-primary"
                                            aria-hidden="true"
                                        />
                                        {value.title}
                                    </dt>
                                    <dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-slate-600 dark:text-slate-400">
                                        <p className="flex-auto">
                                            {value.description}
                                        </p>
                                    </dd>
                                </div>
                            ))}
                        </dl>
                    </div>
                </div>

                <div className="mx-auto max-w-7xl px-6 py-16 sm:py-24 lg:px-8">
                    <div className="mx-auto max-w-2xl lg:text-center">
                        <h2 className="text-base font-semibold leading-7 text-primary">
                            Our Team
                        </h2>
                        <p className="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                            Dedicated to Your Success
                        </p>
                        <p className="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-400">
                            Our team consists of experienced real estate
                            professionals, tech experts, and customer service
                            specialists who work together to provide you with
                            the best possible property platform experience.
                        </p>
                    </div>
                </div>
            </div>
        </ProductLayout>
    );
}
