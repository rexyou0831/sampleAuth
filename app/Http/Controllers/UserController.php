<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ValidateLoginInput;
use App\Http\Requests\ValidateRegisterInput;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateRegisterInput $request)
    {
        // Validated input
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $token = $user->createToken(config('app.name'))->accessToken;

        return response([ 'user'=> $validated, 'token'=> $token ], 200);

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(ValidateLoginInput $request)
    {

        $validated = $request->validated();

        // $response = auth()->attempt($validated);
        $response = Auth::attempt($validated);
        if($response){
            // $user = auth()->user();
            $user = Auth::user();
            $token = $user->createToken(config('app.name'))->accessToken;
            return response([ 'user'=> $user, 'token'=> $token ], 200);
        }

        return response([ 'message'=> 'wrong' ], 500);

    }

    public function profile()
    {

        $user = Auth::user();
        return response([ 'profile'=> $user ], 200);

    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response([ 'message'=> 'success' ], 200);
    }
}
