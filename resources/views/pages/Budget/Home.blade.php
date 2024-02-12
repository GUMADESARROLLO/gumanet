@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
@include('jsViews.js_Budget');
@endsection
@section('content')  
<div class="container-fluid">	
	<div class="card border-0 shadow-sm mt-3">			
	    <div class="card-body col-sm-12">
			<div class="col-sm-12">
                <div class="row ">
					<div class="col-sm-9 mt-4 ">	
                        <h5 class="card-title" id="IdCardTitle">Proyecto 89.</h5>
					</div>
						
                    <div class="col-sm-3 border-left">
                        <div class="row ">
                            <div class="col-sm-5 ">
                                <div class="form-group">                
                                    <label for="f1">Desde:</label>
                                    <input type="text" class="input-fecha" id="f1">
                                </div>
                            </div>
                            <div class="col-sm-5 ">
                                <div class="form-group">                
                                    <label for="f2">Hasta:</label>
                                    <input type="text" class="input-fecha" id="f2">
                                </div>
                                
                            </div>
                            <div class="col-sm-2 mt-4 ">
                                <a href="#!" class="btn btn-primary float-left" id="btnCalcular">
                                    <i class="material-icons text-white mt-1"  style="font-size: 20px">filter_list_alt</i>
                                </a>
                            </div>
                        </div>
                    </div>  
				</div>
                <table class="table table-striped table-bordered table-sm post_back mt-3" width="100%" id="dtProyect89">
                    
                </table>
				</div>	
			</div>
		</div>

		<div class="card border-0 shadow-sm mt-3">
			<div class="col-sm-12">				
				<div class="card-body">					
					<div class="row ">
						<div class="col-sm-12">						
                        <div class="row ">
					<div class="col-sm-9 mt-4 ">	
                        <h5 class="card-title">Proyecto 71.</h5>
					</div>
						
                    <div class="col-sm-3 border-left">
                        <div class="row ">
                            <div class="col-sm-5 ">
                                <div class="form-group">                
                                    <label for="f1">Desde:</label>
                                    <input type="text" class="input-fecha" id="f1">
                                </div>
                            </div>
                            <div class="col-sm-5 ">
                                <div class="form-group">                
                                    <label for="f2">Hasta:</label>
                                    <input type="text" class="input-fecha" id="f2">
                                </div>
                                
                            </div>
                            <div class="col-sm-2 mt-4 ">
                                <a href="#!" class="btn btn-primary float-left" id="BuscarVinneta">
                                    <i class="material-icons text-white mt-1"  style="font-size: 20px">filter_list_alt</i>
                                </a>
                            </div>
                        </div>
                    </div>  
				</div>
							<table class="table table-striped table-bordered table-sm post_back" width="100%" id="dtProyect71" >
							
                           
							</table>
						</div>
						
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>


@endsection

