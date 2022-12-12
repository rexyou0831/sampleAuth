<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
// use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'id',
        'created_at',
        'updated_at',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Create Random User
    public function createRandomUser()
    {

        $response = Http::get('https://randomuser.me/api/');

        $result = $response->collect('results')->first();

        // $last_name = str_replace(' ', '_', $result['name']['last']);
        $last_name = preg_replace('~ -~', '_', $result['name']['last']);

        $filename = strtolower($result['name']['first'].'_'.$last_name).'.txt';

        Storage::disk('local')->put($filename, json_encode($result));

        return $result;
    }


    // Read Random User
    public function readRandomUser($filename)
    {
        $path = Storage::disk('local')->get($filename);

        $response = json_decode($path);

        return $response;
    }
}
