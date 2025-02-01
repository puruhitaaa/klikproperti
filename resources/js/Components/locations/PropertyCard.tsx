'use client';

import { Badge } from '@/Components/ui/badge';
import type { CompleteProperty } from '@/types';
import { Bath, Bed, MapPin, Square, Star } from 'lucide-react';

export function PropertyCard({
    image,
    price,
    title,
    location_address,
    area,
    bathrooms,
    bedrooms,
    type,
    rating,
    review_count,
}: CompleteProperty) {
    const getStatus = (type: string) => {
        return type === 'sale' ? 'For Sale' : 'For Rent';
    };

    return (
        <div className="w-full overflow-hidden border rounded-lg cursor-pointer bg-background group">
            <div className="relative overflow-hidden aspect-video">
                {JSON.stringify(image)}
                {/* <img
                    src={`/storage/${image}`}
                    alt={title}
                    width={400}
                    height={300}
                    className="object-cover w-full transition-transform duration-300 group-hover:scale-110"
                /> */}
            </div>
            <div className="p-4">
                <div className="flex items-center justify-between">
                    <div className="text-lg font-semibold text-primary">
                        Rp {price.toLocaleString('id-ID')}
                    </div>
                    <div className="flex items-center gap-1">
                        <Star className="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <span className="text-sm font-medium">
                            {rating.toFixed(1)}
                        </span>
                        <span className="text-sm text-muted-foreground">
                            ({review_count})
                        </span>
                    </div>
                </div>
                <h3 className="mt-2 text-lg font-medium">{title}</h3>
                <div className="flex items-center gap-2 mt-3 text-gray-500">
                    <MapPin className="w-4 h-4" />
                    <span className="text-sm">{location_address}</span>
                </div>
                <div className="flex items-center gap-4 mt-4 overflow-x-auto text-sm text-muted-foreground scrollbar-hide shrink-0">
                    <div className="flex items-center gap-2">
                        <Square className="w-4 h-4" />
                        <span className="text-nowrap">{area} m²</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <Bath className="w-4 h-4" />
                        <span className="text-nowrap">
                            {bathrooms} Bathrooms
                        </span>
                    </div>
                    <div className="flex items-center gap-2">
                        <Bed className="w-4 h-4" />
                        <span className="text-nowrap">{bedrooms} Bedrooms</span>
                    </div>
                </div>
            </div>
            <div className="flex items-center gap-2 p-4 bg-secondary">
                <Badge variant="outline" className="border-primary">
                    {getStatus(type)}
                </Badge>
            </div>
        </div>
    );
}
