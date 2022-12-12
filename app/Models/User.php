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

    // Get All Country
    public function getCountry()
    {
        $response = Http::get('https://restcountries.com/v3.1/all');

        $response = $response->json();

        $array = [];

        foreach($response as $key => $value)
        {
            $common_name = $value['name']['common'];

            $common_name = $this->stripAccents($common_name);

            $name = preg_replace('~ ~', '_', strtolower($common_name));

            $currency = $value['currencies'] ?? "";

            $new_array = [];
            $new_array['common_name'] = $common_name;
            $new_array['name'] = $name;
            $new_array['display_name'] = $value['name']['official'];
            $new_array['cca2'] = $value['cca2'];
            $new_array['currency'] = gettype($currency) == "array" ? array_keys($currency)[0] : "";
            $new_array['flag'] = $value['flags']['png'];

            $creation = Country::updateOrCreate([ "name"=> $name, "cca2"=> $value['cca2'] ], $new_array);

            if($creation){
                $array[] = $new_array;
            }

        }

        return $array;
    }

    public function stripAccents($str) {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAAACEEEEIIIINOOOOOUUUUY');
    }

    public function getSpecificCountry($code)
    {

        $internalSearch = Country::firstWhere([ "cca2"=> $code ]);

        $response = "";
        if($internalSearch){
            $response = Http::get("https://restcountries.com/v3.1/alpha/$code");
            $response = $response->collect()->first();
        }

        return $response;

    }

}
