@extends('layouts.app')
@section('content')

<div class="breadcrumps">
  Consultor <i class="fas fa-angle-right"></i> Edição
</div>

<div class="content__container">

  @include('layouts.flash-message')

  <form action="{{route('consultor.update', ['id' => $consultor->id])}}" method="POST">
    @csrf

    <div class="row">
      <div class="col-md-6 col-xl-4 form-group">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" value="{{$consultor->name}}" />
      </div>

      <div class="col-md-12">
        <button type="submit" class="btn btn-primary">
          SALVAR
        </button>
      </div>

    </div>
  </form>
</div>

@endsection
