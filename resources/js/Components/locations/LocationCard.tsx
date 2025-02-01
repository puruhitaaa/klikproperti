'use client';

import { Star } from 'lucide-react';

interface LocationCardProps {
  image: string;
  city: string;
  country: string;
  houses: number;
  rating: number;
  reviews: number;
}

export function LocationCard({
  image,
  city,
  country,
  houses,
  rating,
  reviews,
}: LocationCardProps) {
  return (
    <div className="group cursor-pointer">
      <div className="overflow-hidden rounded-lg">
        <img
          src={image}
          alt={`${city}, ${country}`}
          width={400}
          height={300}
          className="w-full object-cover transition-transform duration-300 group-hover:scale-110"
        />
      </div>
      <div className="mt-3">
        <h3 className="text-lg font-semibold">
          {city}, {country}
        </h3>
        <div className="mt-1 flex items-center justify-between">
          <span className="text-sm text-muted-foreground">{houses} Houses</span>
          <div className="flex items-center gap-1">
            <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
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
