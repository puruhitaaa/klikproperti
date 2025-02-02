'use client';

import { type Location } from '@/types';
import { Star } from 'lucide-react';

export function LocationCard({
    image,
    city,
    property_count,
    rating,
    reviews,
}: Location) {
    return (
        <div className="cursor-pointer group">
            <div className="overflow-hidden rounded-lg">
                <img
                    src={image}
                    alt={`${city}`}
                    width={400}
                    height={300}
                    className="object-cover w-full transition-transform duration-300 group-hover:scale-110"
                />
            </div>
            <div className="mt-3">
                <h3 className="text-lg font-semibold">{city}</h3>
                <div className="flex items-center justify-between mt-1">
                    <span className="text-sm text-muted-foreground">
                        {property_count} Properties
                    </span>
                    <div className="flex items-center gap-1">
                        <Star className="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <span className="text-sm">{rating}</span>
                        <span className="text-sm text-muted-foreground">
                            ({reviews} Reviews)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    );
}
