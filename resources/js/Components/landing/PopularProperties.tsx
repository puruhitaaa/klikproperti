'use client';

import { PropertyCard } from '@/Components/locations/PropertyCard';
import { Button } from '@/Components/ui/button';
import type { CompleteProperty } from '@/types';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowRight, ChevronLeft, ChevronRight } from 'lucide-react';
import { useState } from 'react';

const PopularProperties = ({
    properties,
}: {
    properties: CompleteProperty[];
}) => {
    const [currentPage, setCurrentPage] = useState(0);
    const propertiesPerPage = 3;

    const totalPages = Math.ceil(properties.length / propertiesPerPage);

    const handleNext = () => {
        setCurrentPage((prev) => (prev + 1) % totalPages);
    };

    const handlePrev = () => {
        setCurrentPage((prev) => (prev - 1 + totalPages) % totalPages);
    };

    const displayedProperties = properties.slice(
        currentPage * propertiesPerPage,
        (currentPage + 1) * propertiesPerPage,
    );

    const showViewAllLink = currentPage === totalPages - 1;

    return (
        <section className="px-4 py-16 lg:px-8">
            <div className="container mx-auto">
                <motion.div
                    className="flex items-center justify-between"
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8 }}
                >
                    <div>
                        <h2 className="text-2xl font-bold md:text-3xl">
                            Popular Property Deals
                        </h2>
                        <p className="mt-2 text-muted-foreground">
                            Properties with the highest ratings from our users
                        </p>
                    </div>
                    <div className="flex gap-2">
                        <Button
                            variant="outline"
                            size="icon"
                            onClick={handlePrev}
                            disabled={currentPage === 0}
                        >
                            <ChevronLeft className="w-4 h-4" />
                        </Button>
                        <Button
                            variant="outline"
                            size="icon"
                            onClick={handleNext}
                            disabled={currentPage === totalPages - 1}
                        >
                            <ChevronRight className="w-4 h-4" />
                        </Button>
                    </div>
                </motion.div>
                <div className="grid gap-8 mt-8 sm:grid-cols-2 lg:grid-cols-3">
                    {displayedProperties.length ? (
                        [
                            ...displayedProperties,
                            ...(showViewAllLink ? [null] : []),
                        ].map((prop, idx) =>
                            prop === null ? (
                                <motion.div
                                    key="view-all"
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{
                                        duration: 0.8,
                                        delay: 0.2 + idx * 0.1,
                                    }}
                                >
                                    <Link
                                        href="/properties"
                                        className="relative flex items-center justify-center h-full p-4 transition-all duration-300 ease-in-out border-2 border-dashed rounded-md border-primary/30 hover:border-primary group"
                                    >
                                        <div className="text-center">
                                            <p className="text-lg font-semibold transition-colors text-primary group-hover:text-primary/80">
                                                View All Properties
                                            </p>
                                            <ArrowRight className="mx-auto mt-2 transition-transform text-primary group-hover:translate-x-1" />
                                        </div>
                                    </Link>
                                </motion.div>
                            ) : (
                                <motion.div
                                    key={prop.id}
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{
                                        duration: 0.8,
                                        delay: 0.2 + idx * 0.1,
                                    }}
                                >
                                    <PropertyCard {...prop} />
                                </motion.div>
                            ),
                        )
                    ) : (
                        <motion.p
                            className="mt-2 text-muted-foreground"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8, delay: 0.2 }}
                        >
                            No properties yet.
                        </motion.p>
                    )}
                </div>
            </div>
        </section>
    );
};

export default PopularProperties;
