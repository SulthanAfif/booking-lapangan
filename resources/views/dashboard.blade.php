<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-bold text-2xl text-chalk-50 tracking-tight">
            Halo, {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-6 lg:grid-cols-3">

            {{-- Kartu tiket: booking berikutnya --}}
            <div class="lg:col-span-2 rounded-xl bg-pitch-900 text-chalk-50 p-6 relative overflow-hidden">
                <div class="absolute inset-y-0 left-24 border-l-2 border-dashed border-chalk-50/15 hidden sm:block"></div>

                @if ($nextBooking)
                    <p class="text-xs uppercase tracking-wide text-chalk-50/50 mb-1">Jadwal berikutnya</p>
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <div>
                            <h3 class="font-display font-bold text-3xl">{{ $nextBooking->field->name }}</h3>
                            <p class="text-chalk-50/70 mt-1">
                                {{ \Illuminate\Support\Carbon::parse($nextBooking->date)->translatedFormat('l, d M Y') }}
                                &middot; {{ substr($nextBooking->start_time, 0, 5) }}–{{ substr($nextBooking->end_time, 0, 5) }}
                            </p>
                        </div>
                        <span class="inline-flex w-max items-center rounded-full px-3 py-1 text-xs font-medium
                            {{ $nextBooking->status->value === 'paid' ? 'bg-turf-500/20 text-turf-500' : 'bg-flood-400/20 text-flood-400' }}">
                            {{ ucfirst($nextBooking->status->value) }}
                        </span>
                    </div>
                @else
                    <p class="text-xs uppercase tracking-wide text-chalk-50/50 mb-1">Belum ada jadwal</p>
                    <h3 class="font-display font-bold text-3xl">Belum ada booking mendatang</h3>
                    <p class="text-chalk-50/70 mt-1 max-w-md">
                        Pilih lapangan dan jam yang kosong, tempatmu langsung terkonfirmasi.
                    </p>
                @endif

                <a href="{{ route('booking.index') }}" wire:navigate
                   class="mt-6 inline-flex items-center gap-2 rounded-lg bg-flood-400 px-4 py-2 text-sm font-semibold text-pitch-900 hover:bg-flood-300 transition">
                    Booking lapangan
                </a>
            </div>

            {{-- Ringkasan akun --}}
            <div class="rounded-xl bg-white border border-gray-200 p-6">
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-3">Akun</p>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Nama</dt>
                        <dd class="text-ink-900 font-medium">{{ auth()->user()->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Email</dt>
                        <dd class="text-ink-900 font-medium truncate max-w-[10rem]">{{ auth()->user()->email }}</dd>
                    </div>
                </dl>
                <a href="{{ route('profile') }}" wire:navigate class="mt-4 inline-block text-sm text-turf-600 hover:underline">
                    Kelola profil
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
