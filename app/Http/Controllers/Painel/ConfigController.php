<?php

namespace App\Http\Controllers\Painel;

use App\Models\User;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Integration\IfoodController as Ifood;

class ConfigController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $company = true;
        $integration = false;
        if(is_null($user->company_id)){
            $company = false;
        }
        $hasIntegration = Delivery::where('company_id','=',$user->company_id)
        ->where('type','=','ifood')->first();
        if($hasIntegration && !is_null($hasIntegration->authorization)){
            $date = new \DateTime($hasIntegration->expiration);
            $now = new \DateTime('now');
            if(is_null($hasIntegration->access_token)){ // ainda falta mandar o authorization
                //se encontrou no banco e tá no prazo do userCode, manda o userCode do banco pra tela, se não, gera novo userCode
                if($now->getTimestamp() > $date->getTimestamp()){
                    $ifood =  Ifood::userCode();  
                }else{
                    $ifood = $hasIntegration;
                }  
            }else{ //tem access_token
                if($now->getTimestamp() > $date->getTimestamp()){
                    Ifood::getToken($hasIntegration->refresh_token);  
                }
                //chama merchant pra conferir que está tudo ok
                $ifood = Ifood::listMerchants($hasIntegration->access_token);
                if(is_array($ifood)){ //está autorizado e tudo ok
                    $integration = true; 
                }else{ //tenta gerar o refresh token, se ainda assim der erro, gera novo código
                    $ifood =  Ifood::userCode();     
                }
            }
        }else{ 
            $ifood =  Ifood::userCode();
        }
        return  view('painel.config.ifood',[
            'ifood'        => $ifood,
            'company'      =>$company,
            'integration'  => $integration
        ]);
    }

   public function ifoodsave(Request $request){
    $loggedId = intval(Auth::id());
    $user = User::find($loggedId);
    $integration = Delivery::where('company_id','=',$user->company_id)->first();
       $ifoodTokens = Ifood::getToken($integration->authorization,'authorization_code',$request->code);
        return $ifoodTokens;
   }
}
