<?php

namespace App\Http\Controllers\Painel;

use App\Models\User;
use App\Models\Input;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
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
        $column = $where ='';
        if(is_null($user->company_id)){
            $column = 'inputs.user_id';
            $where = $loggedId;
        } else{
            $column = 'inputs.company_id';
            $where = $user->company_id; 
        }

            $recipes = DB::table('inputs')
            ->leftJoin('categories', 'inputs.category_id', '=', 'categories.id')
            ->select('inputs.item','inputs.id','inputs.measure','inputs.packsize', 'categories.name as category','inputs.pack_cost')
            ->where('inputs.type','=','0')
            ->where($column,'=',$where)
            ->orderBy('inputs.id')->get();

        return view('painel.recipes.index', [
            'recipes' => $recipes
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
            $categories = Category::where('type', '=', 'input')
            ->where('user_id','=',$loggedId)->get();
            $inputs = Input::where('user_id','=',$loggedId)->get();
        }else{
            $categories = Category::where('type', '=', 'input')
            ->where('company_id','=',$user->company_id)->get();
            $inputs = Input::where('company_id','=',$user->company_id)->get();
        }
        $measures = [
            'Gramas','Quilos','Unidades','Litros','Mililitros'
        ];
        
        return view('painel.recipes.create', [
            'categories' => $categories,
            'inputs' => $inputs,
            'measures' => $measures
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

        //$request->request->remove('ammount');
        //$request->request->remove('input');
        $request->validate([
           
            'item' => ['required', 'string', 'max:100',Rule::unique('inputs')->where(function ($query) use ($column,$where) {
                return $query->where($column, $where)->where('type','=',0);
            })],
            'measure' => ['required', 'string'],
            'unity_cost' => ['nullable', 'string'],
            'pack_cost' => ['nullable', 'string'],
            'category' => ['nullable','integer'],
            'yield' => ['required','integer'],
            'ammount' =>['array'],
            'input' =>['nullable','array'],
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
                $error['duplicated'] = 'O campo item não pode ter ingredientes duplicados'; 
            }
        }
        $null = array_filter($data['ammount'],fn($value) => is_null($value) && $value == '');
        if(count($null) > 0){
            $error['ammount'] = 'O campo quantidade não pode ser vazio'; 
        }
        
        
        if(isset($error)){
            
            return redirect()->route('recipes.create')
                ->withErrors($error)
                ->withInput();
        }

  
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        //Colocar na tabela de inputs o total
        $input = new Input;
        $input->item = $request->item;
        $input->measure =  $request->measure;
        $input->packsize =  $request->yield;
        $input->pack_cost =  (float) substr($request->pack_cost,3);
        $input->unity_cost =  (float) substr($request->unity_cost,3);
        $input->category_id = $request->category;
        $input->preparation = $request->preparation;
        $input->provider_id = 0;
        $input->type = 0; 
        $input->user_id = $loggedId;
        $input->company_id = $user->company_id;
        $input->timestamps;
        $input->save();  
        //Colocar na tabela de receitas os itens
       
        for($i=0;$i<count($data['ammount']);$i++){
            $recipe = new Recipe;
            $recipe->id_input = $input->id;
            $recipe->id_item = $data['input'][$i];
            $recipe->ammount = $data['ammount'][$i];
            $recipe->type = 'recipe'; 
            $recipe->save();
        } 
        return redirect()->route('recipes.create')
            ->with('warning', 'Informações salvas com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $input = Input::find(intval($request->id));
        return $input;
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
        $column = $where = '';
        $inputs = Input::find($id);
        if(!is_null($user->company_id)){
            $column = 'company_id';
            $where = $user->company_id;
            if($inputs->company_id !== $where || $inputs->type !== 0){
                return redirect()->route('recipes.index');  
            }
        }else{
            $column = 'user_id';
            $where = $loggedId;
            if($inputs->user_id !== $where || $inputs->type !== 0){
                return redirect()->route('recipes.index');  
            }
        }
        
            $recipes = DB::table('inputs')
            ->leftJoin('recipes', 'inputs.id', '=', 'recipes.id_item')
            ->select('item','recipes.ammount as ammount','id_item','measure','unity_cost')
            ->whereNotNull('recipes.id_item')
            ->where('recipes.id_input','=',$id)
            ->where('inputs.type', '=',1)
            ->where('inputs.'.$column,'=',$where)->get();

            $item = Input::where('id','=',intval($id))
            ->where('inputs.'.$column,'=',$where)->first();

            $categories = Category::where('type', '=', 'input')
            ->where($column,'=',$where)->get();
            
      
        $measures = [
            'Gramas','Quilos','Unidades','Litros','Mililitros'
        ];
        
         return view('painel.recipes.edit', [
            'recipes' => $recipes,
            'item' => $item,
            'categories' => $categories,
            'measures' => $measures
            
        ]); 
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
           
            'item' => ['required', 'string', 'max:100'],
            'measure' => ['required', 'string'],
            'unity_cost' => ['required', 'string'],
            'pack_cost' => ['required', 'string'],
            'category' => ['integer'],
            'yield' => ['required','numeric'],
            'preparation' => ['nullable','string']
        ]);



        $input = Input::find($id);
        $input->item = $request->item;
        $input->measure = $request->measure;
        $input->pack_cost =  (float) substr($request->pack_cost,3);
        $input->unity_cost =  (float) substr($request->unity_cost,3);
        $input->category_id = $request->category;
        $input->packsize = $request->yield;
        $input->preparation = $request->preparation;
        $input->save();

        return redirect()->route('recipes.edit',['recipe' => $id])
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
            $loggedId = intval(Auth::id());
            $user = User::find($loggedId);
            $recipe = Recipe::find($id);
            $input = Input::where('id','=',$recipe->id_input);
            if(is_null($user->company_id)){
                if($input->user_id !== $loggedId){
                    return redirect()->route('recipes.index');
                }
            }else{
                if($input->company_id !== $user->company_id){
                    return redirect()->route('recipes.index');
                }
            }
            $input->delete();
            $recipe->delete();
        }
        return redirect()->route('recipes.index');
    }
}
