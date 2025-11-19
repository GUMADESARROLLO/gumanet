@foreach($comentarios as $key)
<div class="card border-light mb-3 shadow-sm bg-white rounded cardComentario"
    style="cursor:pointer;"
    data-id="{{ $key->id }}"
    data-titulo="{{ $key->Titulo }}"
    data-contenido="{{ $key->Contenido }}"
    data-nombre="{{ $key->Nombre }}"
    data-autor="{{ $key->Autor }}"
    data-fecha="{{ date('d/m/Y h:i a', strtotime($key->Fecha)) }}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title font-weight-bold text-primary">{{ $key->Titulo }}</h5>
                <p class="card-text">{{ $key->Contenido }}</p>
                <div class="col-md-2">
                    @if($key->Imagen)
                        <div class="bg-image hover-zoom ripple rounded ripple-surface">
                        <img src="{{ Storage::disk('s3')->temporaryUrl('news/'.$key->Imagen, now()->addMinutes(5)) }}" width="50" class="img-fluid img-thumbnail w-50" />
                            <a href="#!">
                            <div class="hover-overlay">
                                <div class="mask" style="background-color: rgba(253, 253, 253, 0.15);"></div>
                            </div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    <div class="card-footer bg-white border-0">
        <div class="row">
            <div class="col-11">
                <p class="float-left font-weight-bold mr-4">
                    <img src="./images/user.svg" class="img01" /> {{ $key->Nombre }}
                </p>
                <p class="float-left font-weight-bold mr-4">
                    <img src="./images/clock.svg" class="img01" /> {{ strftime('%a %d de %b %G', strtotime($key->Fecha)) }}. {{ date('h:i a', strtotime($key->Fecha)) }}
                </p>
                <p class="float-left font-weight-bold mr-4">
                    <img src="./images/globe.svg" class="img01" /> {{ $key->Autor }}
                </p>
                <p class="float-left font-weight-bold mr-4">
                    <img src="./images/messages.svg" class="img01" /> {{ $key->respuestas_count }}
                </p>
            </div>
            <div class="col-1 text-center">
                @if($key->Read==0)
                <div class="alert-success font-weight-bold mr-1" role="alert" style="border-radius:30px;">¡Nuevo!</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="row">
    <div class="col-md-12 text-center">
        {!! $comentarios->render() !!}
    </div>
</div>
