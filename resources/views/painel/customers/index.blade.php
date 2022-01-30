@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <h1>
      Clientes 
      <a href="{{route('customers.create')}}" class="btn btn-sm btn-success">Adcionar Cliente</a>
    </h1>
@endsection
@section('content')
<div class="card">
  <div class="card-box">

    <table class="table table-hover">
      <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Endereço</th>
        <th>Telefone</th>
        <th>Whatsapp</th>
        <th>Ações</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($customers as $customer)
          <tr>
            <td>{{$customer->name}}</td>
            <td>{{$customer->email}}</td>
            <td>{{$customer->address}}</td>
            <td>{{$customer->phone}}</td>
            <td>{{$customer->whatsapp}}</td>


          
            <td>
              <a href="{{route('customers.edit',['customer' => $customer->id])}}" class="btn btn-sm btn-info">Editar</a>

              <form method="POST" action="{{route('customers.destroy',['customer' => $customer->id])}}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
              </form>
           
            </td>
          </tr>
      @endforeach
      </tbody>
    </table>
  </div></div>
    {{$customers->links()}}
@endsection