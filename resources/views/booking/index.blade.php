<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-bold text-2xl text-chalk-50 tracking-tight">
            Pilih lapangan
        </h2>
        <p class="text-chalk-50/60 text-sm mt-1">Cek jadwal kosong, langsung booking jamnya.</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($fields as $field)
                    @php
                        $accent = $field->type === 'futsal'
                            ? ['bar' => 'bg-turf-500', 'card' => 'hover:border-turf-500', 'text' => 'text-turf-600']
                            : ['bar' => 'bg-flood-400', 'card' => 'hover:border-flood-400', 'text' => 'text-flood-500'];
                    @endphp
                    <a href="{{ route('booking.show', $field) }}"
                       class="block rounded-xl bg-white border border-gray-200 overflow-hidden transition {{ $accent['card'] }}">
                        <div class="h-1.5 {{ $accent['bar'] }}"></div>
                        <div class="p-6">
                            <p class="text-xs uppercase tracking-wide text-gray-400">{{ $field->type }}</p>
                            <h3 class="font-display font-bold text-xl text-ink-900 mt-1">{{ $field->name }}</h3>
                            <p class="mt-3 text-sm text-gray-600">
                                Rp {{ number_format($field->price_per_hour) }} <span class="text-gray-400">/ jam</span>
                            </p>
                            <span class="mt-4 inline-block text-sm font-medium {{ $accent['text'] }}">
                                Lihat jadwal
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-gray-300 p-10 text-center text-gray-500">
                        Belum ada lapangan tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
