'use client';

import { type Location } from '@/types';
import { motion } from 'framer-motion';
import { LocationCard } from '../locations/LocationCard';
interface Props {
    mostSearchedLocations: Location[];
}

const MostSearched = ({ mostSearchedLocations }: Props) => {
    return (
        <section className="px-4 py-16 lg:px-6">
            <div className="container mx-auto">
                <motion.h2
                    className="text-2xl font-bold md:text-3xl"
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8 }}
                >
                    Most Searched Locations
                </motion.h2>
                <motion.p
                    className="mt-2 text-muted-foreground"
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8, delay: 0.2 }}
                >
                    Very strategic location | has very adequate facilities |
                    suitable for families with a modern home look
                </motion.p>
                <div className="grid gap-8 mt-8 sm:grid-cols-2 lg:grid-cols-3">
                    {mostSearchedLocations.map((location, index) => (
                        <motion.div
                            key={location.city}
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{
                                duration: 0.8,
                                delay: 0.2 + index * 0.1,
                            }}
                        >
                            <LocationCard {...location} />
                        </motion.div>
                    ))}
                </div>
            </div>
        </section>
    );
};

export default MostSearched;
