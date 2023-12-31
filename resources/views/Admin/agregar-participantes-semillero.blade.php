@extends('adminlte::page')

@section('title', 'Participantes')

@section('content_header')

<div class="container">
    <div class="mb-3 note note-success">
        <figure class="text-center">
            <h1>Agregar Participantes al Semillero {{$semillero->nombre}}</h1>
        </figure>
    </div>
</div>

@stop

@section('content')
<div class="container">

    <center>

        <br>
        <ul class="list-unstyled">
        <li class="mb-1"><i class="fas fa-check-circle me-2 text-success"></i>Bienvenido {{ $user->name }}</li>
        </ul> 
        <br>

        <div id="contenedor-form">
            <center>
            <table id="buscador-agregar">
                <tr>
                    <td>
                        <div id="contenedor-buscador" class="input-group">
                            <div id="inp">
                                <input id ="buscador" type="text" placeholder="Buscar Semilleristas">
                            </div>
                            <div id="ic">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            </center>
        </div>

        <br>
        <div class="tabla-container" style= "overflow-x: auto;">
            <table id="tabla_usuarios" class="table">
                <thead class="table-info">
                    <tr>
                        <th scope="col"> </th>
                        <th scope="col">Numero De Identificación</th>
                        <th scope="col">Codigo Estudiante</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Semestre</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach($semilleristas_libres as $s)
                        <tr>
                            <th scope="row">{{ $i }}</th>
                            <td class="centered-cell">{{ $s->num_identificacion }}</td>
                            <td class="centered-cell">{{ $s->cod_estudiante }}</td>
                            <td class="centered-cell">{{ app('App\Http\Controllers\Admin\AdminController')->obtenerNombrePersona($s->num_identificacion) }}</td>
                            <td class="centered-cell">{{ app('App\Http\Controllers\Admin\AdminController')->obtenerCorreoUsuario($s->num_identificacion) }}</td>
                            <td class="centered-cell">{{ $s->semestre }}</td>
                            <td class="centered-cell">
                                <center>
                                <a style="margin: 3px;" href="{{ route('vincular_sem_sem', ['num_identificacion' => $s->num_identificacion, 'id' => $id]) }}" class="btn btn-success btn-sm">Vincular</a>
                                <a style="margin: 3px;" href="{{ route('perfiles', app('App\Http\Controllers\Admin\AdminController')->obtenerIdUsuario($s->num_identificacion)) }}" class="btn btn-info btn-sm">Perfil</a>
                                <a style="margin: 3px;" href="{{route('act_info_acad_sem', app('App\Http\Controllers\Admin\AdminController')->obtenerIdUsuario($s->num_identificacion))}}" class="btn btn-primary btn-sm">Inf. Acad</a>
                                </center>
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <script>
            //buscador
            document.addEventListener("DOMContentLoaded", function() {
                var filtroBusqueda = document.getElementById("buscador");

                filtroBusqueda.addEventListener("keyup", function() {
                    var valorBusqueda = filtroBusqueda.value.toLowerCase();
                    var filas = document.querySelectorAll("#tabla_usuarios tbody tr");

                    filas.forEach(function(fila) {
                        var contenidoFila = fila.textContent.toLowerCase();
                        if (contenidoFila.indexOf(valorBusqueda) !== -1) {
                            fila.style.display = "table-row";
                        } else {
                            fila.style.display = "none";
                        }
                    });
                });
            });
        </script>

    </center>

</div>

    @if (session('vinculacionExitosa'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                mostrarAlertaRegistroExitoso("¡Se ha vinculado el semillerita al semillero de forma correcta!","Vinculacion Exitosa", true);
            });
        </script>
    @endif

    <!-- Modal -->
    <div id="reg_ext_emergente" class="modal fade" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExitoLabel">
                        <h5 id="modal-titulo"></h5>
                    </h5>
                    <button id="cerrar-modal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i id="modal-icono"></i>
                    </div>
                    <p id="modalExitoMensaje" class="mt-3 text-center"></p>
                </div>
                <div class="modal-footer">
                    <button widht="60%" type="button" id="btnCerrarModal" class="btn">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
@stop

@section('css')
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet"/>
    <!--CSS propio-->
    <link rel="stylesheet" href="{{asset('css/segundo/listarusuarios.css')}}">
    <link rel="stylesheet" href="{{asset('css/segundo/reg_suarios.css')}}">
    <link href="{{ asset('css/segundo/general.css') }}" rel="stylesheet">
@endsection

@section('js')
    <!-- JQery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
    <!--Js Propio-->
    <script src="{{ asset('js/segundo/listarusuarios.js') }}"></script>
    <script src="{{ asset('js/segundo/reg_suarios.js') }}"></script>
@stop