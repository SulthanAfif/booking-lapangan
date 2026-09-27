<div>
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <p class="text-lg font-semibold text-ink-900">
            Rp {{ number_format($field->price_per_hour) }} <span class="text-sm font-normal text-gray-400">/ jam</span>
        </p>
        <label class="flex items-center gap-2 text-sm text-gray-600">
            Tanggal
            <input type="date" wire:model.live="date" min="{{ today()->toDateString() }}"
                   class="rounded-md border-gray-300 text-sm focus:border-turf-500 focus:ring-turf-500" />
        </label>
    </div>

    @if ($successMessage)
        <div class="mb-4 rounded-lg bg-turf-50 border border-turf-500/20 px-4 py-3 text-sm text-turf-600">
            {{ $successMessage }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="mb-4 rounded-lg bg-clay-50 border border-clay-500/20 px-4 py-3 text-sm text-clay-600">
            {{ $errorMessage }}
        </div>
    @endif

    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4" wire:loading.class="opacity-50">
        @foreach ($operatingHours as $slot)
            @php $terisi = in_array($slot, $bookedSlots); @endphp
            <button
                type="button"
                @disabled($terisi)
                wire:click="book('{{ $slot }}')"
                wire:loading.attr="disabled"
                @class([
                    'rounded-lg border px-3 py-3 text-sm font-medium transition text-left',
                    'border-turf-500/30 bg-turf-50 text-turf-600 hover:bg-turf-500 hover:text-white hover:border-turf-500' => ! $terisi,
                    'border-clay-500/20 slot-booked-pattern text-clay-600 cursor-not-allowed' => $terisi,
                ])
            >
                <span class="block font-display font-bold">{{ $slot }}</span>
                <span class="block text-xs {{ $terisi ? 'text-clay-500' : 'text-turf-500' }}">
                    {{ $terisi ? 'Terisi' : 'Tersedia' }}
                </span>
            </button>
        @endforeach
    </div>

    <p class="mt-4 text-xs text-gray-400">Booking otomatis 1 jam dari slot yang kamu pilih.</p>
</div>
