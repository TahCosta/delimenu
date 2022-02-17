@extends('adminlte::page')

@section('title', 'Integração Ifood')

@section('content_header')
    <h1>Integração Ifood</h1>
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

<div class="card col-sm-12">
  <div class="card-body">
  @if(!$company) 
  <p>Para efetuar a integração com o Ifood é necessário preencher o cadastro da sua empresa</p> 
  @elseif(!$integration)
    <p>Para efetuar a integração do Deli menu com o Ifood, é necessário que entre no portal do parceiro, vá em Aplicativos > Ativar aplicativo por código e digite o código abaixo</p>
    <h3>Código: {{$ifood->userCode}}</h3>
    <p>{{var_dump($ifood)}}</p>
    <p>Se preferir, <a href="https://portal.ifood.com.br/apps/code?c={{$ifood->userCode}}" target="_blank">clique aqui</a>  para ser direcionado ao portal do parceiro. </p>
    <p>Assim que ativar o aplicativo, você deverá informar no campo abaixo o código passado pelo ifood.</p>
    <form class="form-horizontal" method="POST" action="{{route('config.ifood.save')}}">
      @csrf
        <div class="form-group row">
          <label class="col-form-label col-sm-3">Código de autorização</label>
          <div class="col-sm-9">
            <input type="text" class="form-control @error('code') is-invalid @enderror" value="{{old('code')}}" id="code" name="code" placeholder="código">
          </div>
        </div>
        <input type="submit" class="btn btn-success mr-1" value="Cadastrar Produto"> 
    </form>
  @else
  
  <h4>Restaurantes Autorizados</h4>
  <ol>
    @foreach($ifood as $store)
    <li><h5>{{$store->name}}</h5></li>
    @endforeach
  </ol>
  <p>Está tudo ok com a integração do seu restaurante com o nosso sistema. Para trocar o restaurante autorizado, é necessário ir no portal do parceiro>aplicativos, no card do deli menu, clicar nos três pontinhos e selecionar a opção desabilitar, para que possamos gerar um novo código de autorização.</p>
  @endif
  </div>
</div>
@endsection