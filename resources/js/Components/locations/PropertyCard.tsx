'use client';

import { Badge } from '@/Components/ui/badge';
import type { CompleteProperty } from '@/types';
import { Bath, Bed, MapPin, Square, Star } from 'lucide-react';

export function PropertyCard({
    image,
    price,
    title,
    area,
    bathrooms,
    bedrooms,
    type,
    rating,
    review_count,
    city,
    property_type,
}: CompleteProperty) {
    const getStatus = (type: string) => {
        return type === 'sale' ? 'For Sale' : 'For Rent';
    };

    return (
        <div className="group w-full cursor-pointer overflow-hidden rounded-lg border bg-background">
            <div className="relative aspect-video overflow-hidden">
                <img
                    src={image}
                    alt={title}
                    width={400}
                    height={300}
                    className="w-full object-cover transition-transform duration-300 group-hover:scale-110"
                />
                <Badge className="absolute right-0 top-0 m-4 border-0 !bg-primary text-background dark:text-foreground">
                    {property_type.name}
                </Badge>
            </div>
            <div className="p-4">
                <div className="flex items-center justify-between">
                    <div className="text-lg font-semibold text-primary">
                        Rp {price.toLocaleString('id-ID')}
                    </div>
                    <div className="flex items-center gap-1">
                        <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                        <span className="text-sm font-medium">
                            {rating.toFixed(1)}
                        </span>
                        <span className="text-sm text-muted-foreground">
                            ({review_count})
                        </span>
                    </div>
                </div>
                <h3 className="mt-2 text-lg font-medium">{title}</h3>
                <div className="mt-3 flex items-center gap-2 text-gray-500">
                    <MapPin className="h-4 w-4" />
                    <span className="text-sm">{city}</span>
                </div>
                <div className="scrollbar-hide mt-4 flex shrink-0 items-center gap-4 overflow-x-auto text-sm text-muted-foreground">
                    <div className="flex items-center gap-2">
                        <Square className="h-4 w-4" />
                        <span className="text-nowrap">{area} m²</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <Bath className="h-4 w-4" />
                        <span className="text-nowrap">
                            {bathrooms} Bathrooms
                        </span>
                    </div>
                    <div className="flex items-center gap-2">
                        <Bed className="h-4 w-4" />
                        <span className="text-nowrap">{bedrooms} Bedrooms</span>
                    </div>
                </div>
            </div>
            <div className="flex items-center gap-2 bg-secondary p-4">
                <Badge>{getStatus(type)}</Badge>
            </div>
        </div>
    );
}
