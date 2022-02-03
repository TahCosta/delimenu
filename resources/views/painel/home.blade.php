@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
{{header('Content-Type: application/json')}}
    <h1> Dashboard </h1>

@section('content')
<div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$products}}</h3>

                <p>Produtos</p>
              </div>
              <div class="icon">
              <i class="fas fa-shopping-bag"></i>
              </div>
              <a href="{{route('products.index')}}" class="small-box-footer">Ver produtos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$recipes}}</h3>

                <p>Receitas</p>
              </div>
              <div class="icon">
                <i class="fas fa-book-open"></i>
              </div>
              <a href="{{route('recipes.index')}}" class="small-box-footer">Ver receitas <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$inputs}}</h3>

                <p>Insumos</p>
              </div>
              <div class="icon">
              <i class="fas fa-cart-plus"></i>
              </div>
              <a href="{{route('inputs.index')}}" class="small-box-footer">Ver insumos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$providers}}</h3>

                <p>Fornecedores</p>
              </div>
              <div class="icon">
              <i class="fas fa-store-alt"></i>
              </div>
              <a href="{{route('providers.index')}}" class="small-box-footer">Ver fornecedores <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <h3>Gostariamos de saber a sua opinião sobre nosso site</h3>
        <p>Deixe sua opinião e sugestões de melhorias. Estamos trabalhando para te atender cada vez melhor.</p>
  <form action="{{route('email')}}" method="post">
  @csrf
    <div class="form-group row">
      <textarea class="form-control " id="preparation" name="message" placeholder="Mensagem"  cols="30" rows="10"></textarea>      
    </div>
    <input type="submit" class="btn btn-success mr-1" value="Enviar Mensagem">
  </form>
@endsection
@endsection