import { Config } from 'ziggy-js';

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export interface Review {
    id: number;
    rating: number;
    comment: string | null;
    user: User;
    created_at: string;
}

export interface CompleteProperty {
    id: number;
    title: string;
    description: string;
    price: number;
    type: 'sale' | 'rent';
    location_address: string;
    city: string;
    bedrooms: number;
    bathrooms: number;
    area: number;
    features: string[];
    rating: number;
    review_count: number;
    owner: User;
    image: string;
    reviews: Review[];
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
};
