<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ValidateLoginInput;
use App\Http\Requests\ValidateRegisterInput;
use Laravel\Passport\RefreshTokenRepository;

class UserController extends Controller
{

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::all());
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
            
            $request_array = [
                "grant_type"=> "password",
                "username"=> $request->email,
                "password"=> $request->password,
                "scope"=> ""
            ];
            
            $token = $this->user->internalAPI('oauth/token', 'POST', $request_array);

            return response([ 'user'=> $user, 'token'=> $token ], 200);
        }

        return response([ 'message'=> 'wrong' ], 500);

    }

    public function refreshToken(Request $request)
    {

        if(!$request->refresh_token || empty($request->refresh_token)) return response()->json([ "message"=> "Refresh token missing." ]);

        $request_array = [
            "grant_type"=> "refresh_token",
            "refresh_token"=> $request->refresh_token,
        ];

        $token = $this->user->internalAPI('oauth/token', 'POST', $request_array);

        return response([ "token"=> $token ]);

    }

    public function profile()
    {

        $user = Auth::user();
        // return response([ 'profile'=> $user ], 200);
        return new UserResource($user);

    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $token->delete();

        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

        return response([ 'message'=> 'success' ], 200);
    }
    
}
