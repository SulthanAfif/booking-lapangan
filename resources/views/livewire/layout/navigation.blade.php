<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-pitch-900">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-flood-400 text-pitch-900 font-display font-bold text-sm">BL</span>
                        <span class="font-display font-bold text-chalk-50 tracking-tight hidden sm:block">Booking Lapangan</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    @php
                        $navClass = fn ($active) => $active
                            ? 'inline-flex items-center px-3 h-16 border-b-2 border-flood-400 text-sm font-medium text-chalk-50'
                            : 'inline-flex items-center px-3 h-16 border-b-2 border-transparent text-sm font-medium text-chalk-50/60 hover:text-chalk-50 hover:border-chalk-50/30 transition';
                    @endphp
                    <a href="{{ route('dashboard') }}" wire:navigate class="{{ $navClass(request()->routeIs('dashboard')) }}">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('booking.index') }}" wire:navigate class="{{ $navClass(request()->routeIs('booking.*')) }}">
                        {{ __('Booking') }}
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48" contentClasses="py-1 bg-pitch-800 ring-1 ring-chalk-50/10">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-chalk-50/80 hover:text-chalk-50 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <a href="{{ route('profile') }}" wire:navigate class="block w-full px-4 py-2 text-start text-sm text-chalk-50/80 hover:bg-pitch-700 hover:text-chalk-50 transition">
                            {{ __('Profile') }}
                        </a>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start block px-4 py-2 text-sm text-chalk-50/80 hover:bg-pitch-700 hover:text-chalk-50 transition">
                            {{ __('Log Out') }}
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-chalk-50/60 hover:text-chalk-50 hover:bg-pitch-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-pitch-700/60">
        <div class="pt-2 pb-3 space-y-1">
            @php
                $respClass = fn ($active) => $active
                    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-flood-400 text-start text-base font-medium text-chalk-50 bg-pitch-800'
                    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-chalk-50/60 hover:text-chalk-50 hover:bg-pitch-800 transition';
            @endphp
            <a href="{{ route('dashboard') }}" wire:navigate class="{{ $respClass(request()->routeIs('dashboard')) }}">
                {{ __('Dashboard') }}
            </a>
            <a href="{{ route('booking.index') }}" wire:navigate class="{{ $respClass(request()->routeIs('booking.*')) }}">
                {{ __('Booking') }}
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-pitch-700/60">
            <div class="px-4">
                <div class="font-medium text-base text-chalk-50" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-chalk-50/50">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile') }}" wire:navigate class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-chalk-50/60 hover:text-chalk-50 hover:bg-pitch-800 transition">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start block ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-chalk-50/60 hover:text-chalk-50 hover:bg-pitch-800 transition">
                    {{ __('Log Out') }}
                </button>
            </div>
        </div>
    </div>
</nav>
