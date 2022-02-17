<?php

namespace App\Http\Controllers\Integration;

use App\Models\User;
use App\Models\Order;
use App\Models\Delivery;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\IfoodEvent;
use App\Models\Orderitem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class IfoodController extends Controller
{
    private const AUTH = 'https://merchant-api.ifood.com.br/authentication/v1.0/';
    private const CANCEL_LIST = [
		[
			'code' => '501',
			'reason' => 'PROBLEMAS DE SISTEMA'
		],
		[
			'code' => '502',
			'reason' => 'PEDIDO EM DUPLICIDADE'
		],
		[
			'code' => '503',
			'reason' => 'ITEM INDISPONÍVEL'
		],
		[
			'code' => '504',
			'reason' => 'RESTAURANTE SEM MOTOBOY'
		],
		[
			'code' => '505',
			'reason' => 'CARDÁPIO DESATUALIZADO'
		],
		[
			'code' => '506',
			'reason' => 'PEDIDO FORA DA ÁREA DE ENTREGA'
		],
		[
			'code' => '507',
			'reason' => 'CLIENTE GOLPISTA / TROTE'
		],
		[
			'code' => '508',
			'reason' => 'FORA DO HORÁRIO DO DELIVERY'
		],
		[
			'code' => '509',
			'reason' => 'DIFICULDADES INTERNAS DO RESTAURANTE'
		],
		[
			'code' => '511',
			'reason' => 'ÁREA DE RISCO'
		],
		[
			'code' => '512',
			'reason' => 'RESTAURANTE ABRIRÁ MAIS TARDE'
		],
		[
			'code' => '513',
			'reason' => 'RESTAURANTE FECHOU MAIS CEDO'
		],
	];
   
    private static function updateDelivery($ifood, $type){
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $exists = Delivery::where('company_id','=',$user->company_id)
        ->where('type','=','ifood')->first();
        $date = new \DateTime('+'.($ifood->expiresIn - 60).' seconds');
        if(!$exists){
            $exists = new Delivery();
            $exists->type = 'ifood';
            $exists->company_id = $user->company_id;
            $exists->user_id = $loggedId;
        }
        if($type == 'userCode'){
            $exists->userCode = $ifood->userCode;
            $exists->authorization = $ifood->authorizationCodeVerifier;
        }
        if($type == 'access'){
            $exists->access_token = $ifood->accessToken;
            $exists->refresh_token = $ifood->refreshToken;
        }
        $exists->expiration = $date->format('Y-m-d H:i:s');
        $exists->save();
    }

	public static function getCancelList(){
		return json_encode(IfoodController::CANCEL_LIST);
	}

    public static function userCode(){
        $clientId = config('delivery.ifoodClientId');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => IfoodController::AUTH.'oauth/userCode',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'clientId='.$clientId,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        curl_close($curl);
        if(!isset($response->error)){
            IfoodController::updateDelivery($response,'userCode');
        }
        return $response;
    }

    public static function getToken($token,$type = 'refresh_token', $authorizationCode = null){
        $clientId = config('delivery.ifoodClientId');
        $clientSecret = config('delivery.ifoodClientSecret');
        $curl = curl_init();
        if($type == 'authorization_code'){
            $code = "authorizationCode={$authorizationCode}&authorizationCodeVerifier={$token}";
        }else{
            $code = "refreshToken={$token}";
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => IfoodController::AUTH.'oauth/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "grantType={$type}&clientId={$clientId}&clientSecret={$clientSecret}&{$code}",
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/x-www-form-urlencoded'
            ),
          ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        curl_close($curl);
        if(!isset($response->error)){
            IfoodController::updateDelivery($response,'access');
        }
        return $response;
    }

    public static function listMerchants($access_token){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://merchant-api.ifood.com.br/merchant/v1.0/merchants',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('Authorization: Bearer '.$access_token),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        curl_close($curl);
        return $response;

    }

    public static function getMerchantInfo(){
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $delivery = Delivery::where('company_id','=',$user->company_id)->first();
		$date = new \DateTime($delivery->expiration);
        $now = new \DateTime('now');
        if($now->getTimestamp() > $date->getTimestamp()){ //token expirado
           $newTokens = IfoodController::getToken($delivery->refresh_token);
           $access_token = $newTokens->accessToken;
        }else{
            $access_token = $delivery->access_token;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://merchant-api.ifood.com.br/merchant/v1.0/merchants/'.$delivery->merchant_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('Authorization: Bearer '.$access_token),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        curl_close($curl);

        return $response;
    }

    public static function getMerchantStatus(){
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $delivery = Delivery::where('company_id','=',$user->company_id)->first();
		$date = new \DateTime($delivery->expiration);
        $now = new \DateTime('now');
        if($now->getTimestamp() > $date->getTimestamp()){ //token expirado
           $newTokens = IfoodController::getToken($delivery->refresh_token);
           $access_token = $newTokens->accessToken;
        }else{
            $access_token = $delivery->access_token;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://merchant-api.ifood.com.br/merchant/v1.0/merchants/'.$delivery->merchant_id.'/status',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('Authorization: Bearer '.$access_token),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        curl_close($curl);
		$response = [
            'id' => $delivery->merchant_id,
            'data' =>$response,
        ];
        return json_encode($response);
    }

    public static function pooling(){
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $delivery = Delivery::where('company_id','=',$user->company_id)
        ->where('type','=','ifood')->first();
        $date = new \DateTime($delivery->expiration);
        $now = new \DateTime('now');
        if($now->getTimestamp() > $date->getTimestamp()){ //token expirado
           $newTokens = IfoodController::getToken($delivery->refresh_token);
           $access_token = $newTokens->accessToken;
        }else{
            $access_token = $delivery->access_token;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://merchant-api.ifood.com.br/order/v1.0/events:polling',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('Authorization: Bearer '.$access_token),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        $info = curl_getinfo($curl);
        curl_close($curl);
		//if($info['http_code'] == 204) return $response;
		//if($info['http_code'] == 200) IfoodController::handleEvent($response);

       
		if($info['http_code'] == 204) return IfoodController::handleEvent($response);
		return $info['http_code'];
    }

	private static function handleEvent($events){
		$events = json_decode('[{"id":"70f8dbff-9d77-4527-a530-feb620c827e1","orderId":"2f6a923b-1a89-49f4-862e-074379a58070","fullCode":"CANCELLED"}]');
		$eventArray = $return = [];
		$loggedId = intval(Auth::id());
        $user = User::find($loggedId);
		//return var_dump($events);
		foreach($events as $event){
			//ve se o evento já foi tratado
			$checkEvent = IfoodEvent::where('event_id','=',$event->id)->first();
			$order = Order::where('order_id','=',$event->orderId)->first();
			if($checkEvent){
				array_push($eventArray, ['id' =>$event->id]);
				
			}else{
			//se não foi, insere o evento no banco e vê se o pedido existe
				$checkEvent = new IfoodEvent();
				$checkEvent->event_id = $event->id;
				$checkEvent->order_id = $event->orderId;
				$checkEvent->full_code = $event->fullCode;
				$checkEvent->metadata = isset($event->metaData)? $event->metaData: null;
				$checkEvent->event_creation = date("Y-m-d H:i:s", strtotime($event->createdAt));
				$checkEvent->user_id = $loggedId;
				$checkEvent->company_id = $user->company_id;
				$checkEvent->save();
			}
			//se não tiver pedido, segue o fluxo do placed
			if(!$order){
				$ifoodOrder = IfoodController::orderDetail($event->orderId,$event->fullCode);
				IfoodController::createOrder($ifoodOrder,$event->fullCode);
			}
			$order = Order::where('order_id','=',$event->orderId)->first();
			$order->status = $event->fullCode;
			$order->save();
			$items = Orderitem::where('order_id','=',$order->id)->get();
			$customer = Customer::find($order->customer_id);
			array_push($return,['event' =>$event,'order' => $order, 'items' =>$items,'customer'=>$customer]);	
			//se tiver, altera o status no banco
			//retorna orderData, event 
		}
		IfoodController::acknowledgeEvents($eventArray);
		return $return;
	}

	private static function acknowledgeEvents(Array $eventArray){
		$loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $delivery = Delivery::where('company_id','=',$user->company_id)->first();
		$date = new \DateTime($delivery->expiration);
        $now = new \DateTime('now');
        if($now->getTimestamp() > $date->getTimestamp()){ //token expirado
           $newTokens = IfoodController::getToken($delivery->refresh_token);
           $access_token = $newTokens->accessToken;
        }else{
            $access_token = $delivery->access_token;
        }
        $curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://merchant-api.ifood.com.br/order/v1.0/events/acknowledgment',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($eventArray),
		CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$access_token,
			'Content-Type: application/json'
		),
		));
		$response = curl_exec($curl);
        $response = json_decode($response);
        curl_close($curl);
        return json_encode($response);
	}

	private static function createOrder($order,$status){
		$loggedId = intval(Auth::id());
        $user = User::find($loggedId);
		$exists = Order::where('order_id','=',$order->id)
		->where('company_id','=',$user->company_id)->first();
		if(!$exists){
			$customerId = IfoodController::createCustomer($order->customer,$order->delivery);
			$delivery = Delivery::where('merchant_id','=',$order->merchant->id)->where('company_id','=',$user->company_id)->first();
			$newOrder = new Order();
			$newOrder->order_id = $order->id;
			$newOrder->display_id = $order->displayId;
			$newOrder->type = $order->orderType;
			$newOrder->status = $status;
			$newOrder->delivery_id = $delivery->id;
			$newOrder->customer_id = $customerId;
			//$newOrder->paymethod_id = $order->id;
			$discountIfood = $discountStore = 0;
			if(is_array($order->total->benefits)){
				foreach($order->total->benefits as $benefit){
					foreach($benefit->sponsorshipValues as $value){
						if($value->name == 'IFOOD') $discountIfood += $value->value;
						if($value->name == 'MERCHANT') $discountStore += $value->value;
					}
				}
			}
			$newOrder->discount_store = $discountStore;
			$newOrder->discount_delivery = $discountIfood;
			$newOrder->delivery_fee = $order->total->deliveryFee;
			$newOrder->aditional_fees = isset($order->total->additionalFees) ? $order->total->additionalFees : 0;
			$newOrder->total = $order->total->orderAmount;
			$newOrder->preparation_start = date("Y-m-d H:i:s", strtotime($order->preparationStartDateTime));
			$newOrder->delivery_time = date("Y-m-d H:i:s", strtotime($order->delivery->deliveryDateTime));
			$newOrder->delivery_type = $order->orderTiming;
			$newOrder->delivered_by = $order->delivery->deliveredBy;
			$newOrder->observations = isset($order->items->observations) ? $order->items->observations : '';
			$newOrder->extra_info = isset($order->extraInfo) ? $order->extraInfo : '';
			$newOrder->user_id = $loggedId;
			$newOrder->company_id = $user->company_id;
			$newOrder->save();
			foreach($order->items as $item){
				$newItem = IfoodController::createItems($item,$newOrder->id,$user);
				if(isset($item->options)){
					foreach($item->options as $option){
						IfoodController::createItems($option,$newOrder->id,$user,$newItem);
					}
				}
			}
		}
	}

	private static function createItems($item,$orderId,$user, $itemId = null){
		$newItem = new Orderitem();
		if(isset($item->externalCode)){
			$product = Product::where('pdv','=',$item->externalCode)
			->where('company_id','=',$user->company_id)->first();
			if($product){
				$newItem->product_id = $product->id;
			}
		}
		$newItem->name = $item->name;
		$newItem->order_id = $orderId;
		$newItem->item_id = $item->id;
		$newItem->is_complement = !is_null($itemId);
		$newItem->complement_for = $itemId;
		$newItem->ammount = $item->quantity;
		$newItem->price = $item->price;
		$newItem->observations = isset($item->observations)? $item->observations : '';
		$newItem->user_id = $user->id;
		$newItem->company_id = $user->company_id;
		$newItem->save();
		return $newItem->id;
	}

	private static function createCustomer($customer,$delivery){
		$loggedId = intval(Auth::id());
        $user = User::find($loggedId);
		$exists = Customer::where('company_id','=',$user->company_id)
		->where('delivery_id','=',$customer->id)->first();
		if(!$exists){
			$newCustomer = new Customer();
			$newCustomer->delivery_id = $customer->id;
			$newCustomer->name = $customer->name;
			$newCustomer->address = $delivery->deliveryAddress->streetName;
			$newCustomer->address_num = $delivery->deliveryAddress->streetNumber;
			$newCustomer->postal_code = $delivery->deliveryAddress->postalCode;
			$newCustomer->state = $delivery->deliveryAddress->state;
			$newCustomer->city = $delivery->deliveryAddress->city;
			$newCustomer->neighborhood = $delivery->deliveryAddress->neighborhood;
			$newCustomer->document = isset($customer->documentNumber)? $customer->documentNumber: '';
			$newCustomer->phone = isset($customer->phone->number)? $customer->phone->number:'';
			$newCustomer->localizer = isset($customer->phone->localizer)?$customer->phone->localizer:'';
			$newCustomer->order_count = 1;
			$newCustomer->user_id = $loggedId;
			$newCustomer->company_id = $user->company_id;
			$newCustomer->save();
			return $newCustomer->id;
		}
			$exists->address = $delivery->deliveryAddress->streetName;
			$exists->address_num = $delivery->deliveryAddress->streetNumber;
			$exists->postal_code = $delivery->deliveryAddress->postalCode;
			$exists->state = $delivery->deliveryAddress->state;
			$exists->city = $delivery->deliveryAddress->city;
			$exists->neighborhood = $delivery->deliveryAddress->neighborhood;
			$exists->phone = $customer->phone->number;
			$exists->localizer = $customer->phone->localizer;
			$exists->order_count = ($exists->order_count +1);
			$exists->save();
			return $exists->id;
	
	}

	public static function orderDetail($orderId){
		$loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $delivery = Delivery::where('company_id','=',$user->company_id)
        ->where('type','=','ifood')->first();
        $date = new \DateTime($delivery->expiration);
        $now = new \DateTime('now');
        if($now->getTimestamp() > $date->getTimestamp()){ //token expirado
           $newTokens = IfoodController::getToken($delivery->refresh_token);
           $access_token = $newTokens->accessToken;
        }else{
            $access_token = $delivery->access_token;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://merchant-api.ifood.com.br/order/v1.0/orders/'.$orderId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('Authorization: Bearer '.$access_token),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        curl_close($curl);
        return $response;
	}

}
