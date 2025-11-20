@foreach($comentarios as $key)
<div class="container-fluid p-0 mb-4">
    <div class="card card-post p-3 mb-4 cardComentario" 
    style="cursor:pointer;"
    data-id="{{ $key->id }}"
    data-titulo="{{ $key->Titulo }}"
    data-contenido="{{ $key->Contenido }}"
    data-nombre="{{ $key->Nombre }}"
    data-autor="{{ $key->Autor }}"
    data-fecha="{{ date('d/m/Y h:i a', strtotime($key->Fecha)) }}">
      <div class="d-flex align-items-start post-content-row">
        <div class="flex-grow-1 pe-3">
          <!-- Author row -->
          <div class="d-flex align-items-center mb-2">
            <img src=" {{ asset('images/avatar-4.jpg') }}" alt="Autor" class="author-avatar me-2" style="margin-right: 8px;">
            <div class="me-auto">
              <div class="small text-muted">{{ $key->Nombre }} | {{ $key->Autor }}</div>
            </div>
          </div>

          <h5 class="mb-1" style="font-weight:700; margin-bottom:.15rem;">{{ $key->Titulo }}</h5>
          <p class="text-muted mb-2">{{ $key->Contenido }}</p>

          <div class="d-flex align-items-center meta">
            @if($key->Read==0)
            <div>
              <div class="bg" aria-describedby="114" aria-labelledby="114" role="tooltip">
                <div tabindex="-1" class="bh">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 64 64">
                    <path fill="#FFC017" d="m39.637 40.831-5.771 15.871a1.99 1.99 0 0 1-3.732 0l-5.771-15.87a2.02 2.02 0 0 0-1.194-1.195L7.298 33.866a1.99 1.99 0 0 1 0-3.732l15.87-5.771a2.02 2.02 0 0 0 1.195-1.194l5.771-15.871a1.99 1.99 0 0 1 3.732 0l5.771 15.87a2.02 2.02 0 0 0 1.194 1.195l15.871 5.771a1.99 1.99 0 0 1 0 3.732l-15.87 5.771a2.02 2.02 0 0 0-1.195 1.194"></path>
                  </svg>
                </div>
              </div>
            </div>
            @endif   
            <div class="d-flex align-items-center">
              <i class="bi bi-calendar-event me-1"></i> {{ strftime('%a %d de %b %G', strtotime($key->Fecha)) }}. {{ date('h:i a', strtotime($key->Fecha)) }}
            </div>
          
            @if($key->respuestas_count > 0)
            <div class="d-flex align-items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6B6B6B" aria-labelledby="response-filled-16px-desc" viewBox="0 0 16 16"><desc id="response-filled-16px-desc">A response icon</desc><path fill="#6B6B6B" d="M12.344 11.458A5.28 5.28 0 0 0 14 7.526C14 4.483 11.391 2 8.051 2S2 4.483 2 7.527c0 3.051 2.712 5.526 6.059 5.526a6.6 6.6 0 0 0 1.758-.236q.255.223.554.414c.784.51 1.626.768 2.512.768a.37.37 0 0 0 .355-.214.37.37 0 0 0-.03-.384 4.7 4.7 0 0 1-.857-1.958v.014z"></path></svg>
              <i class="bi bi-chat-left-text me-1" style="margin-left: 8px;"></i> {{ $key->respuestas_count }}
            </div>
            @endif
          </div>
        </div>

        <div class="flex-shrink-0 d-none d-sm-block ">
          @if($key->Imagen)
              <div class="bg-image">
              <img src="{{ Storage::disk('s3')->temporaryUrl('news/'.$key->Imagen, now()->addMinutes(5)) }}" width="180" class="img-fluid post-thumb w-180" />
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
@endforeach
<div class="row">
    <div class="col-md-12 d-flex justify-content-center">
        {!! $comentarios->render() !!}
    </div>
</div>
