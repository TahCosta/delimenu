@extends('adminlte::page')
@section('title', 'Editar Fornecedor')

@section('content_header')
    <h1>
      Editar Fornecedor
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
  <form class="form-horizontal" method="POST" action="{{route('providers.update', ['provider' =>$providers->id])}}">
    @method('PUT')
    @csrf
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Razão Social</label>
          <div class="col-sm-9">
            <input type="text" class="form-control @error('provider') is-invalid @enderror" value="{{$providers->provider}}" id="provider" name="provider" placeholder="Nome Fornecedor">
          </div>
        
      </div>
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Endereço</label>
          <div class="col-sm-9">
            <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{$providers->address}}" id="address" name="address" placeholder="Rua x" value="">
          </div>
        
      </div>
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Cnpj</label>
          <div class="col-sm-9">
            <input type="text" class="form-control @error('cnpj') is-invalid @enderror" value="{{$providers->cnpj}}" id="cnpj" name="cnpj" placeholder="xx.xxx.xxx\xxxx-xx" value="">
          </div>
        
      </div>

      
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">E-mail</label>
          <div class="col-sm-9">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{$providers->email}}" placeholder="E-mail">
          </div>
        
      </div>

      <div class="form-group row">

          <label class="col-form-label col-sm-3">Telefone</label>
          <div class="col-sm-9">
            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{$providers->phone}}" placeholder="Telefone" value="">
          </div>

      </div>

      <div class="form-group row">

          <label class="col-form-label col-sm-3">Observações</label>
          <div class="col-sm-9">
            <input type="text" class="form-control @error('obs') is-invalid @enderror" id="obs" name="obs" placeholder="Observações" value="{{$providers->obs}}">
          </div>

      </div>
     
      <div class="form-group row">
          <label class="col-form-label col-sm-3"></label>
          <div class="col-sm-9">
            <input type="submit" class="btn btn-success" value="Alterar">
            <a href="{{route('providers.index')}}" class="btn btn-info">Voltar</a>
          </div>
      </div>


  </form>
</div></div>

@endsection