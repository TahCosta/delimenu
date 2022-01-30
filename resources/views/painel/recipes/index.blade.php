@extends('adminlte::page')

@section('title', 'Receitas')

@section('content_header')
    <h1>
    Receitas 
      <a href="{{route('recipes.create')}}" class="btn btn-sm btn-success">Nova Receita</a>
    </h1>
@endsection
@section('content')
<div class="card">
  <div class="card-box responsive">

    <table class="table datatable table-striped table-hover nowrap" width="100%">
      <thead>
      <tr>
        <th>Nome</th>
        <th>Rendimento</th>
        <th>Medida</th>
        <th>Preço de Custo</th>
        <th>Categoria</th>
        <th>Ações</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($recipes as $recipe)
          <tr>
            <td>{{$recipe->item}}</td>
            <td>{{number_format($recipe->packsize)}}</td>
            <td>{{$recipe->measure}}</td>
            <td>R$ {{number_format($recipe->pack_cost,2,',','.')}}</td>
            <td>{{$recipe->category}}</td>

            <td>
              <a href="{{route('recipes.edit',['recipe' => $recipe->id])}}" class="btn btn-sm btn-info">Editar</a>

              <form method="POST" action="{{route('recipes.destroy',['recipe' => $recipe->id])}}" class="d-inline" onsubmit="event.preventDefault(); deleteInput('Tem certeza que deseja excluir a receita {{$recipe->item}}?',this)">
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