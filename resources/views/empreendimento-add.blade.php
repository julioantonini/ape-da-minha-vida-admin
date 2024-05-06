@extends('layouts.app')
@section('content')

<div class="breadcrumps">
  Empreendimento <i class="fas fa-angle-right"></i> Cadastro
</div>

<div class="content__container">

  @include('layouts.flash-message')

  <form action="{{route('empreendimento.store')}}" method="POST">
    @csrf

    <div class="row">
      <div class="col-md-6 col-xl-4 form-group">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" value="{{old('name')}}" />
        <span>Copie o codigo para colar na sua Landing page</span>
      </div>

      <div class="col-md-12">
        <button type="submit" class="btn btn-primary">
          CADASTRAR
        </button>
      </div>

    </div>
  </form>
</div>

@endsection
