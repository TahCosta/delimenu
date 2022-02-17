@extends('adminlte::page')

@section('title', 'Configurações')

@section('content_header')
    <h1>Integração Ifood</h1>
@endsection
@section('content')
<div class="card responsive">
<ul class="nav nav-tabs mb-3 " id="myTab" role="tablist">
  <li class="flex-sm-fill text-sm-center nav-item" role="presentation">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Formas Pagamento</a>
  </li>
  <li class="flex-sm-fill text-sm-center nav-item" role="presentation">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Integração Ifood</a>
  </li>
  <li class="flex-sm-fill text-sm-center nav-item" role="presentation">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Empresa</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Placeat vero quasi nam in hic facilis recusandae. Aut, aliquid optio aspernatur eius perferendis totam odio ut perspiciatis ea consequuntur delectus tempore quidem sapiente ducimus expedita fugit nisi labore, similique libero amet quasi. Nihil ad vitae explicabo, ipsa dolores illum quia ab.</div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Aspernatur eaque at nostrum odit ducimus suscipit laborum possimus beatae, nisi accusamus. Illum reiciendis odio praesentium magni! Ullam fugit hic rem ipsa! Temporibus sit odit, distinctio beatae delectus quis aperiam sint molestiae necessitatibus quidem et incidunt doloremque quos, aut soluta perspiciatis labore laudantium? Fugiat, dicta odit voluptatum porro, voluptates neque possimus esse nemo ratione atque reiciendis. Maiores delectus soluta molestiae facilis architecto aspernatur eum praesentium sint optio suscipit doloremque expedita minima natus laudantium earum veritatis, obcaecati repudiandae odio debitis minus id dolorum commodi excepturi a! Repudiandae autem necessitatibus natus, doloremque doloribus repellendus?</div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde recusandae perferendis dolor animi omnis sint, ut possimus deserunt nihil optio minima! Tempore, ad repellat. Amet libero, eligendi, totam aperiam atque quam culpa, dolores nobis dolorum illum dolore eum aliquid esse doloremque? Iusto porro veniam dolores. Natus inventore eos voluptatem mollitia doloremque. Deleniti animi temporibus ea dignissimos nobis. Similique eius veritatis eos nihil reiciendis porro sequi magnam voluptates ipsam possimus ducimus laborum neque, nulla quidem distinctio atque facere totam aliquam vero!</div>
</div>
</div>
@endsection