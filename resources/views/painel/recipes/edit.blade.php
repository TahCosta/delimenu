@extends('adminlte::page')
@section('title', 'Ver Receita')

@section('content_header')
{{header('Content-Type: application/json')}}
    <h1>
      Ficha técnica - {{$item->item}}
    </h1>
@endsection
@section('content')

  @if($errors->any())
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <h5><i class="icon fas fa-ban"></i> Ocorreu um erro no cadastro da receita</h5>
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
  <form class="form-horizontal" method="POST" action="{{route('recipes.update',['recipe' => $item->id])}}">
  @method('PUT')
    @csrf
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Nome Receita</label>
          <div class="col-sm-9">
            <input type="text" class="form-control @error('item') is-invalid @enderror" value="{{$item->item}}" id="item" name="item" placeholder="Nome">
          </div>
        
      </div>
      <div class="form-group row">
        <label class="col-form-label col-sm-3">Categoria</label>
        <div class="col-sm-9">
          <select class="form-control" id="category" name="category" >
            @foreach ($categories as $category)
            <option value="{{$category->id}}" @if($category->id == $item->category_id) Selected @endif>{{$category->name}}</option>
            @endforeach
          </select>
        </div>
      </div>  
      <div class="form-group row">
        
          <label class="col-form-label col-sm-3">Rendimento</label>
          <div class="col-sm-3">
            <input type="number" class="form-control @error('yield') is-invalid @enderror" id="yield" name="yield" value="{{$item->packsize}}" placeholder="xxxx">
          </div>

          <label class="col-form-label offset-md-1 offset-sm-1 col-md-2 col-sm-2">Medida</label>
          <div class="col-sm-3">
          <select class="form-control" id="measure" name="measure" >
          @foreach ($measures as $measure)
            <option value="{{$measure}}" @if($measure == $item->measure) Selected @endif>{{$measure}}</option>
            @endforeach
          </select>
          </div>
        
      </div>

      <div class="form-group row">

          <label class="col-form-label col-sm-3">Custo</label>
          <div class="col-sm-3">
            <input type="text" class="form-control @error('pack_cost') is-invalid @enderror" id="pack_cost" name="pack_cost" value="R$ {{$item->pack_cost}}" placeholder="xx,xx" readonly >
          </div>
          <label class="col-form-label offset-md-1 offset-sm-1 col-md-2 col-sm-2">Custo/ unidade</label>
          <div class="col-sm-3">
            <input type="text" class="form-control @error('unity_cost') is-invalid @enderror" id="unity_cost" name="unity_cost" value="R$ {{$item->unity_cost}}" placeholder="xx,xx" readonly >
          </div>

      </div>

      <div class="form-group row">

          <label class="col-form-label col-sm-3">Modo de preparo</label>
          <div class="col-sm-9">
            <textarea class="form-control @error('preparation') is-invalid @enderror" id="preparation" name="preparation" placeholder="Modo de preparo"  cols="30" rows="10">{{$item->preparation}}</textarea>
          
          </div>

      </div>
     
      <div class="form-group row">
        <h3>Lista de ingredientes</h3>
      </div>
      <div class="row " id="list">
        <div class="card col-sm-12">
          <div class="card-box">
            <div style="width:100%; overflow-x:auto">
              <table class="table table-hover table-condensed" id="tbList" >
                <thead>
                  <tr>
                    <th>Item</th>
                    <th>Qtd</th>
                    <th>Medida</th>
                    <th>Custo</th>
                  </tr>
                </thead>
                <tbody>
                <tr class="d-none"></tr>
                  @foreach($recipes as $recipe)
                  <tr>
                    <td>
                      <select class="form-control" name="input[]" readonly>
                        <option value="{{$recipe->id_item}}" selected disabled >{{$recipe->item}}</option>
                      </select>
                    </td>
                  <td><input type="number" class="form-control @error('ammount[]') is-invalid @enderror"  name="ammount[]" value="{{$recipe->ammount}}" placeholder="xxxx" readonly></td>
                  <td ><input type="text" class="form-control" data-input='measure' value="{{$recipe->measure}}" name="inputMeasure[]" readonly></td>
                  <td ><input type="text" class="form-control" data-input='cost' value="R$ {{floatval($recipe->unity_cost)*floatval($recipe->ammount)}}" name="inputMeasure[]" readonly></td>
                  



                  </tr>
                  @endforeach
                </tbody>
                
              </table>
            </div>
            
          </div>
        </div>
      </div>



          <div class="d-flex flex-row-reverse mb-2">
            <a href="{{route('recipes.index')}}" class="btn btn-info">Voltar</a>
            <input type="submit" class="btn btn-success mr-1" value="Salvar Alteração">
          </div>



  </form>
</div></div>

<script>
const select = document.querySelector('#measure');
  select.value = '{{$item->measure}}';
</script>
@endsection