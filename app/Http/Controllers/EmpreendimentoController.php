<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empreendimento;

class EmpreendimentoController extends Controller
{
    public function index()
    {
        $empreendimento = Empreendimento::all();
        return view('empreendimento', compact(['empreendimento']));
    }

    public function create()
    {
        return view('empreendimento-add');
    }

    public function store(Request $request)
    {

        Empreendimento::create($request->only('name'));

        return redirect()->route('empreendimento')->with('success','Empreendimento cadastrada com sucesso');
    }

    public function edit($id)
    {
      $empreendimento = Empreendimento::find($id);
  
      return view('empreendimento-edit', compact(['empreendimento']));
    }

    public function update(Request $request, $id)
    {
        $empreendimento = Empreendimento::find($id);
        if ($empreendimento) {
            $empreendimento->name = $request->get('name');
            $empreendimento->save();
        }
        return redirect()->route('empreendimento')->with('success','Empreendimento alterado com sucesso');
    }

    public function destroy($id)
    {
        $empreendimento = Empreendimento::find($id);
        if(isset($empreendimento)){
            $empreendimento->delete();
        }
        return back()->with('success','Empreendimento apagado com sucesso');
    }
}
