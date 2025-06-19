{!! Form::hidden('route_id', $route->id) !!}
<div class="form-group">
    {!! Form::label('zone', 'Seleccionar Zona') !!}
    {!! Form::select('zone_id', $zones, null, ['class' => 'form-control', 'required']) !!}
</div>
