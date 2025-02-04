import { PropertyCard } from '@/Components/locations/PropertyCard';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import ProductLayout from '@/Layouts/ProductLayout';
import { type CompleteProperty } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { debounce } from 'lodash';
import { Filter, X } from 'lucide-react';
import { useCallback, useEffect, useRef, useState } from 'react';

interface ListingsPageProps {
    properties: {
        data: Array<CompleteProperty>;
        current_page: number;
        last_page: number;
    };
    propertyTypes: Array<{
        id: number;
        name: string;
    }>;
    cities: string[];
    filters: {
        search?: string;
        min_price?: string;
        max_price?: string;
        property_type?: string;
        city?: string;
        bedrooms?: string;
        bathrooms?: string;
        min_area?: string;
        max_area?: string;
        status?: string;
        features?: string;
        sort?: string;
    };
}

export default function ListingsPage({
    properties: initialProperties,
    propertyTypes,
    cities,
    filters: initialFilters,
}: ListingsPageProps) {
    const [properties, setProperties] = useState(initialProperties);
    const [filters, setFilters] = useState(initialFilters);
    const [loading, setLoading] = useState(false);
    const [showFilters, setShowFilters] = useState(false);
    const observerTarget = useRef(null);
    const { url } = usePage();

    const debouncedSearch = useCallback(
        debounce((query) => {
            updateFilters({ search: query });
        }, 300),
        [],
    );

    const updateFilters = (
        newFilters: Partial<ListingsPageProps['filters']>,
    ) => {
        const updatedFilters = { ...filters, ...newFilters };
        setFilters(updatedFilters);

        router.get(
            url,
            {
                ...updatedFilters,
                page: 1,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const loadMore = useCallback(() => {
        if (loading || properties.current_page >= properties.last_page) return;

        setLoading(true);
        router.get(
            url,
            {
                ...filters,
                page: properties.current_page + 1,
            },
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    const properties = page.props
                        .properties as typeof initialProperties;
                    setProperties({
                        current_page: properties.current_page,
                        last_page: properties.last_page,
                        data: [...properties.data, ...properties.data],
                    });
                    setLoading(false);
                },
            },
        );
    }, [loading, properties, filters, url]);

    useEffect(() => {
        const observer = new IntersectionObserver(
            (entries) => {
                if (entries[0].isIntersecting) {
                    loadMore();
                }
            },
            { threshold: 1.0 },
        );

        if (observerTarget.current) {
            observer.observe(observerTarget.current);
        }

        return () => observer.disconnect();
    }, [properties.current_page, loading, loadMore]);

    return (
        <>
            <Head title="Property Listings" />
            <ProductLayout>
                <div className="container mx-auto px-4 py-8">
                    <div className="mb-8 flex items-center justify-between">
                        <div>
                            <h2 className="text-2xl font-bold md:text-3xl">
                                Propery Listings
                            </h2>
                            <p className="mt-2 text-muted-foreground">
                                Find your dream property today
                            </p>
                        </div>
                        <Button
                            variant="outline"
                            onClick={() => setShowFilters(!showFilters)}
                        >
                            <Filter className="mr-2 h-4 w-4" />
                            Filters
                        </Button>
                    </div>

                    {showFilters && (
                        <div className="mb-8 grid gap-6 rounded-lg border p-6 lg:grid-cols-4">
                            <div>
                                <Label>Search</Label>
                                <Input
                                    type="text"
                                    defaultValue={filters.search}
                                    onChange={(e) =>
                                        debouncedSearch(e.target.value)
                                    }
                                    placeholder="Search properties..."
                                    className="mt-1"
                                />
                            </div>

                            <div>
                                <Label>Property Type</Label>
                                <Select
                                    value={filters.property_type}
                                    onValueChange={(value) =>
                                        updateFilters({ property_type: value })
                                    }
                                >
                                    <SelectTrigger className="mt-1">
                                        <SelectValue placeholder="Select type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all_types">
                                            All Types
                                        </SelectItem>
                                        {propertyTypes.map((type) => (
                                            <SelectItem
                                                key={type.id}
                                                value={type.id.toString()}
                                            >
                                                {type.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div>
                                <Label>City</Label>
                                <Select
                                    value={filters.city}
                                    onValueChange={(value) =>
                                        updateFilters({ city: value })
                                    }
                                >
                                    <SelectTrigger className="mt-1">
                                        <SelectValue placeholder="Select city" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all_cities">
                                            All Cities
                                        </SelectItem>
                                        {cities.map((city) => (
                                            <SelectItem key={city} value={city}>
                                                {city}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div>
                                <Label>Sort By</Label>
                                <Select
                                    value={filters.sort}
                                    onValueChange={(value) =>
                                        updateFilters({ sort: value })
                                    }
                                >
                                    <SelectTrigger className="mt-1">
                                        <SelectValue placeholder="Sort by..." />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="latest">
                                            Latest
                                        </SelectItem>
                                        <SelectItem value="price_asc">
                                            Price: Low to High
                                        </SelectItem>
                                        <SelectItem value="price_desc">
                                            Price: High to Low
                                        </SelectItem>
                                        <SelectItem value="rating">
                                            Highest Rated
                                        </SelectItem>
                                        <SelectItem value="reviews">
                                            Most Reviews
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            {/* Active filters */}
                            <div className="lg:col-span-4">
                                <div className="flex flex-wrap gap-2">
                                    {Object.entries(filters).map(
                                        ([key, value]) => {
                                            if (!value) return null;
                                            return (
                                                <Badge
                                                    key={key}
                                                    variant="secondary"
                                                    className="flex items-center gap-1"
                                                >
                                                    {key.replace('_', ' ')}:{' '}
                                                    {value}
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        className="h-4 w-4 p-0 hover:bg-transparent"
                                                        onClick={() =>
                                                            updateFilters({
                                                                [key]: '',
                                                            })
                                                        }
                                                    >
                                                        <X className="h-3 w-3" />
                                                    </Button>
                                                </Badge>
                                            );
                                        },
                                    )}
                                </div>
                            </div>
                        </div>
                    )}

                    <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        {properties.data.map((property, idx) => (
                            <motion.div
                                className="w-full"
                                key={property.id}
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{
                                    duration: 0.8,
                                    delay: 0.2 + idx * 0.1,
                                }}
                            >
                                <PropertyCard {...property} />
                            </motion.div>
                        ))}
                    </div>

                    {/* Infinite scroll trigger */}
                    {properties.current_page < properties.last_page && (
                        <div
                            ref={observerTarget}
                            className="mt-8 flex justify-center p-4"
                        >
                            {loading && (
                                <div className="h-6 w-6 animate-spin rounded-full border-2 border-primary border-t-transparent" />
                            )}
                        </div>
                    )}
                </div>
            </ProductLayout>
        </>
    );
}
