<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/info', function () {
    return view('info');
});

/*
Route::get('/page', function () {
    return view('page');
});
*/

Route::get('/reserva/{idReserva}', 'ReservaController@index');
Route::get('/hospedes/{idReserva}', 'ReservaController@hospedes');
Route::get('/hospede/{idReserva}/{idHospede}/{pos}', 'ReservaController@cadastro');
Route::post('/hospede', 'ReservaController@salvar');
Route::get('/','ReservaController@home');
Route::get('/camera/{idCliente}/{idReserva}', 'ReservaController@camera');
Route::get('/camera/{idCliente}', 'ReservaController@camera');
Route::get('/camera', 'ReservaController@camera');

Route::get('/upload/{id}', function($id = null){
    //Log::debug('>' . $id . 'x' . ((new DateTime())->format('Ymd')));
    if ($id !== (new DateTime())->format('Ymd'))  return view('home');
    return view('upload');
});

Route::post('/upload', function(Request $request){

    $request->validate([
        'imageUpload' => 'required|image|mimes:png|max:2048',
    ]);

    $image = $request->file('imageUpload');
    $name =  pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

    if ((strlen($name) !== 5) || (intval($name) == 0)) return view('upload');
    
    $folder = storage_path('images/logos/');
    $request->imageUpload->move($folder, $image->getClientOriginalName());

    return view('upload');
});

Route::get('/logo/{filename}', function($filename){
    
    $path = storage_path('images/logos/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;

});
