<?php

namespace App\Http\Controllers\Painel;

use App\Models\User;
use App\Models\Input;
use App\Models\Recipe;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        if(is_null($user->company_id)){
            $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.cost','products.pdv','products.product','products.id','products.type','products.sell', 'categories.name as category')
            ->where('products.user_id','=',$loggedId)
            ->get();
        }else{
            $products = Product::where('company_id','=',$user->company_id)
            ->get();
        }
        
        return view('painel.products.index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        
        if(is_null($user->company_id)){
            $categories = Category::where('type', '=', 'product')
            ->where('user_id','=',$loggedId)->get();
            $inputs = Input::where('user_id','=',$loggedId)->get();
        }else{
            $categories = Category::where('type', '=', 'product')
            ->where('company_id','=',$user->company_id)->get();
            $inputs = Input::where('company_id','=',$user->company_id)->get();
        }
        
        return view('painel.products.create', [
            'categories' => $categories,
            'inputs' => $inputs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['ammount','input']);
        array_shift($data['ammount']);
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $column = $where = '';
        if(!is_null($user->company_id)){
            $column = 'company_id';
            $where = $user->company_id;
        }else{
            $column = 'user_id';
            $where = $loggedId;
        }

        $request->validate([
           
            'product' =>        ['required', 'string', 'max:100',Rule::unique('products')->where(function ($query) use ($column,$where) {
                return $query->where($column, $where);
            })],
            'category' =>       ['required','integer'],
            'type' =>           ['required','string'],
            'sell' =>           ['required', 'string', 'regex:/^(?:[1-9]\d+|\d)(?:\,\d+|\d)?$|^(?:[1-9]\d+|\d)(?:\.\d+|\d)?$/m'],
            'cost' =>           ['required','string'],
            'ammount' =>        ['array'],
            'input' =>          ['nullable','array'],
        ]);
        $null = [];
        if(!isset($data['input'])){
            $error['input'] = 'A lista de ingredientes é obrigatória'; 
        }else{
            $null = array_filter($data['input'],fn($value) => is_null($value) && $value == '');
            if(count($null) >0){
                $error['input'] = 'O campo item não pode ser vazio'; 
            }
            $unique = array_unique($data['input']);
            if(count($unique) !== count($data['input'])){
                $error['duplicated'] = 'O campo item não pode ter duplicados'; 
            }

        }

        $null = array_filter($data['ammount'],fn($value) => is_null($value) && $value == '');
        if(count($null) > 0){
            $error['ammount'] = 'O campo quantidade não pode ser vazio'; 
        }
        
        if(isset($error)){
            
            return redirect()->route('products.create')
                ->withErrors($error)
                ->withInput();
        }

        $request->sell = preg_replace('/,/', '.', $request->sell);
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        //Colocar na tabela de insumos o total
        $product = new Product;
        $product->product = $request->product;
        $product->pdv = $request->pdv;
        $product->category_id = $request->category;
        $product->type = $request->type; 
        $product->sell = (float) $request->sell;
        $product->cost = (float) substr($request->cost,3);
        $product->preparation = $request->preparation;
        $product->user_id = $loggedId;
        $product->company_id = $user->company_id;
        $product->timestamps;
        $product->save();  
        //Colocar na tabela de receitas os itens
       
        for($i=0;$i<count($data['ammount']);$i++){
            $recipe = new Recipe;
            $recipe->id_input = $product->id;
            $recipe->id_item = $data['input'][$i];
            $recipe->ammount = $data['ammount'][$i];
            $recipe->type = $request->type; //1 - produto, 2 - topper, 0 - receita
            $recipe->save();
        } 
        return redirect()->route('products.create')
            ->with('warning', 'Informações salvas com sucesso!');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $product = Product::where('id','=',intval($id))
        ->where('products.user_id','=',$loggedId)->first();
        if(is_null($user->company_id)){
            $inputs = DB::table('inputs')
            ->leftJoin('recipes', 'inputs.id', '=', 'recipes.id_item')
            ->select('item','recipes.ammount as ammount','id_item','measure','unity_cost')
            ->where('recipes.id_input', '=', intval($id))
            ->where('inputs.user_id','=',$loggedId)->get();
            $categories = Category::where('type', '=', 'product')
            ->where('user_id','=',$loggedId)->get();
           
        }else{
            $inputs = DB::table('inputs')
            ->leftJoin('recipes', 'inputs.id', '=', 'recipes.id_item')
            ->select('item','recipes.ammount as ammount','id_item','measure','unity_cost')
            ->where('recipes.id_input', '=',intval($id))
            ->where('company_id','=',$user->company_id)->get();
            $categories = Category::where('type', '=', 'product')
            ->where('company_id','=',$user->company_id)                
            ->get();
            
        }
        if ($product) {
            return view('painel.products.edit', [
                'product'  => $product,
                'categories' => $categories,
                'inputs' => $inputs,

            ]); 
        }
        return redirect()->route('products.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->request->remove('ammount');
        $request->request->remove('input');
        
        $request->validate([
           
            'product' => ['required', 'string', 'max:100'],
            'pdv' => ['integer'],
            'type' => ['required', 'string'],
            'cost' => ['required', 'string'],
            'category' => ['integer'],
            'sell' => ['required', 'string', 'regex:/^(?:[1-9]\d+|\d)(?:\,\d+|\d)?$|^(?:[1-9]\d+|\d)(?:\.\d+|\d)?$/m'],
            
        ]);
        $request->sell = preg_replace('/,/', '.', $request->sell);
        $product = Product::find($id);
        $product->product = $request->product;
        $product->pdv = $request->pdv;
        $product->type =  $request->type;
        $product->sell = $request->sell;
        $product->cost =  (float) substr($request->cost,3);
        $product->category_id = $request->category;
        $product->preparation = $request->preparation;
        $product->save();

        return redirect()->route('products.edit',['product' => $id])
            ->with('warning', 'Informações salvas com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!empty($id)){
            $product = Product::find($id);
            $recipe = Recipe::where('id_input', '=',$id);
            $recipe->delete();
            $product->delete();
        }
        return redirect()->route('products.index');
    }

    public function show(Request $request, $id){
        $input = Input::find(intval($request->id));
        return $input;
    }
}
