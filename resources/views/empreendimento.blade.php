@extends('layouts.app')
@section('content')

<div class="breadcrumps">
  Empreendimento
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

        @foreach ($empreendimento as $e)
          <tr>
            <td>{{$e->name}}</td>
            <td>
              <a title="Editar" href="{{route('empreendimento.edit', ['id' => $e->id])}}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
              <button type="button" onClick="sweetDelete('Apagar equipe','Deseja realmente apagar a equipe <br/>{{$e->name}} ?','{{route('empreendimento.destroy', ['id' => $e->id])}}')" title="Apagar" class="btn btn-primary"><i class="fas fa-trash-alt"></i></button>
            </td>
          </tr>
        @endforeach

      </table>
    </div>
  </div>

  @endsection
