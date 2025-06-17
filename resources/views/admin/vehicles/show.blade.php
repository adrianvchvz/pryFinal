<div>
    <div class="row">
        @foreach ($images as $img)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ asset($img->image) }}" class="card-img-top" alt="Imagen vehículo">
                    <div class="card-body p-2 text-center">
                        {{-- Botón de seleccionar como principal --}}
                        @if (!$img->profile)
                            {!! Form::open([
                                'route' => ['admin.vehicles.images.setprofile', $img->id],
                                'method' => 'POST',
                                'class' => 'd-inline formSetProfile',
                                'data-vehicle' => $vehicle->id,
                            ]) !!}
                            <button class="btn btn-primary btn-sm">Seleccionar</button>
                            {!! Form::close() !!}
                        @else
                            <span class="badge badge-success">Principal</span>
                        @endif
                        {{-- Botón de eliminar --}}
                        {!! Form::open([
                            'route' => ['admin.vehicles.images.destroy', $img->id],
                            'method' => 'DELETE',
                            'class' => 'd-inline',
                        ]) !!}
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    $(document).on('click', '.btnImages', function() {
        var vehicleId = $(this).data('id');
        $.ajax({
            url: '/admin/vehicles/' + vehicleId + '/images',
            type: "GET",
            success: function(response) {
                $('#ModalCenter .modal-body').html(response);
                $('#ModalCenter').modal('show');
            }
        });
    });

</script>
