<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SsoIvao extends Controller
{
    public function redirectToIVAO()
    {
        $query = http_build_query([

            'client_id' => config('services.ivao.client_id'),
            'redirect_uri' => route('ivao.callback'),
            'response_type' => 'code',
            'scope' => 'profile email',
            'state' => 'xyz',
        ]);

        return redirect("https://sso.ivao.aero/authorize?$query");
    }

    public function handleCallback(Request $request)
    {
        $response = Http::asForm()->post('https://api.ivao.aero/v2/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.ivao.client_id'),
            'client_secret' => config('services.ivao.client_secret'),
            'redirect_uri' => route('ivao.callback'),
            'code' => $request->code,
        ]);
        if (!$response->successful()) {
            return redirect('/')->withErrors('No se pudo autenticar con IVAO.');
        }

        $accessToken = $response->json('access_token');
        session([
            'ivao_access_token' => $accessToken,
        ]);

        $userResponse = Http::withToken($accessToken)->get('https://api.ivao.aero/v2/users/me');
        $ivaoUser = $userResponse->json();


        $user = User::updateOrCreate(
            [
                'vid_ivao' => $ivaoUser['id'],
            ],
            [
                'firstname' => $ivaoUser['firstName'],
                'lastname' => $ivaoUser['lastName'],
                'email' => $ivaoUser['email'],
                'division_id' => $ivaoUser['divisionId'],
                'country_id' => $ivaoUser['countryId'],
                'pilotRating_name' => $ivaoUser['rating']['pilotRating']['name'],
                'pilotRating_short' => $ivaoUser['rating']['pilotRating']['shortName'],
                'rank_ivao' => $ivaoUser['userStaffPositions'][0]['id'] ?? null,
            ]

        );
        Auth::login($user);

        return redirect('/dashboard');

    }
}
