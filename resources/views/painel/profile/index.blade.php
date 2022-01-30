@extends('adminlte::page')
@section('title', 'Meu perfil')

@section('content_header')
    <h1>
      Meu perfil
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
  <form class="form-horizontal" method="POST" action="{{route('profile.update', ['profile' =>$user->id])}}">
    @method('PUT')
    @csrf       
      <div class="form-group row">
        
        <label class="col-form-label col-sm-3">Nome</label>
        <div class="col-sm-9">
          <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{$user->name}}" id="name" name="name" placeholder="Nome">
        </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-3">Email</label>
      <div class="col-sm-9">
        <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{$user->email}}" id="email" name="email" placeholder="Email" readonly>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-3">Alterar Senha</label>
      <div class="col-sm-9">
        <input type="password" class="form-control @error('password') is-invalid @enderror" value="" id="password" name="password" placeholder="Senha">
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-3">Confirmar nova senha</label>
      <div class="col-sm-9">
        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" value="" id="password_confirmation" name="password_confirmation" placeholder="Confirma Nova senha">
      </div>
    </div>

     
      <div class="form-group row">
          <label class="col-form-label col-sm-3"></label>
          <div class="col-sm-9">
            <input type="submit" class="btn btn-success" value="Alterar">
          </div>
      </div>
  </form>
</div></div>
@endsection