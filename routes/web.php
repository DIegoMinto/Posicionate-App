<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\StatiticController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ContrasenaController;
use App\Http\Controllers\AreaController;

use App\Models\PlanesPago;

Route::get('/', function () {
    return view('welcome');
});

//RUTAS LOGIN
Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginVerify'])->name('login.verify');
});


//RUTAS REGISTRO DE PERSONAL

Route::get('/registro', [PersonaController::class, 'create'])->name('personas.create');
Route::post('/registro', [PersonaController::class, 'store'])->name('personas.store');
Route::get('/api/paises/{pais}/departamentos', function ($pais) {
    return App\Models\Departamento::where('id_pais', $pais)->get();
});

Route::get('/api/departamentos/{depto}/ciudades', function ($depto) {
    return App\Models\Ciudad::where('id_departamento', $depto)->get();
});

//RUTAS REGISTRO DOCENTE
Route::get('/registrodocente', [DocenteController::class, 'create'])->name('docentes.create');
Route::post('/registrodocente', [DocenteController::class, 'store'])->name('docentes.store');

//RUTAS REGISTRO ESTUDIANTE
Route::get('/inscripcion/{id_curso}/{id_personal}', [InscripcionController::class, 'showForm'])->name('inscripcion.public');
Route::post('/inscripcion/store', [InscripcionController::class, 'store'])->name('inscripcion.store');

Route::middleware(['auth', 'vigente'])->group(function () {

    //RUTAS DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('people')->name('people.')->group(function () {
        Route::get('/students', [DashboardController::class, 'students'])->name('index');
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('/staff', [DashboardController::class, 'staff'])->name('staff');
        });

    });
    Route::get('/wpsender', [DashboardController::class, 'wpsender'])->name('wpsender.index');

    //RUTAS CREACIÓN DE USUARIOS
    Route::middleware('role:admin,super_admin')->group(function () {
        Route::get('/userscreate', [UserController::class, 'create'])->name('users.create');
        Route::get('/userscreate/adduser/{id}', [UserController::class, 'create_user'])->name('users.create_user');
        Route::post('/userscreate/store', [UserController::class, 'store_user'])->name('users.store_user');
        //RUTAS ELIMINACIÓN DE USUARIOS
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::delete('/personas/{id}', [PersonaController::class, 'destroy'])->name('personas.destroy');
    });
    Route::middleware('auth')->group(function () {
        Route::get('/users/{id}/info', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/personal/{id}', [UserController::class, 'update'])->name('personal.update');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    //RUTAS MIDDLEWARE
    Route::post('/users/{id}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    //RUTAS CURSO
    Route::get('/programs', [DashboardController::class, 'programs'])->name('programs.index');
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/programs/create', [DashboardController::class, 'programsCreate'])->name('programs.create');
        Route::get('/programs/{id}/edit', [DashboardController::class, 'programsEdit'])->name('programs.edit');
        Route::post('/programs/store', [DashboardController::class, 'programsStore'])->name('programs.store');
        Route::get('/programs/{id}/payments-setup', [DashboardController::class, 'programsPaymentsSetup'])->name('programs.payments.setup');
        Route::patch('/programs/{id}/status', [DashboardController::class, 'updateStatus'])->name('programs.updateStatus');
        Route::patch('/programs/{id}/update', [DashboardController::class, 'programsUpdate'])->name('programs.update');
        Route::delete('/programs/{id}', [DashboardController::class, 'programsDestroy'])->name('programs.destroy');
        Route::delete('/modules/{id}', [ModuloController::class, 'destroy'])->name('modules.destroy');
    });

    Route::get('/programs/{id}/show', [DashboardController::class, 'programsShow'])->name('programs.show');
    Route::get('/curso/{id}/estudiantes', [InscripcionController::class, 'list'])->name('curso.estudiantes');

    //RUTAS PLANES
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::delete('/planes-pago/{id}', [PlanController::class, 'destroy'])->name('plans.destroy');
    });
    Route::get('/programs/{id}/payments-setup', [DashboardController::class, 'programsPaymentsSetup'])->name('programs.payments.setup');
    Route::post('/planes-pago/guardar', [PlanController::class, 'store'])->name('plans.store');
    Route::get('/planes-pago/{id}/detalles', [PlanController::class, 'showInstallments'])->name('plans.installments');


    //RUTAS CLASE
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/class/create', [ClassController::class, 'classCreate'])->name('class.create');
        Route::post('/class/store', [ClassController::class, 'classStore'])->name('class.store');
    });
    //CREACIONES
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/creations', [DashboardController::class, 'creations'])->name('creations.index');
        // Rutas para la gestión de Instituciones
        Route::resource('institutions', InstitucionController::class)->names('institutions');
        Route::resource('sedes', SedeController::class)->names('sedes');
    });

    //RUTAS MODULO
    Route::get('/modulo/crear', [ModuloController::class, 'create'])->name('modulo.create');
    Route::post('/modulo/guardar', [ModuloController::class, 'store'])->name('modulo.store');

    //RUTAS WHATSAPP
    Route::get('/wpsender', [WhatsappController::class, 'index'])->name('wpsender.index');
    Route::get('/whatsapp/qr', [WhatsappController::class, 'qr']);
    Route::get('/whatsapp/status/{instance}', [WhatsappController::class, 'status']);
    Route::get('/wpsender/send', [WhatsappController::class, 'viewSend'])->name('wpsender.send');
    Route::post('/whatsapp/send', [WhatsappController::class, 'send']);


    // RUTAS DOCENTE
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::prefix('teachers')->group(function () {
            Route::get('/', [DocenteController::class, 'teachers'])->name('teachers.index');
            Route::get('/{docente}', [DocenteController::class, 'show'])->name('docentes.show');
            Route::get('/{docente}/edit', [DocenteController::class, 'edit'])->name('docentes.edit');
            Route::put('/{docente}', [DocenteController::class, 'update'])->name('docentes.update');
            Route::delete('/teachers/{docente}', [DocenteController::class, 'destroy'])->name('docentes.destroy');
        });

    });

    //RUTAS ESTADÍSTICAS
    Route::resource('statitics', StatiticController::class)->names('statitics');

    //RUTAS ESTUDIANTES
    Route::get('/estudiantes/change/{id}', [InscripcionController::class, 'change'])->name('students.change');
    Route::post('/estudiantes/change/{id}', [InscripcionController::class, 'store_change'])->name('inscripcion.store_change');
    Route::get('/api/plan-detalles/{id}', function ($id) {
        return \App\Models\PlanCuotaDetalle::where('id_planes_pago', $id)->get();
    });
    Route::get('/estudiantes/facturacion/{id}', [InscripcionController::class, 'facturacion'])->name('students.facturacion');
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::delete('/students/{id}', [InscripcionController::class, 'destroy'])->name('students.destroy');
        Route::get('/{estudiante}/edit', [EstudianteController::class, 'edit'])->name('estudiantes.edit');
        Route::put('/estudiante/{estudiante}', [EstudianteController::class, 'update'])->name('estudiantes.update');
        Route::get('/pagos/{id}/edit', [InscripcionController::class, 'editPago'])->name('pagos.edit');
        Route::put('/pagos/{id}', [InscripcionController::class, 'updatePago'])->name('pagos.update');
    });
    Route::get('/students/{id}/info', [DashboardController::class, 'show_student'])->name('people.show');
    Route::post('/curso/agregar-estudiante', [InscripcionController::class, 'agregarEstudiante'])
        ->name('curso.agregar.estudiante');
    Route::post('/pagos/{id}/validar', [InscripcionController::class, 'validarPago'])->name('pagos.validar');

    // RUTAS PLANES
    Route::get('/plans/{id}/edit', [PlanController::class, 'edit'])->name('plans.edit');

    Route::put('/plans/{id}', [PlanController::class, 'update'])->name('plans.update');

    Route::get('/pagos/{id}/recibo', [InscripcionController::class, 'reciboPago'])->name('pagos.recibo');

    // RUTAS CONTRASEÑAS
    Route::controller(ContrasenaController::class)->group(function () {
        Route::get('/contrasenas', 'index')->name('contrasenas.index');
        Route::get('/contrasenas/crear', 'create')->name('contrasenas.create');
        Route::post('/contrasenas', 'store')->name('contrasenas.store');
        Route::delete('/contrasenas/{id}', 'destroy')->name('contrasenas.destroy');
    });

    //RUTAS AREAS
    Route::controller(AreaController::class)->group(function () {
        Route::get('/areas', 'index')->name('areas.index');
        Route::get('/areas/crear', 'create')->name('areas.create');
        Route::post('/areas', 'store')->name('areas.store');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/whatsapp/groups', [WhatsappController::class, 'getGroups']);
        Route::post('/whatsapp/groups/extract', [WhatsappController::class, 'extractGroupContacts']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('/whatsapp/groups', [WhatsappController::class, 'getGroups']);
        Route::post('/whatsapp/groups/extract', [WhatsappController::class, 'extractGroupContacts']);
        Route::post('/whatsapp/groups/export-excel', [WhatsappController::class, 'exportGroupContactsExcel']);
    });
});