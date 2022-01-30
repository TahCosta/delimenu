@extends('adminlte::page')

@section('title', 'Fornecedores')

@section('content_header')
    <h1>
      Fornecedores 
      <a href="{{route('providers.create')}}" class="btn btn-sm btn-success">Adcionar Fornecedor</a>
    </h1>
@endsection
@section('content')
<div class="card">
  <div class="card-box responsive">
    <div class="table-responsive">
      <table class="table datatable table-striped table-hover nowrap" width="100%">
        <thead>
        <tr>
          <th>Fornecedor</th>
          <th>Endereço</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($providers as $provider)
            <tr>
              <td>{{$provider->provider}}</td>
              <td>{{$provider->address}}</td>
              <td>{{$provider->email}}</td>
              <td>{{$provider->phone}}</td>

              <td>
                <a href="{{route('providers.edit',['provider' => $provider->id])}}" class="btn btn-sm btn-info">Editar</a>

                <form method="POST" action="{{route('providers.destroy',['provider' => $provider->id])}}" class="d-inline" onsubmit="event.preventDefault(); deleteInput('Tem certeza que deseja excluir o fornecedor {{$provider->provider}}?',this)">
                  @method('DELETE')
                  @csrf
                  <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                </form>
              
              </td>
            </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div></div>

@endsection