@extends('adminlte::page')

@section('title', 'Painel Delivery')

@section('content_header')
    <h1>Painel Delivery</h1>
@endsection
@section('content')
<h3>Lojas</h3>
<button type="button" class="btn btn-secondary" id="{{$merchant->id}}" data-container="body" data-toggle="popover" data-placement="bottom" >
  Ifood - {{$merchant->name}}
</button>
<br>
<h3>Pedidos</h3>
<div id="orders">
<div class="card card-success collapsed-card" id="1234">
 <div class="card-header">
     <h3 class="card-title"><i class="fas fa-lg fa-motorcycle mr-2"></i>Pedido Ifood #1234</h3>
     <div class="card-tools">
         <button type="button" class="btn btn-tool" data-card-widget="collapse">
         <i class="fas fa-lg fa-plus"></i></button>
     </div>
 </div>
 <div class="card-body">
     <h6>Cliente: Fulano de tal</h6>
     <h6>Rua dos bobos, num 0, santo andré, SP Cep 00000-000</h6>
    
     <h5>Itens Pedido</h5>
<table class="table table-sm   nowrap">
    <thead>
    <tr class="bg-secondary">
        <th>Item</th>
        <th>Qtd</th>
        <th>Valor Total</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><b>Bolo Bombom</b></td>
        <td><b>2</b></td>
        <td><b>9,88</b></td>
    </tr>
    <tr class="text-secondary">
        <td> Brigadeiro</td>
        <td>4</td>
        <td>12,00</td>
    </tr>
    <tr class="text-secondary">
        <td> trufa</td>
        <td>1</td>
        <td>3,99</td>
    </tr>
    </tbody>
</table>
<br>
<table class="table table-borderless table-sm " style="max-width: 400px;">
    <tr>
        <td>Subtotal:</td>
        <td class="text-right">R$ 30,00</td>
    </tr>
    <tr>
        <td>Taxa de entrega:</td>
        <td class="text-right"> R$ 4,90</td>
    </tr>
    <tr>
        <td><b>Total Pedido:</b></td>
        <td class="text-right"><b> R$ 34,90</b></td>
    </tr>
</table>
<h6> </h6>
<h6></h6>
<h5></h5>
<h6>Forma de pagamento: Dinheiro - troco para R$50,00</h6>

<br>
<h6>Observações: Tirar cebola</h6>
<h6>Outras informações: Pagamento online - não levar máquina</h6>
 </div>
</div>
</div>
@endsection
@section('js')
<script src="{{asset('js/delivery.js')}}"></script>
@stop