<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Lead;
use App\User;
use App\Team;
use App\Consultores;
use App\Empreendimento;

class DashboardController extends Controller
{

  public function __construct(){
    $this->middleware('userCheck');
  }

  public function index(Request $request)
  {
    if($request->query('first-date')&&$request->query('last-date')){
      $firstDate = Carbon::createFromFormat('d/m/Y', $request->query('first-date'))->format('Y-m-d 00:00:00');
      $lastDate = Carbon::createFromFormat('d/m/Y', $request->query('last-date'))->format('Y-m-d 23:59:59');
    }else{
      $firstDate =  Carbon::now()->startOfMonth()->toDateTimeString();
      $lastDate =  Carbon::now()->endOfMonth()->toDateTimeString();
    }

    if($request->query('empreendimento')){
      $empreendimento = $request->query('empreendimento');
    }else{
      $empreendimento = "";
    }


    if(auth()->user()->privilege_id === 3){
      $qtdLead = Lead::whereBetween('created_at', [$firstDate, $lastDate])->when(!empty($request->query('empreendimento')) , function ($query) use($empreendimento){
        return $query->where('empreendimento_id',$empreendimento);
      })->count();

      $qtdSell = Lead::whereBetween('updated_at', [$firstDate, $lastDate])->where('funnel_id', 7)->when(!empty($request->query('empreendimento')) , function ($query) use($empreendimento){
        return $query->where('empreendimento_id',$empreendimento);
      })->count();


      $qtdLeadByDays = Lead::whereBetween('created_at', [$firstDate, $lastDate])->when(!empty($request->query('empreendimento')) , function ($query) use($empreendimento){
        return $query->where('empreendimento_id',$empreendimento);
      })->orderBy('created_at')
      ->get()
      ->groupBy(function($date) {
        return Carbon::parse($date->created_at)->format('Y-m-d');
      });


      $qtdLeadByUser = User::where('privilege_id', 1)->with(['team'])->withCount([
        'leads' => function ($query) use ($firstDate, $lastDate) {
          $query->whereBetween('created_at', [$firstDate, $lastDate]);
        }])
        ->get()->sortBy('name')->sortBy('team_id');

      $qtdLeadByTeam = Team::withCount([
        'leads' => function ($query) use ($firstDate, $lastDate) {
          $query->whereBetween('created_at', [$firstDate, $lastDate]);
        }])
        ->get()->sortBy('name');
    }else{
      $qtdLead = Lead::where('team_id', auth()->user()->team_id)->whereBetween('created_at', [$firstDate, $lastDate])->when(!empty($request->query('empreendimento')) , function ($query) use($empreendimento){
        return $query->where('empreendimento_id',$empreendimento);
      })->count();
      $qtdSell = Lead::where('team_id', auth()->user()->team_id)->whereBetween('updated_at', [$firstDate, $lastDate])->where('funnel_id', 7)->when(!empty($request->query('empreendimento')) , function ($query) use($empreendimento){
        return $query->where('empreendimento_id',$empreendimento);
      })->count();


      $qtdLeadByDays = Lead::where('team_id', auth()->user()->team_id)->whereBetween('created_at', [$firstDate, $lastDate])->when(!empty($request->query('empreendimento')) , function ($query) use($empreendimento){
        return $query->where('empreendimento_id',$empreendimento);
      })->orderBy('created_at')
      ->get()
      ->groupBy(function($date) {
        return Carbon::parse($date->created_at)->format('Y-m-d');
      });


      $qtdLeadByUser = User::where('team_id', auth()->user()->team_id)->where('privilege_id', 1)->with(['team'])->withCount([
        'leads' => function ($query) use ($firstDate, $lastDate) {
          $query->whereBetween('created_at', [$firstDate, $lastDate]);
        }])
        ->get()->sortBy('name')->sortBy('team_id');

      $qtdLeadByTeam = [];
    }

    $qtdLeadByConsultor = Consultores::withCount([
      'leads' => function ($query) use ($firstDate, $lastDate) {
        $query->whereBetween('created_at', [$firstDate, $lastDate]);
      }])
      ->get()->sortBy('name');

    $empreendimentos = Empreendimento::all();

    return view('dashboard', compact(['firstDate', 'lastDate','qtdLead','qtdSell','qtdLeadByDays','qtdLeadByUser','qtdLeadByTeam', 'qtdLeadByConsultor', 'empreendimentos']));
  }


  public function create()
  {
    //
  }


  public function store(Request $request)
  {
    //
  }


  public function show($id)
  {
    //
  }


  public function edit($id)
  {
    //
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    //
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    //
  }
}
