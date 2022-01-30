<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:edit-company');
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

        if ($company) { //empresa já cadastrada, altera os valores
            $data = $request->only([
                'name',
                'address',
                'email',
                'phone'
            ]);

            $validator = Validator::make([
                'name' => $data['name'],
                'address' => $data['address'],
                'email' => $data['email'],
                'phone' => $data['phone']
            ], [
                'name' => ['required', 'string', 'max:100'],
                'address' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:100', 'unique:companies'],
                'phone' => ['required', 'string', 'max:13', 'min:10']
            ]);

            if ($validator->fails()) {
                return redirect()->route('company')
                    ->withErrors($validator)
                    ->withInput();
            }

            $company->name = $data['name'];
            $company->address = $data['address'];
            $company->email = $data['email'];
            $company->phone = $data['phone'];
            $company->save();
        } else { //empresa não cadastrada, cria cadastro

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
                'email' => ['required', 'string', 'email', 'max:100', 'unique:companies'],
                'cnpj' => ['required', 'string', 'max:18', 'min:14'],
                'phone' => ['required', 'string', 'max:13', 'min:10']
            ]);

            if ($validator->fails()) {
                return redirect()->route('company')
                    ->withErrors($validator)
                    ->withInput();
            }
            $newCompany = new Company;
            $newCompany->user_id = $loggedId;
            $newCompany->name = $data['name'];
            $newCompany->address = $data['address'];
            $newCompany->email = $data['email'];
            $newCompany->cnpj = $data['cnpj'];
            $newCompany->plan = 'free';
            $newCompany->phone = $data['phone'];
            $newCompany->save();
        }

        return redirect()->route('company')
            ->with('warning', 'Informações alteradas com sucesso!');
    }

    //             if (count($validator->errors()) > 0) {
    //                 return redirect()->route('profile', [
    //                     'user' => $loggedId
    //                 ])->withErrors($validator);
    //             }
    //             $user->save();

    //             return redirect()->route('profile')
    //                 ->with('warning', 'Informações alteradas com sucesso!');
    //         }
    //         return redirect()->route('profile');
    //     }
}
