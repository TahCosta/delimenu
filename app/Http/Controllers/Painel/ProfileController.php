<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
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
        return view('painel.profile.index', [
            'user' => $user,
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
        $loggedId = intval(Auth::id());
         if($loggedId !== intval($id)){
            return redirect()->route('profile.index');
        } 
        $user = User::find($id);
        if ($user) {
            $data = $request->only([
                'name',
                'email',
                'password',
                'password_confirmation'
            ]);
            $validator = Validator::make([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation']
            ], [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email', 'max:100'],
                'password' => ['nullable', 'string'],
                'password_confirmation' => ['nullable', 'string']
            ]);

            if ($validator->fails()) {
                return redirect()->route('profile.index')
                    ->withErrors($validator)
                    ->withInput();
            }
            
            if(!is_null($data['password'])){
                if($data['password'] !== $data['password_confirmation'] || strlen($data['password'])< 8){ // alteração de senha
                    if(strlen($data['password']) < 8){
                        $validator->errors()->add('password', 'A senha deve ter, no mínimo, 8 caracteres');
                    }else{
                        $validator->errors()->add('password', 'As senhas não coincidem');
                    }
                    
                }
            }
            
            if (count($validator->errors()) > 0) {
                return redirect()->route('profile.index')
                    ->withErrors($validator)
                    ->withInput();
            }
            $user->name = $data['name'];
            $user->password = Hash::make($data['password']);
            $user->save();

            return redirect()->route('profile.index')
            ->with('warning', 'Informações salvas com sucesso!');
        }
        return redirect()->route('profile.index');
    }


}
