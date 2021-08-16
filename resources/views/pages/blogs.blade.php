<?php setlocale(LC_TIME, "spanish") ?>
@foreach( $blogs as $key )
<div class="card border-light mb-3 shadow-sm bg-white rounded" id="{{$key->idMinuta}}">
	<div class="card-body">
		<div class="row">
			<div class="col-md-10">
				<h5 class="mb-3"><a href="minuta/{{$key->idMinuta}}/ver" style="text-decoration: none">{{ $key->titulo }}</a></h5>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<p class="card-text">{{ $key->contenido_min }}</p>
			</div>
		</div>		
	</div>
	<div class="card-footer bg-white border-0">
		<div class="row">
			<div class="col-md-10">
				<p class="float-left text-muted font-weight-normal"><span data-feather="user-check"></span> Por {{ $key->nombre_completo }}. <span class="ml-3" data-feather="calendar"></span> {{ strftime('%A, %d de %B %G', strtotime($key->fecha)) }} | {{ $key->nombre }}</p>
			</div>
			@if(Auth::User()->id==$key->idUser)
			<div class="col-md-2">
        		<a class="nav-link text-secondary float-right p-1" href="#!" onclick="deleteMinuta({{ $key->idMinuta }})"><span data-feather="trash-2"></span></a>
        		<a class="nav-link text-secondary float-right p-1" href="minuta/{{$key->idMinuta}}/edit"><span data-feather="edit"></span></a>
			</div>
			@endif
		</div>
	</div>
</div>
@endforeach
{!! $blogs->render() !!}
<script>
	feather.replace();
</script>