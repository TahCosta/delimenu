<?php

namespace App\Http\Controllers\Painel;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
            $catInputs = Category::where('type', '=', 'input')
            ->where('user_id', '=',$loggedId)
            ->get();
            $catProducts = Category::where('type', '=', 'product')
            ->where('user_id', '=',$loggedId)
            ->get();
        }else{
            $catInputs = Category::where('type', '=', 'input')
            ->where('company_id', '=',$user->company_id)
            ->get();
            $catProducts = Category::where('type', '=', 'product')
            ->where('company_id', '=',$user->company_id)
            ->get();
        }
        
        return view('painel.categories.index', [
            'catInputs' => $catInputs,
            'catProducts' => $catProducts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->only([
            'type'
        ]);

        return view('painel.categories.create', [
            'type' => $data['type'],
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
            'type'
        ]);

        
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $column = $where = '';
        if ($data['type'] == 'inputPage') {
            $type = 'input';
        } else {
            $type = $data['type'];
        }
        if(!is_null($user->company_id)){
            $column = 'company_id';
            $where = $user->company_id;
        }else{
            $column = 'user_id';
            $where = $loggedId;
        }
        $validator = Validator::make(
            [
                'name' => $data['item'],
                'type' => $data['type']
            ],
            [
                'name' => ['required', 'string', 'max:100',Rule::unique('categories')->where(function ($query) use ($column,$where,$type) {
                    return $query->where($column, $where)->where('type',$type);
                })],
                'type' => ['required', 'string', 'max:20']
            ]
        );

        
        
        
        
        
        
       // return var_dump(count($validator->errors()));
        if ($validator->fails()|| count($validator->errors()) > 0) {
            if ($data['type'] !== 'inputPage') {
                return redirect()->route('category.create', ['type' => $data['type']])
                    ->withErrors($validator)
                    ->withInput();
            } else {
                return redirect()->route('inputs.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $category = new Category;
        $category->user_id = $loggedId;
        $category->company_id = $user->company_id;
        $category->name = $data['item'];
        $category->type = $type;
        $category->timestamps;
        $category->save();

        if ($data['type'] == 'inputPage') {
            return redirect()->route('inputs.create')
                ->with('warning', 'Categoria criada com sucesso!');
        } else {
            return redirect()->route('category.create', ['type' => $data['type']])
                ->with('warning', 'Categoria criada com sucesso!');
        }
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if ($category) {
            return view('painel.categories.edit', [
                'category' => $category
            ]);
        }
        return redirect()->route('category.index');
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
        ]);

        $validator = Validator::make(
            [
                'name' => $data['item'],
            ],
            [
                'name' => ['required', 'string', 'max:100'],
            ]
        );

        if ($validator->fails()) {
            return redirect()->route('category.edit', ['category' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $category = Category::find($id);
        $category->company_id = $user->company_id;
        $category->name = $data['item'];
        $category->save();

        return redirect()->route('category.edit', ['category' => $id])
            ->with('warning', 'Categoria editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->route('category.index');
    }
}
