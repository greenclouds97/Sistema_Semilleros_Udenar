<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Persona;
use App\Models\User;
use App\Models\Semillerista;
use App\Models\Evento;
use App\Models\Semillero;
use App\Models\Coordinador;
use App\Models\Rol;
use App\Models\Proyecto;

class HomeController extends Controller
{
    public function index(){
        $user = auth()->user();
        $persona = Persona::where('usuario', $user->id)->first();
        if($persona !== null){
            return view('index')->with('user', $user);
        }else{
            return redirect()->route('perfil')->with('actualizarProfa', true);
        }
    }
    public function login(){
        if (auth()->check()) {
            $user = auth()->user();
            return view('index')->with('user', $user);
        } else {
            session()->flash('openModal', true);
            return view('welcome');
        }
    }
    public function devLogin(){
        if (auth()->check()) {
            $user = auth()->user();
            return view('index')->with('user', $user);
        } else {
            session()->flash('openModal', true);
            return view('about');
        }
    }
    public function welcome() {
        session()->forget('openModal');
        return view('welcome');
    }
    public function registarUsuarios() {
        return redirect()->route('v_reg_usr');
    }
    public function checkEmail($email) {
        $user = User::where('email', $email)->first();
        return response()->json(['exists' => !is_null($user)]);
    }
    public function postUsuarios() {
        return redirect()->route('registar_usuario');
    }
    public function perfil(){
        $user = auth()->user();
        $persona = Persona::where('usuario', $user->id)->first();
        if($persona !== null){
            return view('perfil', ['persona' => $persona, 'user' => $user]);
        }else{
            return view('perfil')->with('user', $user);
        }
    }
    public function actualizarPerfil(Request $request){
        $validator = Validator::make($request->all(), [
            'num_identificacion' => 'required',
            'tipo_identificacion' => 'required',
            'nombre' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'fecha_nac' => 'required',
            'sexo' => 'required',
            'programa' => 'required',
            'foto' => 'nullable|image|max:2048',
        ], [
            'num_identificacion.required'=>'El Numero de identificacion no puede estar vacío',
            'tipo_identificacion.required'=>'El tipo de identificacion no puede estar vacío',
            'nombre.required'=>'El nombre no puede estar vacío',
            'telefono.required'=>'El telefono no puede estar vacío',
            'direccion.required'=>'La dirección no puede estar vacía',
            'fecha_nac.required'=>'La fecha de nacimiento no puede estar vacía',
            'sexo.required'=>'El sexo no puede estar vacío',
            'programa.required'=>'El programa academico no puede estar vacío',
            'foto.image' => 'El archivo debe ser una imagen',
            'foto.max' => 'El tamaño de la imagen no puede ser mayor a 2MB',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $user = auth()->user();
        $persona = Persona::where('usuario', $user->id)->first();
        $usuarioRepetido = Persona::all();

        $usuarioExistente = $usuarioRepetido->firstWhere('num_identificacion', $request->input('num_identificacion'));

        if ($usuarioExistente === null) {
        if ($persona === null) {
            $persona = new Persona();
        }

        $persona->num_identificacion = $request->input('num_identificacion');
        $persona->tipo_identificacion = $request->input('tipo_identificacion');
        $persona->usuario = $user->id;
        $persona->nombre = $request->input('nombre');
        $persona->correo = $user->email;
        $persona->telefono = $request->input('telefono');
        $persona->direccion = $request->input('direccion');
        $persona->fecha_nac = $request->input('fecha_nac');
        $persona->sexo = $request->input('sexo');
        $persona->programa_academico = $request->input('programa');
    
        $imagen = $request->file('foto');
        if ($imagen !== null && $imagen->isValid()) {
            if ($persona->foto !== null) {
                Storage::delete($persona->foto);
            }

            $rutaFoto = $imagen->store('public/perfiles/imagenes');
            $persona->foto = $rutaFoto;
        }

        $persona->save();

        $nombre_rol = $user->getRoleNames()[0];
        if($nombre_rol == 'semillerista'){
            return redirect()->route('vista_actualizar_datos_semillerista');
        }else{
            return redirect()->route('perfil')->with('actualizacionExitosa', true);
        }}
        else {
            return redirect()->route('perfil')->with('actualizacionNoExitosa', true);
        }
    }
    public function actualizarContrasena(){
        $user = auth()->user();

        return view('reset-psswd')->with('user', $user);
    }
    public function cambiarContrasena(Request $request){
        $user = auth()->user();
        $usr_edit = User::findOrFail($user->id);

        $validator = Validator::make($request->all(), [
            'passwd1' => 'required|min:6',
            'passwd2' => 'required|min:6',
            'passwd3' => 'required|min:6',
        ], [
            'passwd1.min' => 'La contraseña debe tener al menos :min caracteres.',
            'passwd2.min' => 'La contraseña debe tener al menos :min caracteres.',
            'passwd3.min' => 'La contraseña debe tener al menos :min caracteres.',
            'passwd1.required' => 'La contraseña vieja no puede estar vacia.',
            'passwd2.required' => 'La contraseña nueva no puede estar vacia',
            'passwd3.required' => 'La contraseña nueva no puede estar vacia',
        ]);
        
        if (!password_verify($request->input('passwd1'), $usr_edit->password)) {
            $validator = Validator::make($request->all(), [], []);
            $validator->errors()->add('passwd1', 'Contraseña Actual Incorrecta.');

            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if (($request->input('passwd2')) === ($request->input('passwd3'))) {
                // Comprobar si hay errores de validación
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                } else {
                    $user->password = bcrypt($request->input('passwd2'));
                    $user->save();
                    
                    return redirect()->route('cambiar-contrasena')->with('cambioExitoso', true);
                }
            } else {
                $validator = Validator::make($request->all(), [], []);
    
                $validator->errors()->add('passwd2', 'Las contraseñas no coinciden.');
                $validator->errors()->add('passwd3', 'Las contraseñas no coinciden.');
    
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    }
    public function listarEventos(){
        $user = auth()->user();
        $eventos = Evento::all();
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
        return view('eventos', compact('eventos','user','tipoOptions','modalidadOptions','clasificacionOptions'));
    }
    public function verSemillero(){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        if ($rol->name === 'coordinador') {
            // Lógica para coordinadores
            $persona = DB::table('personas')->where('usuario', $user->id)->first();
            if($persona !== null){
                $coordinador = Coordinador::where('num_identificacion', $persona->num_identificacion)->first();
                $semillero = null;
                if($coordinador !== null){
                    $semillero = Semillero::where('id_semillero', $coordinador->semillero)->first();
                }
                return view('semillero', compact('semillero', 'user', 'coordinador'));
            }else{
                return redirect()->route('perfil')->with('actualizarProfa', true);
            }
        } elseif ($rol->name === 'semillerista') {
            // Lógica para semilleristas
            $persona = DB::table('personas')->where('usuario', $user->id)->first();
            if($persona !== null){
                $semillerista = Semillerista::where('num_identificacion', $persona->num_identificacion)->first();
                if($semillerista !== null){
                    $semillero = Semillero::where('id_semillero', $semillerista->semillero)->first();
                    $coordinador = null;
                    if($semillero !== null){
                        $coordinador = Coordinador::where('semillero', $semillero->id_semillero)->first();
                    }
                    return view('semillero', compact('semillero', 'user', 'coordinador'));
                }else{
                    return redirect()->route('perfil')->with('actualizarProfa', true);
                }
            }else{
                return redirect()->route('perfil')->with('actualizarProfa', true);
            }
        }
    }
    public function vistaProyectoEventoVinculado($codigo_evento){
        $user = auth()->user();
        $nombre_rol = $user->getRoleNames()[0];
        $rol = Rol::where('name', $nombre_rol)->first();
        $presentaciones =  DB::table('presentaciones')->where('evento', $codigo_evento)->get();
        $proyectos = collect(); // Inicializar una colección vacía
    
        foreach ($presentaciones as $presentacion) {
            $proyecto = Proyecto::find($presentacion->proyecto); // Buscar cada proyecto
            if ($proyecto) {
                $proyectos->push($proyecto); // Agregar proyecto a la colección
            }
        }
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
        return view('verProyectosVinculados', compact('proyectos', 'user','estadoOptions','tipoOptions'));
    }
    public function aboutUs(){
        return view('about');
    }
}
