<div>
    <h3>Jadwal {{ $field->name }} — Hari ini</h3>
    <ul>
        @foreach (['08:00','09:00','10:00','19:00','20:00','21:00'] as $slot)
            <li>
                {{ $slot }} —
                @if (in_array($slot, $bookedSlots))
                    <span class="text-red-600">Terisi</span>
                @else
                    <span class="text-green-600">Tersedia</span>
                @endif
            </li>
        @endforeach
    </ul>
</div>