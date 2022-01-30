@extends('adminlte::page')
@section('title', 'Nova Categoria')

@section('content_header')
    <h1>
      Nova Categoria de @if ($type == 'input') Insumo @else Produto @endif

              
      
    </h1>
@endsection
@section('content')

  @if($errors->any())
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <h5><i class="icon fas fa-ban"></i> Ocorreu um erro no cadastro</h5>
        <ul>
        @foreach ($errors->all() as $error)
           <li>{{$error}}</li> 
        @endforeach
        </ul>
      </div>
  @endif
  @if(session('warning'))
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{session('warning')}}
    
  </div>
@endif


  <div class="card col-sm-8">
    <div class="card-body">
  <form class="form-horizontal" method="POST" action="{{route('category.store', ['type' => $type])}}">
    @csrf
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Categoria</label>
          <div class="col-sm-9">
            <input type="text" class="form-control @error('item') is-invalid @enderror" value="{{old('item')}}" id="item" name="item" placeholder="Nome Categoria">
          </div>
        
      </div>
     
      <div class="form-group row">
          <label class="col-form-label col-sm-3"></label>
          <div class="col-sm-9">
            <input type="submit" class="btn btn-success" value="Cadastrar">
            <a href="{{route('category.index')}}"  class="btn btn-info">Voltar</a>
          </div>
      </div>

  </form>
</div></div>
@endsection