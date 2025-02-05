'use client';

import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/Components/ui/sheet';
import { Link } from '@inertiajs/react';
import { Menu } from 'lucide-react';
import Logo from './Logo';
import { Button, buttonVariants } from './ui/button';
import { Separator } from './ui/separator';
import { ModeToggle } from './ui/ThemeToggle';

const links = [
    {
        title: 'Listings',
        href: '/listings',
    },
    {
        title: 'Services',
        href: '/services',
    },
    {
        title: 'About Us',
        href: '/about-us',
    },
    {
        title: 'News',
        href: '/news',
    },
];

function Header() {
    return (
        <header className="sticky top-0 z-50 bg-background/30 backdrop-blur-sm">
            <div className="container mx-auto flex items-center p-4 lg:px-6">
                <div className="flex items-center gap-4">
                    <Link prefetch="hover" className="block" href="/">
                        <Logo className="h-6 w-auto" />
                        <span className="sr-only">KlikProperti</span>
                    </Link>
                    <nav className="ml-auto hidden gap-4 sm:gap-6 md:flex">
                        {links.map((link) => (
                            <Link
                                key={link.href}
                                className="text-sm font-medium underline-offset-4 hover:underline"
                                href={link.href}
                                prefetch="hover"
                            >
                                {link.title}
                            </Link>
                        ))}
                    </nav>
                </div>
                <div className="ml-auto">
                    <div className="hidden items-center gap-4 sm:gap-6 md:flex">
                        <Button asChild variant="ghost">
                            <Link prefetch="hover" href="/login">
                                Sign In
                            </Link>
                        </Button>
                        <Button
                            className="text-background dark:text-foreground"
                            asChild
                        >
                            <Link prefetch="hover" href="/register">
                                Sign Up
                            </Link>
                        </Button>
                        <ModeToggle />
                    </div>
                    <Sheet>
                        <SheetTrigger className="md:hidden" asChild>
                            <Button
                                className="text-background dark:text-foreground md:hidden"
                                size="icon"
                            >
                                <Menu />
                            </Button>
                        </SheetTrigger>
                        <SheetContent className="w-full sm:max-w-[50vw] md:hidden">
                            <SheetHeader>
                                <SheetTitle className="sr-only">
                                    Menu
                                </SheetTitle>
                                <SheetDescription className="sr-only">
                                    Mobile Menu
                                </SheetDescription>
                            </SheetHeader>
                            <div className="flex flex-col space-y-4 p-4">
                                {links.map((link) => (
                                    <Link
                                        key={link.href}
                                        className={buttonVariants({
                                            className:
                                                'w-full text-sm font-medium',
                                            variant: 'secondary',
                                        })}
                                        href={link.href}
                                        prefetch="hover"
                                    >
                                        {link.title}
                                    </Link>
                                ))}
                                <Separator />
                                <Button asChild variant="outline">
                                    <Link prefetch="hover" href="/login">
                                        Sign In
                                    </Link>
                                </Button>
                                <Button
                                    className="text-background dark:text-foreground"
                                    asChild
                                >
                                    <Link prefetch="hover" href="/register">
                                        Sign Up
                                    </Link>
                                </Button>
                                <ModeToggle />
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>
            </div>
        </header>
    );
}

export default Header;
