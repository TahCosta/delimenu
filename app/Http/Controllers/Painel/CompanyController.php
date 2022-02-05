<?php

namespace App\Http\Controllers\Painel;

use DateTime;
use DateInterval;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Input;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $loggedId = intval(Auth::id());
        $company = Company::where('user_id', $loggedId)->first();
        if ($company) {

            return view('painel.company.index', [
                'company' => $company,
            ]);
        } else {
            return view('painel.company.create');
        }


        return redirect()->route('painel');
    }

    public function save(Request $request)
    {
        $loggedId = intval(Auth::id());
        $company = Company::where('user_id', $loggedId)->first();
        $data = $request->only([
            'name',
            'address',
            'email',
            'cnpj',
            'phone'
        ]);

        $validator = Validator::make([
            'name' => $data['name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'cnpj' => $data['cnpj'],
            'phone' => $data['phone']
        ], [
            'name' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100'],
            'cnpj' => ['required', 'string', 'min:18', 'max:18'],
            'phone' => ['nullable', 'string', 'min:14', 'max:15'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('company')
                ->withErrors($validator)
                ->withInput();
        }



        if ($company) { //empresa já cadastrada, altera os valores
            $company->name = $data['name'];
            $company->address = $data['address'];
            $company->email = $data['email'];
            $company->phone = $data['phone'];
            $company->save();
        } else { //empresa não cadastrada, cria cadastro

            $newCompany = new Company;
            $newCompany->user_id = $loggedId;
            $newCompany->name = $data['name'];
            $newCompany->address = $data['address'];
            $newCompany->email = $data['email'];
            $newCompany->cnpj = $data['cnpj'];
            $newCompany->plan = 'free';
            $newCompany->phone = $data['phone'];
            $newCompany->save();

            $user = User::find($loggedId);
            $user->company_id = $newCompany->id;
            $user->save();

            $inputs = Input::where('user_id','=',$loggedId);
            $inputs->category_id = $newCompany->id;
            $inputs->save();

            $products = Product::where('user_id','=',$loggedId);
            $products->category_id = $newCompany->id;
            $products->save();

            $category = Category::where('user_id','=',$loggedId);
            $category->category_id = $newCompany->id;
            $category->save();
        }

        return redirect()->route('company')
            ->with('warning', 'Informações salvas com sucesso!');
    }
}
