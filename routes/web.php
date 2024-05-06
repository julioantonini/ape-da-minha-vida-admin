<?php

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


Auth::routes([
  'register' => false,
  'reset' => false,
  'verify' => false,
  'confirm' => false,
]);


Route::post('/lead-insert', 'RouletteController@store')->name('lead.insert');

Route::get('/lead-insert-bild', 'RouletteController@storeBild')->name('lead.insert');

Route::get('/check/{user_id}/{lead_id}', 'LeadController@show')->name('lead.check');

Route::get('/cron', 'CronController@index');

Route::group(['middleware' => 'auth'], function()
{
  Route::get('logout', 'Auth\LoginController@logout')->name('logout');
  Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
  Route::get('/', 'FunnelController@index')->name('home');

  Route::get('/team', 'TeamController@index')->name('team');
  Route::get('/team/add', 'TeamController@create')->name('team.create');
  Route::post('/team', 'TeamController@store')->name('team.store');
  Route::get('/team/{id}', 'TeamController@edit')->name('team.edit');
  Route::post('/team/{id}', 'TeamController@update')->name('team.update');
  Route::get('/team/destroy/{id}', 'TeamController@destroy')->name('team.destroy');

  Route::get('/user', 'UserController@index')->name('user');
  Route::get('/user/add', 'UserController@create')->name('user.create');
  Route::post('/user', 'UserController@store')->name('user.store');
  Route::get('/user/{id}', 'UserController@edit')->name('user.edit');
  Route::post('/user/{id}', 'UserController@update')->name('user.update');
  Route::get('/user/destroy/{id}', 'UserController@destroy')->name('user.destroy');
  Route::post('/user/destroy/move', 'UserController@moveAndDestroy')->name('user.movedestroy');

  Route::get('/lead', 'LeadController@index')->name('lead');
  Route::get('/lead/add', 'LeadController@create')->name('lead.create');
  Route::post('/lead', 'LeadController@store')->name('lead.store');
  Route::get('/lead/{id}', 'LeadController@edit')->name('lead.edit');
  Route::post('/lead/{id}', 'LeadController@update')->name('lead.update');
  Route::post('/lead/', 'LeadController@updateConsultor')->name('lead.updateConsultor');
  Route::get('/lead/destroy/{id}', 'LeadController@destroy')->name('lead.destroy');

  Route::get('/export', 'ExportController@index')->name('export');
  Route::post('/export', 'ExportController@store')->name('export.store');

  Route::get('/funnel', 'FunnelController@index')->name('funnel');
  Route::post('/funnel/update', 'FunnelController@update')->name('funnel.update');

  Route::get('/lead-info/{id}', 'LeadInfoController@show')->name('lead.info');
  Route::post('/lead-info', 'LeadInfoController@store')->name('lead.info.create');

  Route::get('/consultor', 'ConsultorController@index')->name('consultor');
  Route::get('/consultor/add', 'ConsultorController@create')->name('consultor.create');
  Route::get('/consultor/{id}', 'ConsultorController@edit')->name('consultor.edit');
  Route::post('/consultor', 'ConsultorController@store')->name('consultor.store');
  Route::post('/consultor/{id}', 'ConsultorController@update')->name('consultor.update');
  Route::get('/consultor/destroy/{id}', 'ConsultorController@destroy')->name('consultor.destroy');
  Route::get('/consultores', 'ConsultorController@show')->name('consultor.info');

  Route::get('/empreendimento', 'EmpreendimentoController@index')->name('empreendimento');
  Route::get('/empreendimento/add', 'EmpreendimentoController@create')->name('empreendimento.create');
  Route::get('/empreendimento/{id}', 'EmpreendimentoController@edit')->name('empreendimento.edit');
  Route::post('/empreendimento', 'EmpreendimentoController@store')->name('empreendimento.store');
  Route::post('/empreendimento/{id}', 'EmpreendimentoController@update')->name('empreendimento.update');
  Route::get('/empreendimento/destroy/{id}', 'EmpreendimentoController@destroy')->name('empreendimento.destroy');
});





