'use client';

import { motion } from 'framer-motion';
import { Button } from '../ui/button';
import { Input } from '../ui/input';

const Newsletter = () => {
    return (
        <section className="px-4 py-16 bg-secondary lg:px-8">
            <div className="container mx-auto text-center">
                <motion.h2
                    className="text-2xl font-bold md:text-3xl"
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8 }}
                >
                    Subscribe Our Notification,
                    <br />
                    News & Blog
                </motion.h2>
                <motion.div
                    className="flex max-w-md gap-4 mx-auto mt-8"
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8, delay: 0.2 }}
                >
                    <Input
                        className="border border-primary"
                        placeholder="your@email.com"
                    />
                    <Button>Get Started</Button>
                </motion.div>
            </div>
        </section>
    );
};

export default Newsletter;
