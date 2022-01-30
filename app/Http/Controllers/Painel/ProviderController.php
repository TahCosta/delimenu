<?php

namespace App\Http\Controllers\Painel;

use App\Models\User;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
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
            $providers = Provider::where('user_id', '=',$loggedId)
            ->get();
        }else{
            $providers = Provider::where('company_id', '=',$user->company_id)
            ->get();
           
        }
        return view('painel.providers.index', [
            'providers' => $providers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('painel.providers.create');
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
            'provider',
            'address',
            'email',
            'cnpj',
            'phone',
            'obs'
        ]);
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
            'provider' => $data['provider'],
            'address' => $data['address'],
            'email' => $data['email'],
            'cnpj' => $data['cnpj'],
            'phone' => $data['phone'],
            'obs ' => $data['obs'],
        ], [
            'provider' => ['required', 'string', 'max:100',Rule::unique('providers')->where(function ($query) use ($column,$where) {
                return $query->where($column, $where);
            })],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'cnpj' => ['nullable', 'string', 'min:18', 'max:18'],
            'phone' => ['nullable', 'string', 'min:14', 'max:15'],
            'obs' => ['nullable', 'string']
        ]);
        if ($validator->fails()) {
            return redirect()->route('providers.create')
                ->withErrors($validator)
                ->withInput();
        }

        
        $providers = new Provider;
        $providers->provider = $data['provider'];
        $providers->address = $data['address'];
        $providers->email = $data['email'];
        $providers->cnpj = $data['cnpj'];
        $providers->phone = $data['phone'];
        $providers->obs = $data['obs'];
        $providers->user_id = $loggedId;
        $providers->company_id = $user->company_id;
        $providers->save();

        return redirect()->route('providers.create')
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
        $providers = Provider::find($id);
        if ($providers) {
            return view('painel.providers.edit', [
                'providers'  => $providers
            ]);
        }
        return redirect()->route('providers.index');
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
            'provider',
            'address',
            'email',
            'cnpj',
            'phone',
            'obs'
        ]);
        
        $validator = Validator::make([
            'provider' => $data['provider'],
            'address' => $data['address'],
            'cnpj' => $data['cnpj'],
            'phone' => $data['phone'],
            'obs ' => $data['obs'],
        ], [
            'provider' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:100'],
            'cnpj' => ['nullable', 'string', 'min:18', 'max:18'],
            'phone' => ['nullable', 'string', 'min:14', 'max:15'],
            'obs' => ['nullable', 'string']
        ]);
        if ($validator->fails()) {
            return redirect()->route('providers.edit', [
                'provider' => $id
            ])
                ->withErrors($validator)
                ->withInput();
        }
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        $providers = Provider::find($id);
        if ($providers) {
            if ($providers->provider !== $data['provider']) {
                if(!is_null($user->company_id)){
                    $hasprovider  = Provider::where('provider', '=', $data['provider'])
                    ->where('company_id', '=', $user->company_id)
                    ->first();
                }else{
                    $hasprovider  = Provider::where('provider', '=', $data['provider'])
                    ->where('user_id', '=', $loggedId)
                    ->first();
                }
                if ($hasprovider) {
                    $validator->errors()->add('item', __('validation.unique', [
                        'attribute' => 'provider'
                    ]));
                } else {
                    $providers->provider = $data['provider'];
                }
            }

            if (count($validator->errors()) > 0) {
                return redirect()->route('providers.edit', [
                    'provider' => $id
                ])
                    ->withErrors($validator)
                    ->withInput();
            }
            $providers->company_id = $user->company_id;
            $providers->address = $data['address'];
            $providers->email = $data['email'];
            $providers->cnpj = $data['cnpj'];
            $providers->phone = $data['phone'];
            $providers->obs = $data['obs'];
            $providers->save();

            return redirect()->route('providers.edit', [
                'provider' => $id
            ])
                ->with('warning', 'Informações salvas com sucesso!');
        }

        return redirect()->route('providers.index');
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
            $provider = Provider::find($id);
            $provider->delete();

        }

        return redirect()->route('providers.index');
    }
}
