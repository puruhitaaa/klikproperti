'use client';

import { motion } from 'framer-motion';
import { LocationCard } from '../locations/LocationCard';

const MostSearched = () => {
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
          Very strategic location | has very adequate facilities | suitable for
          families with a modern home look
        </motion.p>
        <div className="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          {[
            {
              image:
                'https://utfs.io/f/hFb3RT4IPBAEpXPPckaBFv3ZsW4mbtQNC2pzfPyYMSJ70HjU',
              city: 'Bandung',
              country: 'Indonesia',
              houses: 3000,
              rating: 4.4,
              reviews: 12,
            },
            {
              image:
                'https://utfs.io/f/hFb3RT4IPBAEy8Equflg1Xn4wAj2d7bhDYBN8ZUariV6HLpf',
              city: 'Jakarta',
              country: 'Indonesia',
              houses: 5824,
              rating: 4.4,
              reviews: 12,
            },
            {
              image:
                'https://utfs.io/f/hFb3RT4IPBAEyRE9KQlg1Xn4wAj2d7bhDYBN8ZUariV6HLpf',
              city: 'Bekasi',
              country: 'Indonesia',
              houses: 2504,
              rating: 4.4,
              reviews: 12,
            },
          ].map((location, index) => (
            <motion.div
              key={location.city}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.2 + index * 0.1 }}
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
