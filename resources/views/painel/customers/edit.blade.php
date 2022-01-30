@extends('adminlte::page')
@section('title', 'Editar Cliente')

@section('content_header')
    <h1>
      Editar Cliente
    </h1>  
@endsection
@section('content')

  @if($errors->any())
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <h5><i class="icon fas fa-ban"></i> Ocorreu um erro na alteração</h5>
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
  <form class="form-horizontal" method="POST" action="{{route('customers.update', ['customer' =>$customer->id])}}">
    @method('PUT')
    @csrf       
      <div class="form-group row">
        
        <label class="col-form-label col-sm-3">Nome</label>
        <div class="col-sm-9">
          <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{$customer->name}}" id="name" name="name" placeholder="Nome">
        </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-3">Email</label>
      <div class="col-sm-9">
        <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{$customer->email}}" id="email" name="email" placeholder="Email">
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-3">Endereço</label>
      <div class="col-sm-9">
        <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{$customer->address}}" id="address" name="address" placeholder="Endereço">
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-3">Telefone</label>
      <div class="col-sm-9">
        <input type="number" class="form-control @error('phone') is-invalid @enderror" value="{{$customer->phone}}" id="phone" name="phone" placeholder="Telefone">
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-3">Whatsapp</label>
      <div class="col-sm-9">
        <input type="number" class="form-control @error('whatsapp') is-invalid @enderror" value="{{$customer->whatsapp}}" id="whatsapp" name="whatsapp" placeholder="Whatsapp">
      </div>
    </div>
     
      <div class="form-group row">
          <label class="col-form-label col-sm-3"></label>
          <div class="col-sm-9">
            <input type="submit" class="btn btn-success" value="Alterar">
            <a href="{{route('customers.index')}}" class="btn btn-info">Voltar</a>
          </div>
      </div>
  </form>
</div></div>
@endsection