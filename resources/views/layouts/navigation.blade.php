<nav x-data="{ open: false }" class="border-b border-default bg-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-12">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <span class="font-display text-xl font-semibold text-primary">InterviewPrep</span>
                    </a>
                </div>

                <div class="hidden sm:flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-secondary hover:text-primary' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('domains.index') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('domains.*') ? 'text-primary' : 'text-secondary hover:text-primary' }}">
                        Mes Domaines
                    </a>
                    <a href="{{ route('concepts.archived') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('concepts.archived') ? 'text-primary' : 'text-secondary hover:text-primary' }}">
                        Archivés
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-4">
                <div class="flex items-center gap-3 pr-4 border-r border-default">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-black font-semibold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm text-secondary">{{ Auth::user()->name }}</span>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center p-2 rounded-lg text-secondary hover:text-primary hover:bg-tertiary transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-secondary hover:text-primary hover:bg-tertiary transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-secondary border-t border-default">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-tertiary text-primary' : 'text-secondary hover:bg-tertiary' }}">
                Dashboard
            </a>
            <a href="{{ route('domains.index') }}" class="block px-4 py-2 rounded-lg text-base font-medium {{ request()->routeIs('domains.*') ? 'bg-tertiary text-primary' : 'text-secondary hover:bg-tertiary' }}">
                Mes Domaines
            </a>
            <a href="{{ route('concepts.archived') }}" class="block px-4 py-2 rounded-lg text-base font-medium {{ request()->routeIs('concepts.archived') ? 'bg-tertiary text-primary' : 'text-secondary hover:bg-tertiary' }}">
                Archivés
            </a>
        </div>

        <div class="pt-4 pb-2 border-t border-default px-4">
            <div class="text-sm font-medium text-primary">{{ Auth::user()->name }}</div>
            <div class="text-xs text-muted">{{ Auth::user()->email }}</div>
        </div>

        <div class="pt-2 pb-4 space-y-1 px-4">
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-secondary hover:bg-tertiary">
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 rounded-lg text-base font-medium text-secondary hover:bg-tertiary">
                    Log Out
                </a>
            </form>
        </div>
    </div>
</nav>