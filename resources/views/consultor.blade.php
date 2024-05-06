@extends('layouts.app')
@section('content')

<div class="breadcrumps">
  Consultores de campo
</div>

<div class="content__container">

  @include('layouts.flash-message')

  <div class="table-responsive">
    <table class="datatables table table-hover dataTable table-bordered table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th style="width:66px">Ações</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($consultores as $c)
          <tr>
            <td>{{$c->name}}</td>
            <td>
              <a title="Editar" href="{{route('consultor.edit', ['id' => $c->id])}}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
              <button type="button" onClick="sweetDelete('Apagar equipe','Deseja realmente apagar a equipe <br/>{{$c->name}} ?','{{route('consultor.destroy', ['id' => $c->id])}}')" title="Apagar" class="btn btn-primary"><i class="fas fa-trash-alt"></i></button>
            </td>
          </tr>
        @endforeach

      </table>
    </div>
  </div>

  @endsection
