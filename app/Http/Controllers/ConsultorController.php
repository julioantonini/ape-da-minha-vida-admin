<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consultores;

class ConsultorController extends Controller
{
    public function show()
    {
        $consultores = Consultores::all();
        return $consultores;
    }

    public function index()
    {
        $consultores = Consultores::all();
        return view('consultor', compact(['consultores']));
    }

    public function create()
    {
        return view('consultor-add');
    }

    public function store(Request $request)
    {

        Consultores::create($request->only('name'));

        return redirect()->route('consultor')->with('success','Equipe cadastrada com sucesso');
    }

    public function edit($id)
    {
      $consultor = Consultores::find($id);
  
      return view('consultor-edit', compact(['consultor']));
    }

    public function update(Request $request, $id)
    {
        $consultor = Consultores::find($id);
        if ($consultor) {
            $consultor->name = $request->get('name');
            $consultor->save();
        }
        return redirect()->route('consultor')->with('success','Consultor alterado com sucesso');
    }

    public function destroy($id)
    {
        $consultor = Consultores::find($id);
        if(isset($consultor)){
            $consultor->delete();
        }
        return back()->with('success','Consultor apagado com sucesso');
    }
}
