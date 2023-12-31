<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\View\View;
use App\Models\Semillero;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Persona;
use App\Models\Coordinador;
use App\Models\Semillerista;
use App\Models\Proyecto;
use App\Models\Evento;

class ReportController extends Controller
{
    public function generarReporteUsuarios(){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        $this->authorize('director', $rol);

        $usuarios = User::all();
        date_default_timezone_set('America/Bogota');
        $fechaActual = date("d-m-Y");
        $horaActual = date("h:i A");
        $fecha = $fechaActual.' | '.$horaActual;
        $pdf = Pdf::loadView('Reportes.usuarios', compact('usuarios', 'fecha'));
        return $pdf->stream('Reporte_Usuarios.pdf');
    }
    public function generarReporteSemillero_admin($id){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        $this->authorize('director', $rol);

        date_default_timezone_set('America/Bogota');
        $fechaActual = date("d-m-Y");
        $horaActual = date("h:i A");
        $fecha = $fechaActual.' | '.$horaActual;

        $semillero = Semillero::where('id_semillero', $id)->first();

        if($semillero !== null){
            $logo = $semillero->logo;
            $foto = '';
            if($logo !== null){
                $foto= public_path().Storage::url($logo);
            }else{
                $foto = public_path().'/vendor/adminlte/dist/img/logo.png';
            }
            
            $pdf = Pdf::loadView('Reportes.semillero', compact('semillero', 'fecha', 'foto'));
            return $pdf->stream('Reporte_Semillero.pdf');
        }else{
            return redirect('home');
        }
    }
    public function generarReporteSemillero_coor(){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        $this->authorize('coordinador', $rol);

        $persona = Persona::where('usuario', $user->id)->first();
        if($persona !== null){
            $coordinador = Coordinador::where('num_identificacion', $persona->num_identificacion)->first();
            if($coordinador !== null){
                $semillero = Semillero::where('id_semillero', $coordinador->semillero)->first();
                if($semillero !== null){
                    date_default_timezone_set('America/Bogota');
                    $fechaActual = date("d-m-Y");
                    $horaActual = date("h:i A");
                    $fecha = $fechaActual.' | '.$horaActual;

                    $logo = $semillero->logo;
                    $foto = '';
                    if($logo !== null){
                        $foto= public_path().Storage::url($logo);
                    }else{
                        $foto = public_path().'/vendor/adminlte/dist/img/logo.png';
                    }
                    
                    $pdf = Pdf::loadView('Reportes.semillero', compact('semillero', 'fecha', 'foto'));
                    return $pdf->stream('Reporte_Semillero.pdf');
                    
                }else{
                    return redirect()->route('ver_semillero');
                }
            }else{
                return redirect()->route('ver_semillero');
            }
        }else{
            return redirect()->route('perfil')->with('actualizarProfa', true);
        }
    }
    public function generarReporteSemillero_sem(){
        $user = auth()->user();

        $persona = Persona::where('usuario', $user->id)->first();
        if($persona !== null){
            $semillerista = Semillerista::where('num_identificacion', $persona->num_identificacion)->first();
            if($semillerista !== null){
                $semillero = Semillero::where('id_semillero', $semillerista->semillero)->first();
                if($semillero !== null){
                    date_default_timezone_set('America/Bogota');
                    $fechaActual = date("d-m-Y");
                    $horaActual = date("h:i A");
                    $fecha = $fechaActual.' | '.$horaActual;

                    $logo = $semillero->logo;
                    $foto = '';
                    if($logo !== null){
                        $foto= public_path().Storage::url($logo);
                    }else{
                        $foto = public_path().'/vendor/adminlte/dist/img/logo.png';
                    }
                    
                    $pdf = Pdf::loadView('Reportes.semillero', compact('semillero', 'fecha', 'foto'));
                    return $pdf->stream('Reporte_Semillero.pdf');
                    
                }else{
                    return redirect('home');
                }
            }else{
                return redirect('home');
            }
        }else{
            return redirect('home');
        }
    }
    public function generarReporteSemilleristas(){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        $this->authorize('coordinador', $rol);

        $persona = Persona::where('usuario', $user->id)->first();
        if($persona !== null){
            $coordinador = Coordinador::where('num_identificacion', $persona->num_identificacion)->first();
            if($coordinador !== null){
                $semillero = DB::table('semilleros')->where('id_semillero', $coordinador->semillero)->first();
                if($semillero !== null){
                    date_default_timezone_set('America/Bogota');
                    $fechaActual = date("d-m-Y");
                    $horaActual = date("h:i A");
                    $fecha = $fechaActual.' | '.$horaActual;
            
                    $logo = $semillero->logo;
                    $foto = '';
                    if($logo !== null){
                        $foto= public_path().Storage::url($logo);
                    }else{
                        $foto = public_path().'/vendor/adminlte/dist/img/logo.png';
                    }

                    $semilleristas = Semillerista::where('semillero',$semillero->id_semillero)->get();
            
                    $pdf = Pdf::loadView('Reportes.semilleristas', compact('semillero', 'semilleristas', 'fecha', 'foto'));
                    return $pdf->stream('Reporte_Semilleristas.pdf');
                }else{
                    return redirect()->route('ver_semillero');
                }
            }else{
                return redirect()->route('ver_semillero');
            }
        }else{
            return redirect()->route('perfil')->with('actualizarProfa', true);
        }
    }

    public function generarReporteEventos(){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        // $this->authorize('director', $rol);

        $eventos = Evento::all();
        date_default_timezone_set('America/Bogota');
        $fechaActual = date("d-m-Y");
        $horaActual = date("h:i A");
        $fecha = $fechaActual.' | '.$horaActual;

        $tipoOptions = [
            '1' => 'Congreso',
            '2' => 'Encuentro',
            '3' => 'Seminario',
            '4' => 'Taller',
        ];
        
        $modalidadOptions = [
            '1' => 'Virtual',
            '2' => 'Presencial',
            '3' => 'Hibrida',
        ];
        $clasificacionOptions = [
            '1' => 'Local',
            '2' => 'Regional',
            '3' => 'Nacional',
        ];

        $pdf = Pdf::loadView('Reportes.eventos', compact('eventos', 'fecha','tipoOptions','modalidadOptions','clasificacionOptions'));
        return $pdf->stream('Reporte_Eventos.pdf');
    }

    public function generarReporteEventosIndividual($id){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();

        $id_int = intval($id);
        $eventos = DB::table('eventos')->where('codigo_evento',$id_int)->get();
        if($eventos !== null){
            date_default_timezone_set('America/Bogota');
            $fechaActual = date("d-m-Y");
            $horaActual = date("h:i A");
            $fecha = $fechaActual.' | '.$horaActual;
    
            $tipoOptions = [
                '1' => 'Congreso',
                '2' => 'Encuentro',
                '3' => 'Seminario',
                '4' => 'Taller',
            ];
            
            $modalidadOptions = [
                '1' => 'VirtualA',
                '2' => 'Presencial',
                '3' => 'Hibrida',
            ];
            $clasificacionOptions = [
                '1' => 'Local',
                '2' => 'Regional',
                '3' => 'Nacional',
            ];
    
            $pdf = Pdf::loadView('Reportes.eventosIndividual', compact('eventos', 'fecha','tipoOptions','modalidadOptions','clasificacionOptions'));
            return $pdf->stream('Reporte_Eventos.pdf');
        }
    }

    public function generarReporteProyectosA(){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        $this->authorize('director', $rol);
        date_default_timezone_set('America/Bogota');
        $fechaActual = date("d-m-Y");
        $horaActual = date("h:i A");
        $fecha = $fechaActual.' | '.$horaActual;

        $proyectos = Proyecto::all();
        $estadoOptions = [
            '1' => 'Propuesta',
            '2' => 'En curso',
            '3' => 'Finalizado',
            '4' => 'Inactivo',
        ];
        
        $tipoOptions = [
            '1' => 'Investigación',
            '2' => 'Innovación y Desarrollo',
            '3' => 'Emprendimiento',
        ];

        $pdf = Pdf::loadView('Reportes.proyectosA', compact('proyectos', 'fecha','tipoOptions','estadoOptions'));
        return $pdf->stream('Reporte_Proyectos.pdf');
    }

    public function generarReporteProyectosIndividuaAI($id){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        $this->authorize('director', $rol);

        $id_int = intval($id);
        $proyectos = DB::table('proyectos')->where('id_proyecto',$id_int)->get();
        $proyectos2 = DB::table('proyectos')->where('id_proyecto',$id_int)->first();
        $semillero = DB::table('semilleros')->where('id_semillero', $proyectos2->semillero)->first();
        $nombre = $semillero->nombre;
        if($proyectos !== null){
            date_default_timezone_set('America/Bogota');
            $fechaActual = date("d-m-Y");
            $horaActual = date("h:i A");
            $fecha = $fechaActual.' | '.$horaActual;
    
            // $proyectos = Proyecto::all();
            $estadoOptions = [
                '1' => 'Propuesta',
                '2' => 'En curso',
                '3' => 'Finalizado',
                '4' => 'Inactivo',
            ];
            
            $tipoOptions = [
                '1' => 'Investigación',
                '2' => 'Innovación y Desarrollo',
                '3' => 'Emprendimiento',
            ];
    
            $pdf = Pdf::loadView('Reportes.proyectosIndividual', compact('proyectos', 'fecha','tipoOptions','estadoOptions','nombre'));
            return $pdf->stream('Reporte_Proyectos.pdf');

        }

    }

    public function generarReporteProyectosC(){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        $this->authorize('coordinador', $rol);

        $persona = Persona::where('usuario', $user->id)->first();
        if($persona !== null){
            $coordinador = Coordinador::where('num_identificacion', $persona->num_identificacion)->first();
            if($coordinador !== null){
                $semillero = DB::table('semilleros')->where('id_semillero', $coordinador->semillero)->first();
                if($semillero !== null){
                    date_default_timezone_set('America/Bogota');
                    $fechaActual = date("d-m-Y");
                    $horaActual = date("h:i A");
                    $fecha = $fechaActual.' | '.$horaActual;
            
                    $logo = $semillero->logo;
                    $foto = '';
                    if($logo !== null){
                        $foto= public_path().Storage::url($logo);
                    }else{
                        $foto = public_path().'/vendor/adminlte/dist/img/logo.png';
                    }

                    $proyectos = Proyecto::where('semillero',$coordinador->semillero)->get();
                    $estadoOptions = [
                        '1' => 'Propuesta',
                        '2' => 'En curso',
                        '3' => 'Finalizado',
                        '4' => 'Inactivo',
                    ];
                    
                    $tipoOptions = [
                        '1' => 'Investigación',
                        '2' => 'Innovación y Desarrollo',
                        '3' => 'Emprendimiento',
                    ];

                    $pdf = Pdf::loadView('Reportes.proyectosC', compact('proyectos', 'fecha','tipoOptions','estadoOptions'));
                    return $pdf->stream('Reporte_Proyectos.pdf');
                }else{
                    return redirect()->route('ver_semillero');
                }
            }else{
                return redirect()->route('ver_semillero');
            }
        }else{
            return redirect()->route('perfil')->with('actualizarProfa', true);
        }
    }

    public function generarReporteProyectosIndividuaCI($id){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        $this->authorize('coordinador', $rol);

        $persona = Persona::where('usuario', $user->id)->first();
        if($persona !== null){
            $coordinador = Coordinador::where('num_identificacion', $persona->num_identificacion)->first();
            if($coordinador !== null){
                $semillero = DB::table('semilleros')->where('id_semillero', $coordinador->semillero)->first();
                if($semillero !== null){
                    date_default_timezone_set('America/Bogota');
                    $fechaActual = date("d-m-Y");
                    $horaActual = date("h:i A");
                    $fecha = $fechaActual.' | '.$horaActual;
            
                    $logo = $semillero->logo;
                    $foto = '';
                    if($logo !== null){
                        $foto= public_path().Storage::url($logo);
                    }else{
                        $foto = public_path().'/vendor/adminlte/dist/img/logo.png';
                    }
                    $nombre = $semillero->nombre;
                    $id_int = intval($id);
                    $proyectos = DB::table('proyectos')->where('id_proyecto',$id_int)->get();
                    if($proyectos !== null){
                        date_default_timezone_set('America/Bogota');
                        $fechaActual = date("d-m-Y");
                        $horaActual = date("h:i A");
                        $fecha = $fechaActual.' | '.$horaActual;
                
                        // $proyectos = Proyecto::all();
                        $estadoOptions = [
                            '1' => 'Propuesta',
                            '2' => 'En curso',
                            '3' => 'Finalizado',
                            '4' => 'Inactivo',
                        ];
                        
                        $tipoOptions = [
                            '1' => 'Investigación',
                            '2' => 'Innovación y Desarrollo',
                            '3' => 'Emprendimiento',
                        ];
                        
                        $pdf = Pdf::loadView('Reportes.proyectosIndividual', compact('proyectos', 'fecha','tipoOptions','estadoOptions','nombre'));
                        return $pdf->stream('Reporte_Proyectos.pdf');
                    }
                }
            }
        }else{
            return redirect()->route('perfil')->with('actualizarProfa', true);
        }
    }
}
