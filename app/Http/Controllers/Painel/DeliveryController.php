<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Integration\IfoodController as Ifood;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function painelDelivery(){  
        $merchant = Ifood::getMerchantInfo();
        return view('painel.delivery.painel',[
            'merchant' => $merchant
        ]);
    }

    //Ifood
    public function updateOrder(){
        $pooling = Ifood::pooling();
        return $pooling;
    }

    public function infoMerchant(){
        $merchants = Ifood::getMerchantStatus();
        return $merchants;
    }

    public function infoOrder(Request $request){

        //verifica se o pedido existe
           
  
        return json_encode(array('message' =>'tipo não processado'));
        
    }

}
