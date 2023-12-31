<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ReportController;

//rutas de usuarios
Route::get('/usuarios', [AdminController::class,'listarUsuarios'])->name('usuarios');
//registrar
Route::get('/vista/registrar-usuarios', [AdminController::class,'vistaRegUsuarios'])->name('v_reg_usr');
Route::post('/vista/registrar-usuarios', [AdminController::class,'registrarUsuario'])->name('registar_usuario');
//editar
Route::get('/vista/editar-usuarios/{id}', [AdminController::class,'vistaEditUsuarios'])->name('edit_usr');
Route::post('/vista/editar-usuarios/{id}', [AdminController::class,'editUsuarios'])->name('editar_usr');

//eliminar
Route::get('/eliminar-usuarios/{id}', [AdminController::class,'eliminarUsuario'])->name('delete_usr');
Route::get('/eliminar-usuario/{id}', [AdminController::class,'eliminarUsuarioConfirmado'])->name('eliminar_confirmado');

//perfiles
Route::get('/perfil/{id}', [AdminController::class, 'perfil'])->name('perfiles');
Route::post('/perfil/{id}', [AdminController::class, 'actualizarPerfil'])->name('actualizar_perfiles');

//semilleros
Route::get('/semilleros', [AdminController::class, 'listarSemilleros'])->name('listar_semilleros');
Route::get('/agregar_semilleros', [AdminController::class, 'agregarSemilleros'])->name('agregar_semilleros');

//eventos
Route::get('vista/registrar_eventos', [AdminController::class, 'vistaRegEventos'])->name('vista_reg_eventos');
Route::post('vista/registrar_eventos', [AdminController::class, 'registrarEventos'])->name('registrar_evento');

Route::get('vista/editar_eventos/{id}', [AdminController::class, 'vistaEditEventos'])->name('edit_eventos');
Route::post('vista/editar_eventos/{id}', [AdminController::class, 'editarEventos'])->name('editar_evento');

Route::get('/eliminar_eventos/{id}', [AdminController::class, 'eliminarEvento'])->name('eliminar_evento');
Route::get('/eliminar_evento/{id}', [AdminController::class, 'confirmacionEliminacionEvento']);

//agregar semilerros
Route::get('/agregar_semilleros', [AdminController::class, 'agregarSemilleros'])->name('agregar_semilleros');
Route::post('/agregar_semillero', [AdminController::class, 'agregarSemillero'])->name('agregar_semillero');

//actualizar semilleros
Route::get('/actualizar_semillero/{id}', [AdminController::class, 'vistaActualizarSemillero'])->name('vista_actualizar_semillero');
Route::post('/actualizar_semillero/{id}', [AdminController::class, 'actualizarSemillero'])->name('actualizar_semillero');

//eliminar semilleros
Route::get('/eliminar-semilleros/{id}', [AdminController::class,'eliminarSemillero'])->name('delete_sem');
Route::get('/eliminar-semillero/{id}', [AdminController::class,'eliminarSemilleroConfirmado'])->name('eliminar_sem_confirmado');

//ver participantes de un semillero
Route::get('/participantes-semillero/{id}', [AdminController::class,'vistaParticipantes'])->name('participantes_semillero');

//agregar participantes a un semillero
Route::get('/add-participantes-semillero/{id}', [AdminController::class,'addParticipantes'])->name('add_par_sem');

//vincular los participantes 1 a 1
Route::get('/vincular-semillerista-semillero/{num_identificacion}/{id}', [AdminController::class, 'vincularSemilleristaSemillero'])->name('vincular_sem_sem');

//desvincular participantes
Route::get('/desvincular_sem_sem/{num_identificacion}', [AdminController::class, 'desvincularSemillero'])->name('desvincular_sem_sem');

//actualizar info academica usuario
Route::get('/actualizar_informacion_academica_semillerista/{id}', [AdminController::class, 'vistaActualizarAcademicaSem'])->name('act_info_acad_sem');
Route::post('/actualizar_informacion_academica_semillerista/{id}', [AdminController::class, 'actualizarAcademicaSem'])->name('actualizar_acad_semillerista');

//coordinador semillero
Route::get('/coordinador-semillero/{id}', [AdminController::class, 'vistaCoordinadorSem'])->name('vista_coor_sem');

//lista de posibles coordinadores
Route::get('/nombrar-coordinador-semillero/{id}', [AdminController::class, 'nombrarCoordinador'])->name('vencular_coor_sem');

//vincular coordinador a semillero
Route::post('/nombrar_coordinador/{semillero_id}', [AdminController::class, 'nombrarCoordinadorSemillero'])->name('nombrar_coor_sem');

//desvincular coordinador de semillero
Route::get('/despedir_coordinador/{semillero_id}', [AdminController::class, 'despedirCoordinadorSemillero'])->name('destituir_coor_sem');

//rutas de usuarios
Route::get('/proyectos', [AdminController::class,'listarProyectos'])->name('proyectos_dir');

//Agregar
Route::get('/vista/agregar-proyectos', [AdminController::class,'vistaAgrProyectos'])->name('vista_agr_proy_dir');
Route::post('/vista/agregar-proyectos', [AdminController::class,'agregarProyecto'])->name('agregar_proyecto_dir');

//Editar
Route::get('vista/editar_proyectos/{id}', [AdminController::class, 'vistaEditProyectos'])->name('edit_proyectos_dir');
Route::post('vista/editar_proyectos/{id}', [AdminController::class, 'editarProyectos'])->name('editar_proyecto_dir');

//Eliminar
Route::get('/eliminar_proyectos/{id}', [AdminController::class, 'eliminarProyecto'])->name('eliminar_proyecto_dir');
Route::get('/eliminar_proyecto/{id}', [AdminController::class, 'confirmacionEliminacionProyecto_dir']);

//GENERAR REPORTES

//Generar reporte usuarios
Route::get('/reporte_usuarios', [ReportController::class, 'generarReporteUsuarios'])->name('usr_report');

//Generar reporte semilleros
Route::get('/reporte_semillero/{id}', [ReportController::class, 'generarReporteSemillero_admin'])->name('sem_report_dir');

//Generar reporte proyectos
Route::get('/reporte_proyectosA', [ReportController::class, 'generarReporteProyectosA'])->name('proyectosA_report');
Route::get('/reporte_proyectosAI/{id}', [ReportController::class, 'generarReporteProyectosIndividuaAI'])->name('proyectosAI_report');