'use client';

import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';

const Footer = () => {
    return (
        <footer className="px-4 py-6 border-t md:px-6">
            <motion.div
                className="container flex flex-col items-center w-full gap-2 mx-auto shrink-0 sm:flex-row"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 0.8 }}
            >
                <p className="text-xs text-neutral-500 dark:text-neutral-400">
                    © 2025 KlikProperti. All rights reserved.
                </p>
                <nav className="flex gap-4 sm:ml-auto sm:gap-6">
                    <Link
                        className="text-xs underline-offset-4 hover:underline"
                        href="/terms-of-service"
                    >
                        Terms of Service
                    </Link>
                    <Link
                        className="text-xs underline-offset-4 hover:underline"
                        href="/privacy-policy"
                    >
                        Privacy Policy
                    </Link>
                </nav>
            </motion.div>
        </footer>
    );
};

export default Footer;
