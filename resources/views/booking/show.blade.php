<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('booking.index') }}" wire:navigate class="text-sm text-chalk-50/50 hover:text-chalk-50 transition">
            Semua lapangan
        </a>
        <h2 class="font-display font-bold text-2xl text-chalk-50 tracking-tight mt-1">
            {{ $field->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <livewire:field-schedule :field="$field" />
            </div>
        </div>
    </div>
</x-app-layout>
