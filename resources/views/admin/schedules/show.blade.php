<div class="form-row">
    <div class="form-group col-6">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Zona:</strong> {{ $zones[$schedule->zone_id] ?? '-' }}</li>
            <li class="list-group-item"><strong>Vehículo:</strong> {{ $vehicles[$schedule->vehicle_id] ?? '-' }}</li>
            <li class="list-group-item"><strong>Turno:</strong> {{ $shifts[$schedule->shift_id] ?? '-' }}</li>
        </ul>
    </div>
    <div class="form-group col-6">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Fecha inicio:</strong> {{ $schedule->start_date }}</li>
            <li class="list-group-item"><strong>Fecha fin:</strong> {{ $schedule->end_date }}</li>
            <li class="list-group-item"><strong>Días:</strong> {{ $diasString ?: '-' }}</li>
        </ul>
    </div>
</div>
<div class='form-group'>
    <ul class="list-group list-group-flush">
        <strong class="ml-3">Personal Asignado</strong>
        @if ($conductorNombre)
            <li class="list-group-item">{{ $conductorNombre }} <span class="badge badge-info">Conductor</span></li>
        @endif
        @foreach ($ayudantesNombres as $nombre)
            <li class="list-group-item">{{ $nombre }} <span class="badge badge-secondary">Ayudante</span></li>
        @endforeach
    </ul>
</div>
