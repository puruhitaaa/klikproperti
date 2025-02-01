'use client';

import { Button } from '@/Components/ui/button';
import { motion } from 'framer-motion';
import { ChevronRight, MapPin, Play } from 'lucide-react';

const Stats = () => {
    return (
        <section className="px-4 py-16 bg-secondary lg:px-8">
            <div className="container mx-auto">
                <div className="grid items-center gap-12 lg:grid-cols-2">
                    <div className="lg:pr-6">
                        <motion.div
                            className="relative h-96 w-full lg:h-[44rem]"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8 }}
                        >
                            <motion.div
                                className="z-10 items-center hidden w-full max-w-xs gap-4 p-4 rounded-lg bg-background -right-10 top-10 drop-shadow lg:absolute lg:flex dark:drop-shadow-none"
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.8 }}
                            >
                                <Button className="rounded-full" size="icon">
                                    <Play />
                                </Button>
                                <div className="flex flex-col items-start space-y-1">
                                    <h5 className="w-full font-semibold truncate">
                                        View House Tour
                                    </h5>
                                    <Button
                                        className="px-0 text-sm"
                                        variant="link"
                                    >
                                        Watch Video{' '}
                                        <ChevronRight className="w-4 h-4" />
                                    </Button>
                                </div>
                            </motion.div>
                            <img
                                src="https://utfs.io/f/hFb3RT4IPBAEy8Equflg1Xn4wAj2d7bhDYBN8ZUariV6HLpf"
                                alt="Luxury house tour"
                                className="object-cover w-full h-full rounded-lg"
                            />
                            <motion.div
                                className="border-secondary -bottom-10 -right-10 z-[5] hidden h-1/2 w-1/2 rounded-lg border-4 lg:absolute lg:flex"
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.8 }}
                            >
                                <img
                                    src="https://utfs.io/f/hFb3RT4IPBAEpXPPckaBFv3ZsW4mbtQNC2pzfPyYMSJ70HjU"
                                    alt="Luxury house tour"
                                    className="object-cover w-full h-full rounded-lg"
                                />
                            </motion.div>
                            <motion.div
                                className="z-10 flex-col items-start hidden w-full max-w-sm p-4 space-y-1 rounded-lg bg-background -bottom-5 left-10 drop-shadow lg:absolute lg:flex dark:drop-shadow-none"
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.8 }}
                            >
                                <h5 className="w-full font-semibold truncate">
                                    Luxury Minimalist House
                                </h5>
                                <div className="inline-flex items-center gap-4">
                                    <MapPin className="w-4 h-4 text-muted-foreground" />
                                    <p className="text-sm text-muted-foreground">
                                        Jakarta
                                    </p>
                                </div>
                                <Button
                                    className="inline-flex items-center gap-4 px-0 text-sm"
                                    variant="link"
                                >
                                    View House
                                    <ChevronRight className="w-4 h-4" />
                                </Button>
                            </motion.div>
                        </motion.div>
                    </div>
                    <div className="lg:pl-6">
                        <motion.h2
                            className="text-2xl font-bold md:text-3xl"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8 }}
                        >
                            With Us Help You Find
                            <br />
                            Your Dream Home
                        </motion.h2>
                        <motion.p
                            className="mt-4 text-muted-foreground"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8 }}
                        >
                            We guarantee that the products we sell will make our
                            customers happy because we are very selective in
                            choosing properties
                        </motion.p>
                        <div className="grid gap-8 mt-8 sm:grid-cols-2">
                            <div>
                                <motion.div
                                    className="text-2xl font-bold text-primary"
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.8 }}
                                >
                                    10K+
                                </motion.div>
                                <motion.p
                                    className="mt-2 text-sm text-muted-foreground"
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.8 }}
                                >
                                    Happy customers with our service
                                </motion.p>
                            </div>
                            <div>
                                <motion.div
                                    className="text-2xl font-bold text-primary"
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.8 }}
                                >
                                    242K+
                                </motion.div>
                                <motion.p
                                    className="mt-2 text-sm text-muted-foreground"
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.8 }}
                                >
                                    The best property we provide
                                </motion.p>
                            </div>
                            <div>
                                <motion.div
                                    className="text-2xl font-bold text-primary"
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.8 }}
                                >
                                    432K+
                                </motion.div>
                                <motion.p
                                    className="mt-2 text-sm text-muted-foreground"
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.8 }}
                                >
                                    Companies affiliated with us
                                </motion.p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default Stats;
