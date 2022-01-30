<?php

namespace App\Http\Controllers\Painel;

use App\Models\User;
use App\Models\Input;
use App\Models\Stock;
use App\Models\Category;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InputController extends Controller
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
        $inputs = DB::table('inputs')
            ->leftJoin('categories', 'inputs.category_id', '=', 'categories.id')
            ->leftJoin('providers', 'inputs.provider_id', '=', 'providers.id')
            ->select('inputs.*', 'categories.name as category', 'providers.provider as provider')
            ->where('inputs.type','=','1')
            ->where('inputs.user_id','=',$loggedId)->get();
        }else{
            $inputs = DB::table('inputs')
            ->leftJoin('categories', 'inputs.category_id', '=', 'categories.id')
            ->leftJoin('providers', 'inputs.provider_id', '=', 'providers.id')
            ->select('inputs.*', 'categories.name as category', 'providers.provider as provider')
            ->where('inputs.type','=','1')
            ->where('inputs.company_id','=',$user->company_id)->get();
        }
        


        return view('painel.inputs.index', [
            'inputs' => $inputs
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
            ->where('user_id','=',$loggedId)                
            ->get();
            $providers = Provider::where('user_id','=',$loggedId)->get();
        }else{
            $categories = Category::where('type', '=', 'input')
            ->where('company_id','=',$user->company_id)                
            ->get();
            $providers = Provider::where('company_id','=',$user->company_id)->get();
        }
       

        return view('painel.inputs.create', [
            'categories' => $categories,
            'providers' => $providers,
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
        $data = $request->only([
            'item',
            'measure',
            'packsize',
            'pack_cost',
            'category',
            'provider'
        ]);

        if (!isset($data['category'])) {
            $data['category'] = 0;
        }
        if (!isset($data['provider'])) {
            $data['provider'] = 0;
        }
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

        $validator = Validator::make([
            'item' => $data['item'],
            'measure' => $data['measure'],
            'packsize' => $data['packsize'],
            'pack_cost' => $data['pack_cost'],
            'category' => $data['category'],
            'provider' => $data['provider']
        ], [
            'item' => ['required', 'string', 'max:100', Rule::unique('inputs')->where(function ($query) use ($column,$where) {
                return $query->where($column, $where)->where('type', '=',1);
            })],
            'measure' => ['required', 'string'],
            'packsize' => ['required', 'string', 'regex:/^(?:[1-9]\d+|\d)(?:\,\d+|\d)?$|^(?:[1-9]\d+|\d)(?:\.\d+|\d)?$/m'],
            'pack_cost' => ['required', 'string', 'regex:/^(?:[1-9]\d+|\d)(?:\,\d+|\d)?$|^(?:[1-9]\d+|\d)(?:\.\d+|\d)?$/m'],
            'category' => ['integer'],
            'provider' => ['integer']
        ]);
        if ($validator->fails()) {
            return redirect()->route('inputs.create')
                ->withErrors($validator)
                ->withInput();
        }
        $data['pack_cost'] = preg_replace('/,/', '.', $data['pack_cost']);
        if(floatval($data['packsize']) > 0){
            $data['unity_cost'] = floatval($data['pack_cost'])/floatval($data['packsize']);
        }else{
            $validator->errors()->add('packsize', 'O tamanho do pacote deve ser maior do que zero.'); 
        }

        $input = new Input;
        $input->item = $data['item'];
        $input->measure = $data['measure'];
        $input->packsize = $data['packsize'];
        $input->pack_cost = $data['pack_cost'];
        $input->unity_cost = $data['unity_cost'];
        $input->category_id = $data['category'];
        $input->provider_id = $data['provider'];
        $input->user_id = $loggedId;
        $input->type = 1;
        $input->timestamps;
        $input->save();

        return redirect()->route('inputs.create')
            ->with('warning', 'Informações salvas com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $input = Input::find($id);
        if ($input) {

            $loggedId = intval(Auth::id());
            $user = User::find($loggedId);
            if(is_null($user->company_id)){
                $categories = Category::where('type', '=', 'input')
                ->where('user_id','=',$loggedId)                
                ->get();
                $providers = Provider::where('user_id','=',$loggedId)->get();
            }else{
                $categories = Category::where('type', '=', 'input')
                ->where('company_id','=',$user->company_id)                
                ->get();
                $providers = Provider::where('company_id','=',$user->company_id)->get();
            }


            return view('painel.inputs.edit', [
                'input'      => $input,
                'categories' => $categories,
                'providers'  => $providers,
            ]);
        }
        return redirect()->route('inputs.index');
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
        $data = $request->only([
            'item',
            'measure',
            'packsize',
            'pack_cost',
            'category',
            'provider'
        ]);

        if (!isset($data['category'])) {
            $data['category'] = 0;
        }
        if (!isset($data['provider'])) {
            $data['provider'] = 0;
        }
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

        $validator = Validator::make([
            'item' => $data['item'],
            'measure' => $data['measure'],
            'packsize' => $data['packsize'],
            'pack_cost' => $data['pack_cost'],
            'category' => $data['category'],
            'provider' => $data['provider']
        ], [
            'item' => ['required', 'string', 'max:100'],
            'measure' => ['required', 'string'],
            'packsize' => ['required', 'string', 'regex:/^(?:[1-9]\d+|\d)(?:\,\d+|\d)?$|^(?:[1-9]\d+|\d)(?:\.\d+|\d)?$/m'],
            'pack_cost' => ['required', 'string', 'regex:/^(?:[1-9]\d+|\d)(?:\,\d+|\d)?$|^(?:[1-9]\d+|\d)(?:\.\d+|\d)?$/m'],
            'category' => ['integer'],
            'provider' => ['integer']
        ]);
        $data['pack_cost'] = preg_replace('/,/', '.', $data['pack_cost']);
        if(floatval($data['packsize']) > 0){
            $data['unity_cost'] = floatval($data['pack_cost'])/floatval($data['packsize']);
        }else{
            $validator->errors()->add('packsize', 'O tamanho do pacote deve ser maior do que zero.'); 
        }
        
        if ($validator->fails()) {
            return redirect()->route('inputs.edit', [
                'input' => $id
            ])
                ->withErrors($validator)
                ->withInput();
        }

        $input = Input::find($id);
        if ($data['item'] !== $input->item) {
            $hasItem = Input::where('item', '=', $data['item'])
            ->where($column,$where)->get();
            if (count($hasItem) === 0) {
                $input->item = $data['item'];
            } else {
                $validator->errors()->add('item', __('validation.unique', [
                    'attribute' => 'item'
                ]));
            }
        }
        if (count($validator->errors()) > 0) {
            return redirect()->route('inputs.edit', [
                'input' => $id
            ])->withErrors($validator);
        }

        $input->measure = $data['measure'];
        $input->packsize = $data['packsize'];
        $input->pack_cost = $data['pack_cost'];
        $input->unity_cost = $data['unity_cost'];
        $input->category_id = $data['category'];
        $input->provider_id = $data['provider'];
        $input->timestamps;
        $input->save();

        return redirect()->route('inputs.edit', [
            'input' => $id
        ])->with('warning', 'Informações salvas com sucesso!');
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
            $input = Input::find($id);
            $input->delete();
            Stock::where('item_id', '=', $id)->delete();

        }

        return redirect()->route('inputs.index');
    }
}
