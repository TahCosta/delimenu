@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <h1>
      Produtos 
      <a href="{{route('products.create')}}" class="btn btn-sm btn-success">Novo Produto</a>
    </h1>
@endsection
@section('content')
<div class="card">
  <div class="card-box responsive">

  <table class="table datatable table-striped table-hover nowrap" width="100%">
      <thead>
      <tr>
        <th>PDV</th>
        <th>Nome</th>
        <th>Categoria</th>
        <th>Tipo</th>
        <th>Preço de Custo</th>
        <th>Preço de Venda</th>
        <th>Ações</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($products as $product)
          <tr>
            <td>{{$product->pdv}}</td>
            <td>{{$product->product}}</td>
            <td>{{$product->category}}</td>
            <td>{{$product->type}}</td>
            <td>R$ {{number_format($product->cost,2,',','.')}}</td>
            <td>R$ {{number_format($product->sell,2,',','.')}}</td>

            <td>
              <a href="{{route('products.edit',['product' => $product->id])}}" class="btn btn-sm btn-info">Editar</a>

              <form method="POST" action="{{route('products.destroy',['product' => $product->id])}}" class="d-inline" onsubmit="event.preventDefault(); deleteInput('Tem certeza que deseja excluir o produto {{$product->product}}?',this)">
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
@endsection