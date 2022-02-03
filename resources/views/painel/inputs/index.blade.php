@extends('adminlte::page')

@section('title', 'Insumos')

@section('content_header')
    <h1>
      Insumos 
      <a href="{{route('inputs.create')}}" class="btn btn-sm btn-success">Adcionar Insumo</a>
    </h1>
@endsection
@section('content')
<div class="card">
  <div class="card-box responsive">

    <table class="table datatable table-striped table-hover nowrap" width="100%">
      <thead>
      <tr>
        <th>Item</th>
        <th>Tamanho Pcte</th>
        <th>Preço Pcte</th>
        <th>Categoria</th>
        <th>Fornecedor</th>
        <th>Ações</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($inputs as $input)
          <tr>
            <td>{{$input->item}}</td>
            <td>{{number_format($input->packsize)}} {{$input->measure}}</td>
            <td>R$ {{number_format($input->pack_cost,2,',','.')}}</td>
            <td>{{$input->category_id == 0 ?'Sem Categoria': $input->category}}</td>
            <td>{{$input->provider_id == 0 ?'Sem Fornecedor': $input->provider}}</td>
          
            <td>
              <a href="{{route('inputs.edit',['input' => $input->id])}}" class="btn btn-sm btn-info">Editar</a>

              <form method="POST" action="{{route('inputs.destroy',['input' => $input->id])}}" class="d-inline" onsubmit="event.preventDefault(); deleteInput('Tem certeza que deseja excluir o insumo {{$input->item}}?',this)">
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
</div>
@endsection