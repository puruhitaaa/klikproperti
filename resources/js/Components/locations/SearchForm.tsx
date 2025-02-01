'use client';

import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import { Search } from 'lucide-react';

export function SearchForm() {
    return (
        <div className="flex flex-col gap-4 md:flex-row md:items-end">
            <div className="grid flex-1 gap-2">
                <div className="flex items-center gap-2">
                    <div className="w-2 h-2 bg-blue-600 rounded-full" />
                    <span className="text-sm">Type</span>
                </div>
                <Select defaultValue="house">
                    <SelectTrigger className="border border-primary">
                        <SelectValue placeholder="Select type" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="house">House</SelectItem>
                        <SelectItem value="apartment">Apartment</SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <div className="grid flex-1 gap-2">
                <div className="flex items-center gap-2">
                    <div className="w-2 h-2 bg-blue-600 rounded-full" />
                    <span className="text-sm">Location</span>
                </div>
                <Input
                    className="border border-primary"
                    placeholder="Surabaya"
                />
            </div>
            <div className="grid flex-1 gap-2">
                <div className="flex items-center gap-2">
                    <div className="w-2 h-2 bg-blue-600 rounded-full" />
                    <span className="text-sm">Price Range</span>
                </div>
                <Select defaultValue="range1">
                    <SelectTrigger className="border border-primary">
                        <SelectValue placeholder="Select price range" />
                    </SelectTrigger>

                    <SelectContent>
                        <SelectItem value="range1">
                            IDR100M - IDR500M
                        </SelectItem>
                        <SelectItem value="range2">IDR500M - IDR1B</SelectItem>
                        <SelectItem value="range3">IDR1B+</SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <Button className="bg-blue-600 hover:bg-blue-700">
                <Search className="w-4 h-4 mr-2" />
                Search
            </Button>
        </div>
    );
}
