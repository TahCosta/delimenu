<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
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
        $customers = Customer::paginate(10);
        return view('painel.customers.index', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('painel.customers.create');
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
            'name',
            'email',
            'address',
            'phone',
            'whatsapp'
        ]);
        $validator = Validator::make([
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'whatsapp' => $data['whatsapp'],
        ], [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:customers'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('customers.create')
                ->withInput()
                ->withErrors($validator);
        }

        $customer = new Customer;
        $customer->name = $data['name'];
        $customer->email = $data['email'];
        $customer->address = $data['address'];
        $customer->phone = $data['phone'];
        $customer->whatsapp = $data['whatsapp'];
        $customer->save();

        return redirect()->route('customers.create')
            ->with('warning', 'Cliente cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            return view('painel.customers.edit', [
                'customer' => $customer
            ]);
        }
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
        $customer = Customer::find($id);
        if ($customer) {
            $data = $request->only([
                'name',
                'email',
                'address',
                'phone',
                'whatsapp'
            ]);
            $validator = Validator::make([
                'name' => $data['name'],
                'email' => $data['email'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'whatsapp' => $data['whatsapp'],
            ], [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['nullable', 'email', 'max:100'],
                'address' => ['nullable', 'string', 'max:100'],
                'phone' => ['required', 'string', 'max:20'],
                'whatsapp' => ['nullable', 'string', 'max:20'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('customers.edit', [
                    'customer' => $id
                ])
                    ->withErrors($validator)
                    ->withInput();
            }
            if ($customer->phone !== $data['phone']) {
                $hasPhone = Customer::where('phone', '=', $data['phone'])->first();
                if ($hasPhone) {
                    $validator->errors()->add('item', __('validation.unique', [
                        'attribute' => 'phone'
                    ]));
                } else {
                    $customer->phone = $data['phone'];
                }
            }
            if (count($validator->errors()) > 0) {
                return redirect()->route('customers.edit', [
                    'customer' => $id
                ])
                    ->withErrors($validator)
                    ->withInput();
            }
            $customer->name = $data['name'];
            $customer->email = $data['email'];
            $customer->address = $data['address'];
            $customer->whatsapp = $data['whatsapp'];
            $customer->save();

            return redirect()->route('customers.edit', [
                'customer' => $id
            ])->with('warning', 'Informações salvas com sucesso!');
        }
        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return redirect()->route('customers.index');
    }
}
