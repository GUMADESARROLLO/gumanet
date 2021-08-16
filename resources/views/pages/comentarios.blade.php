<?php setlocale(LC_TIME, "spanish") ?>
		@foreach( $comentarios as $key )
		<div class="card border-light mb-3 shadow-sm bg-white rounded">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10">
						<h5 class="card-title font-weight-bold text-primary">{{ $key->Titulo }}</h5>
						<p class="card-text">{{ $key->Contenido }}</p>					
					</div>
					<div class="col-md-2 ">
					@if ($key->Imagen!='' || $key->Imagen!=NULL)
						<img src="{{config('global.url_server').$key->Imagen}}" width="100" class="img-fluid rounded float-right" style="cursor: pointer" />
					@endif						
					</div>
				</div>
			</div>
			<div class="card-footer bg-white border-0">
				<p class="float-left font-weight-bold mr-4"><img src="./images/user.svg" class="img01" /> {{ $key->Nombre }}</p>
				<p class="float-left font-weight-bold mr-4">
				<img src="./images/clock.svg" class="img01" />
							{{ strftime('%a %d de %b %G', strtotime($key->Fecha)) }}. {{ date('h:i a', strtotime($key->Fecha)) }}
				</p>
				<p class="float-left font-weight-bold mr-4"><img src="./images/globe.svg" class="img01" /> {{ $key->Autor }}</p>
			</div>
		</div>
		@endforeach
{!! $comentarios->render() !!}