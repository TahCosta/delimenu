@extends('adminlte::page')

@section('title', 'Categorias')

@section('content_header')
<div class="row">
  <div class="col-sm-6">
    <h1>
      Categorias Insumos
      <a href="{{route('category.create',['type' => 'input'])}}" class="btn btn-sm btn-success">Adcionar nova</a>
    </h1>
  </div>
  <div class="col-sm-6">
    <h1>
      Categorias Produtos
      <a href="{{route('category.create',['type' => 'product'])}}" class="btn btn-sm btn-success">Adcionar nova</a>
    </h1>
  </div>
</div>
    
@endsection
@section('content')
<div class="row">
  <div class="col-sm-4">
<div class="card">
  <div class="card-box">

    <table class="table table-hover">
      <thead>
      <tr>
    
        <th>Categoria</th>
        <th style="width:150px;">Ações</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($catInputs as $category)
          <tr>
       
            <td>{{$category->name}}</td>
            <td>
              <a href="{{route('category.edit',['category' => $category->id])}}" class="btn btn-sm btn-info">Editar</a>

              <form method="POST" action="{{route('category.destroy',['category' => $category->id])}}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
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
  </div>
    
  <div class="col-sm-4 offset-sm-2">
<div class="card">
  <div class="card-box">

    <table class="table table-hover">
      <thead>
      <tr>
        <th>Categoria</th>
        <th style="width:150px;">Ações</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($catProducts as $category)
          <tr>
            <td>{{$category->name}}</td>
            <td>
              <a href="{{route('category.edit',['category' => $category->id])}}" class="btn btn-sm btn-info">Editar</a>

              <form method="POST" action="{{route('category.destroy',['category' => $category->id])}}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
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
  </div>
</div>
@endsection