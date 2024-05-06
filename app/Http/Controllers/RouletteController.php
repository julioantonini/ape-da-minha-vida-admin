<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\User;
use App\Lead;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\NewLeadMail;
use App\Mail\NewLeadGerenteMail;
use App\Mail\NewLeadInfoMail;

class RouletteController extends Controller
{

  public function store(Request $request)
  {
    /*$responseRecaptcha = $request->get('g-recaptcha-response');

    if (!$responseRecaptcha) {
      return redirect()->away($request->redirect_url);
    }

    $url = 'https://google.com/recaptcha/api/siteverify';
    $secret = '6LeEueUjAAAAAB4yxLK2KyGMvIx2BB-_ypQqMih_';
    $response = $request->get('g-recaptcha-response');
    $values = "secret={$secret}&response={$responseRecaptcha}";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $values);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);

    $parsedResponse = json_decode($response, true);

    if ($parsedResponse['success'] === false) {
      return redirect()->away($request->redirect_url);
    }*/

    if(!isset($request->email)){
      return redirect()->away($request->redirect_url);
    }


    $user = $this->verifyLead($request->email);
    if(!isset($user)){
      return redirect()->away($request->redirect_url);
    }

    $lead = new Lead();

    $lead->team_id = $user->team_id;
    $lead->user_id = $user->id;
    $lead->name = $request->nome;
    $lead->email = $request->email;
    $lead->phone = $request->telefone;
    $lead->comments = $request->mensagem;
    $lead->cpf = $request->cpf;
    $lead->birthdate = $request->data_nasc;
    $lead->income = $request->renda;
    $lead->empreendimento_id = $request->empreendimentoId;
    $lead->consultores_id = NULL;
    $lead->empreendimento = $request->empreendimentoName;
    $lead->sended = 1;
    $lead->origin = $request->origin ? ucfirst($request->origin) : null;

    if ($this->checkLead($lead)) {
      return redirect()->away($request->redirect_url);
    }

    $lead->save();

    Mail::to($user->email)->cc(['livdigitalmkt@gmail.com'])->send(new NewLeadMail($user, $lead));

    Mail::to('contato@vittapassos.com.br')->cc(['livdigitalmkt@gmail.com'])->send(new NewLeadInfoMail($lead));

    $gerentes = User::where('privilege_id', 2)->where('team_id', $user->team_id)->get();

    foreach($gerentes as $g){
      Mail::to($g->email)->cc(['livdigitalmkt@gmail.com'])->send(new NewLeadGerenteMail($g->name, $user->name));
    }

    return redirect()->away($request->redirect_url);
  }

  public function storeBild(Request $request)
  {

    if(!isset($request->email)){
      return redirect()->away($request->redirect_url);
    }


    $user = $this->verifyLead($request->email);
    if(!isset($user)){
      return redirect()->away($request->redirect_url);
    }

    $lead = new Lead();

    $lead->team_id = $user->team_id;
    $lead->user_id = $user->id;
    $lead->name = $request->name;
    $lead->email = $request->email;
    $lead->phone = $request->telefone;
    $lead->comments = $request->mensagem;
    $lead->cpf = $request->cpf;
    $lead->birthdate = $request->data_nasc;
    $lead->income = $request->renda;
    $lead->empreendimento_id = '92e3a75a-f7a2-45a1-ab75-5ed6f47c86e1';
    $lead->consultores_id = NULL;
    $lead->empreendimento = 'Collis Residence';
    $lead->sended = 1;

    $lead->save();

    return $request;
  }

  private function verifyLead($email)
   {
     $leadExists = Lead::where('email', $email)->latest('created_at')->first();

     if(isset($leadExists)){
       $to = Carbon::parse($leadExists->created_at);
       $from = Carbon::now();
       $diffInHours = $to->diffInHours($from);
       if($diffInHours < 24){
         $user = User::where('id', $leadExists->user_id)->first();
       }else{
         $user = $this->selectUser();
       }
       return $user;
     }

     return $this->selectUser();
   }

   private function selectUser()
   {

     //conta todas as equipes q tem corretor
     $qtdTeams = count(
       DB::table('teams')
       ->join('users', 'teams.id', '=', 'users.team_id')
       ->where('users.privilege_id', 1)
       ->select('teams.id')
       ->groupBy('teams.id')
       ->get()
     );

     if($qtdTeams == 0){
       return;
     }

     //conta todas as equipes q receberam lead na rodada
     $qtdTeamsReceived = count(
       DB::table('teams')
       ->join('users', 'teams.id', '=', 'users.team_id')
       ->where('users.privilege_id', 1)
       ->where('teams.received', 1)
       ->select('teams.id')
       ->groupBy('teams.id')
       ->get()
     );

     //se todas as equipes ja tiverem recebido, reseta a contagem
     if($qtdTeams === $qtdTeamsReceived){
       Team::where('received', 1)->update(['received' => 0]);
     }

     //Seleciona uma equipe para enviar o lead
     $selectedTeam = DB::table('teams')
     ->join('users', 'teams.id', '=', 'users.team_id')
     ->where('teams.received', 0)
     ->where('users.privilege_id', 1)
     ->select('teams.*')
     ->inRandomOrder()
     ->first();

     //atualiza essa equipe para recebido
     Team::where('id', $selectedTeam->id)->update(['received' => 1]);

     //conta todos os corretores da equipe
     $QtdUsersInTeam = User::where('privilege_id', 1)->where('team_id', $selectedTeam->id)->count();

     //conta todos os corretores da equipe que ja receberam lead
     $QtdUsersInTeamReceived = User::where('privilege_id', 1)->where('team_id', $selectedTeam->id)->where('received', 1)->count();

     //se todos os usuários da equipe ja tiverem recebido, reseta a contagem
     if($QtdUsersInTeam === $QtdUsersInTeamReceived){
       User::where('team_id', $selectedTeam->id)->update(['received' => 0]);
     }

     //pego o usuário que irá receber o lead
     $selectedUser = User::where('team_id', $selectedTeam->id)->where('privilege_id', 1)->where('received', 0)->inRandomOrder()->first();

     //marco que ele recebeu o lead
     $selectedUser->received = 1;
     $selectedUser->save();

     return $selectedUser;
   }

   private function checkLead(Lead $lead): bool
   {
     $leadSearch = Lead::where([
       ['email', '=', $lead->email],
       ['phone', '=', $lead->phone],
       ['empreendimento_id', '=', $lead->empreendimento_id],
     ])
       ->orderBy('created_at', 'DESC')
       ->first();

     if (!$leadSearch) {
       return false;
     }

     if (Carbon::now()->diffInMinutes($leadSearch->created_at) >= 10) {
       return false;
     }

     return true;
   }
}
