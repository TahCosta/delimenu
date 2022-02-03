<?php

namespace App\Http\Controllers\Painel;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Input;
use App\Models\Product;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $column = $where ='';
        if(!is_null($user->company_id)){
            $column = 'company_id';
            $where = $user->company_id;
        }else{
            $column = 'user_id';
            $where = $loggedId;
        }
        $products = Product::where($column, '=', $where)->count();
        $inputs = Input::where($column, '=', $where)->where('type','=',1)->count();
        $recipes = Input::where($column, '=', $where)->where('type','=',0)->count();
        $providers = Provider::where($column, '=', $where)->count();
       
        return view('painel.home',[
            'products' => $products,
            'inputs' => $inputs,
            'recipes' => $recipes,
            'providers' => $providers
        ]);
    }

    public function email(Request $request){
        
    }
}
