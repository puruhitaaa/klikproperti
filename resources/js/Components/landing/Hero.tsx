'use client';

import { motion } from 'framer-motion';
import { SearchForm } from '../locations/SearchForm';

const Hero = () => {
    return (
        <section className="bg-secondary relative h-[75vh] px-4 py-16 lg:px-6">
            <div className="container h-full mx-auto">
                <div className="flex items-center h-full">
                    <div className="z-10 w-full lg:max-w-4xl">
                        <motion.h1
                            className="text-4xl font-bold tracking-tight text-secondary-foreground md:text-5xl lg:text-6xl"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8 }}
                        >
                            Everyone can
                            <br />
                            own a house.
                            <br />
                            <span className="text-primary">
                                Only with KlikProperti
                            </span>
                        </motion.h1>
                        <motion.p
                            className="mt-6 text-lg text-muted-foreground lg:max-w-xl"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8, delay: 0.2 }}
                        >
                            Find comfort in the house with us, want to find a
                            home? we are ready to help you wholeheartedly based
                            on what you need
                        </motion.p>
                        <motion.div
                            className="mt-8"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8, delay: 0.4 }}
                        >
                            <SearchForm />
                        </motion.div>
                    </div>
                </div>
            </div>

            <motion.div
                className="absolute bottom-0 right-0 z-0 hidden lg:block"
                initial={{ opacity: 0, x: 50 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.8, delay: 0.6 }}
            >
                <img
                    src="/images/hero-image.png"
                    alt="Modern building"
                    width={600}
                    height={600}
                    className="scale-x-[-1] rounded-lg object-cover"
                />
            </motion.div>
        </section>
    );
};

export default Hero;
