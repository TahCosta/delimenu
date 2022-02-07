@extends('adminlte::page')
@section('title', 'Editar Insumo')

@section('content_header')
    <h1>
      Editar Insumo
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
  <div class="card col-sm-12">
    <div class="card-body">
  <form class="form-horizontal" method="POST" action="{{route('inputs.update', ['input' =>$input->id])}}">
    @method('PUT')
    @csrf       
      <div class="form-group row">
        
        <label class="col-form-label col-sm-3">Item</label>
        <div class="col-sm-9">
          <input type="text" class="form-control @error('item') is-invalid @enderror" value="{{$input->item}}" id="item" name="item" placeholder="Item">
        </div>
      
    </div>
    <div class="form-group row">
      
        <label class="col-form-label col-sm-3">Medida</label>
        <div class="col-sm-9">
          <div class="form-check-inline">
            <label class="form-check-label">
            <input type="radio" class="form-check-input @error('measure') is-invalid @enderror" value="un" id="measure" name="measure" placeholder="measure" @if($input->measure == 'un') checked @endif>Unidade
            </label>
          </div>
          <div class="form-check-inline">
            <label class="form-check-label">
            <input type="radio" class="form-check-input @error('measure') is-invalid @enderror" value="kg" id="measure" name="measure" placeholder="measure" @if($input->measure == 'kg') checked @endif>Quilo
            </label>
          </div>
          <div class="form-check-inline">
            <label class="form-check-label">
            <input type="radio" class="form-check-input @error('measure') is-invalid @enderror" value="g" id="measure" name="measure" placeholder="measure"  @if($input->measure == 'g') checked @endif >Grama
            </label>
          </div>
          <div class="form-check-inline">
            <label class="form-check-label">
            <input type="radio" class="form-check-input @error('measure') is-invalid @enderror" value="l" id="measure" name="measure" placeholder="measure" @if($input->measure == 'l') checked @endif>Litro
            </label>
          </div>            
          <div class="form-check-inline">
            <label class="form-check-label">
            <input type="radio" class="form-check-input @error('measure') is-invalid @enderror" value="ml" id="measure" name="measure" placeholder="measure" @if($input->measure == 'ml') checked @endif>Mililitro
            </label>
          </div>            
        </div>
    </div>

    <div class="form-group row">
      
      <label class="col-form-label col-sm-3">Tamanho pacote</label>
      <div class="col-sm-9">
        <input type="number" step="0.01" class="form-control @error('packsize') is-invalid @enderror" value="{{$input->packsize}}" id="packsize" name="packsize" placeholder="Peso/unidades/litros por pacote">
      </div>
    
    </div>
    <div class="form-group row">
      
      <label class="col-form-label col-sm-3">Preço do pacote</label>
      <div class="col-sm-9">
        <input type="number" step="0.01" class="form-control @error('pack_cost') is-invalid @enderror" value="{{$input->pack_cost}}" id="pack_cost" name="pack_cost" placeholder="12,34">
      </div>
    
    </div>
 
  <div class="form-group row">
      
    <label class="col-form-label col-sm-3">Categoria</label>
    <div class="col-sm-9">
      <select class="form-control" id="category" name="category" >
        @if ($input->category_id === 0)
          <option value="0" @if(!old('category')) selected @endif>Selecionar categoria</option> 
        @endif
        @foreach ($categories as $category)
        <option value="{{$category->id}}" @if($input->category_id == $category->id) selected @endif>{{$category->name}}</option>
      @endforeach
        
        
      </select>
    </div>
  
</div>

<div class="form-group row">    
  <label class="col-form-label col-sm-3">Fornecedor</label>
  <div class="col-sm-9">
    <select class="form-control" id="provider" name="provider" >
      @if ($input->provider_id === 0)
          <option value="0" @if(!old('category')) selected @endif>Selecionar Fornecedor</option> 
        @endif
        @foreach ($providers as $provider)
        <option value="{{$provider->id}}" @if($input->provider_id == $provider->id) selected @endif>{{$provider->provider}}</option>
      @endforeach
    </select>
  </div>

</div>
     
      <div class="form-group row">
          <label class="col-form-label col-sm-3"></label>
          <div class="col-sm-9">
            <input type="submit" class="btn btn-success" value="Alterar">
            <a href="{{route('inputs.index')}}" class="btn btn-info">Voltar</a>
          </div>
      </div>

     


  </form>
</div></div>
@endsection