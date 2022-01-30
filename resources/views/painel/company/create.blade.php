@extends('adminlte::page')
@section('title', 'Minha Empresa')

@section('content_header')
    <h1>
      Minha Empresa
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



  <div class="card col-sm-8">
    <div class="card-body">
  <form class="form-horizontal" method="POST" action="{{route('company.save')}}">
    @method('PUT')
    @csrf
    <div class="form-group row">
        
      <label class="col-form-label col-sm-3">Razão Social</label>
      <div class="col-sm-9">
        <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" id="name" name="name" placeholder="Razão Social">
      </div>
    
  </div>

  
  <div class="form-group row">
    
      <label class="col-form-label col-sm-3">Endereço</label>
      <div class="col-sm-9">
        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{old('address')}}" placeholder="Endereço">
      </div>
    
  </div>
 
  <div class="form-group row">
    
      <label class="col-form-label col-sm-3">E-mail</label>
      <div class="col-sm-9">
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{old('email')}}" placeholder="E-mail">
      </div>
    
  </div>

  <div class="form-group row">

      <label class="col-form-label col-sm-3">Cnpj</label>
      <div class="col-sm-9">
        <input type="number" class="form-control @error('cnpj') is-invalid @enderror" id="cnpj" name="cnpj" value="{{old('cnpj')}}" placeholder="11.111.111/1111-11">
      </div>

  </div>
    
    <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Telefone</label>
          <div class="col-sm-9">
            <input type="tel" class="form-control @error('phone') is-invalid @enderror" value="{{old('phone')}}" id="phone" name="phone" placeholder="Telefone">
          </div>
        
    </div>      
     
      <div class="form-group row">
          <label class="col-form-label col-sm-3"></label>
          <div class="col-sm-9">
            <input type="submit" class="btn btn-success" value="Cadastrar">
          </div>
      </div>


  </form>
</div></div>

@endsection