@extends('adminlte::page')
@section('title', 'Novo Produto')

@section('content_header')
{{header('Content-Type: application/json')}}
    <h1>
      Novo Produto - Cadastro Ficha técnica
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
  @if(session('warning'))
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    {{session('warning')}}
    
  </div>
@endif


  <div class="card col-sm-12">
    <div class="card-body">
  <form class="form-horizontal" method="POST" action="{{route('products.store')}}">
    @csrf
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Nome Produto</label>
          <div class="col-sm-9">
            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" id="name" name="name" placeholder="Nome">
          </div>
        
      </div>
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Código de produto (PDV)</label>
          <div class="col-sm-9">
            <input type="number" class="form-control @error('pdv') is-invalid @enderror" value="{{old('pdv')}}" id="pdv" name="pdv" placeholder="xxxx">
          </div>
        
      </div>
      <div class="form-group row">
        <label class="col-form-label col-sm-3">Categoria</label>
        <div class="col-sm-9">
          <select class="form-control" id="category" name="category" >
            <option value="0" selected>Selecionar categoria</option>
            @foreach ($categories as $category)
            <option value="{{$category->id}}">{{$category->name}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group row">
        
        <label class="col-form-label col-sm-3">Tipo</label>
        <div class="col-sm-9">
          <div class="form-check-inline">
            <label class="form-check-label">
            <input type="radio" class="form-check-input @error('type') is-invalid @enderror" value="produto" id="type" name="type" @if(old('type') == 'produto'  || !old('type')) checked @endif>Produto
            </label>
          </div>
          <div class="form-check-inline">
            <label class="form-check-label">
              <input type="radio" class="form-check-input @error('type') is-invalid @enderror" value="topper" id="type" name="type"  @if(old('type') == 'topper') checked @endif>Complemento/Topper
            </label>
          </div>        
        </div>
    </div>

      
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Preço de venda</label>
          <div class="col-sm-3">
            <input type="number" class="form-control @error('sell') is-invalid @enderror" id="sell" name="sell" value="{{old('sell')}}" placeholder="xx,xx">
          </div>

          <label class="col-form-label offset-md-1 offset-sm-1 col-md-2 col-sm-2">Preço Custo</label>
          <div class="col-sm-3">
            <input type="text" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" value="{{old('cost')}}" placeholder="xx,xx" readonly>
          </div>
        
      </div>

      <div class="form-group row">

          <label class="col-form-label col-sm-3">Preço Sugerido </label>
          <div class="col-sm-3">
            <input type="text" class="form-control @error('profit') is-invalid @enderror" id="profit" name="profit" value="{{old('profit')}}" placeholder="xx,xx" readonly>
          </div>
          <label class="col-form-label offset-md-1 offset-sm-1 col-md-2 col-sm-2">Porcentagem lucro</label>
          <div class="col-sm-3">
            <input type="text" class="form-control @error('profitperc') is-invalid @enderror" id="profitperc" name="profitperc" value="{{old('profitperc')}}" placeholder="xx,xx" readonly>
          </div>

      </div>

      <div class="form-group row">

          <label class="col-form-label col-sm-3">Observações</label>
          <div class="col-sm-9">
            <textarea class="form-control @error('preparation') is-invalid @enderror" id="preparation" name="preparation" placeholder="Observações" cols="30" rows="10"></textarea>
          
          </div>

      </div>
     
      <div class="form-group row">
        <h3>Lista de ingredientes</h3>
      </div>
      <div class="row " id="list">
        <div class="card col-sm-12">
          <div class="card-box">
            <table class="table table-hover" id="tbList">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Qtd</th>
                  <th>Medida</th>
                  <th>Custo</th>
                  <th>Ação</th>
                </tr>
              </thead>
              <tbody>
                <tr class="d-none">
                  <td>
                    <select class="form-control" name="input[]" >
                      <option value="0" selected disabled>Selecionar item</option>
                      @foreach ($inputs as $input)
                      <option value="{{$input->id}}" >{{$input->item}}</option>
                      @endforeach
                    </select>
                  </td>
                <td><input type="number" class="form-control @error('ammount[]') is-invalid @enderror"  name="ammount[]" value="{{old('ammount[]')}}" placeholder="xxxx" ></td>
                <td name="inputMeasure"></td>
                <td name="inputCost"></td>

                <td>
                  <button type="button" onclick="removeItem(this)"class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Excluir ingrediente"><i class="bi bi-trash-fill"></i></i></button>
                </td>

                </tr>
                <tr>

                  <td>
                    <select class="form-control" name="input[]" >
                      <option value="0" selected disabled>Selecionar item</option>
                      @foreach ($inputs as $input)
                      <option value="{{$input->id}}" >{{$input->item}}</option>
                      @endforeach
                    </select>
                  </td>
                <td><input type="number" class="form-control @error('ammount[]') is-invalid @enderror"  name="ammount[]" value="{{old('ammount[]')}}" placeholder="xxxx" ></td>
                <td name="inputMeasure"></td>
                <td name="inputCost"></td>
                <td>
                  <button type="button" onclick="removeItem(this)"class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Excluir ingrediente"><i class="bi bi-trash-fill"></i></i></button>
                </td>
                </tr>
                
              </tbody>
              
            </table>
            <div class="d-flex flex-row-reverse mb-2">
              <button type="button" onclick="addItem()" class="btn btn-success">Adicionar Ingrediente <i class="bi bi-plus-lg"></i></button>
            </div>
            
          </div>
        </div>
      </div>
      <div class="d-flex flex-row-reverse mb-2">
        <a href="{{route('products.index')}}" class="btn btn-info">Voltar</a>
        <input type="submit" class="btn btn-success mr-1" value="Cadastrar Produto"> 
      </div>


  </form>
</div></div>

<script>

</script>
@endsection