<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class IvaoAuthController extends Controller
{
    public function redirectToIVAO()
    {

        $scopes = 'profile configuration email';
        $query = http_build_query([
            'client_id' => config('services.ivao.client_id'),
            'redirect_uri' => route('ivao.callback'),
            'response_type' => 'code',
            'scope' => $scopes,
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

        //Id Staff Positions
        $staffadmin = ['CO-EC','CO-EAC','CO-EA1','CO-WM','CO-AWM','CO-WMA1','CO-DIR','CO-ADIR'];
        $isAdmin = false;

        if (isset($ivaoUser['userStaffPositions']) && is_array($ivaoUser['userStaffPositions'])) {
            foreach ($ivaoUser['userStaffPositions'] as $position) {
                if (
                    isset($position['id']) &&
                    in_array(strtoupper(trim($position['id'])), $staffadmin)
                ) {
                    $isAdmin = true;
                    break;
                }
            }
        }

        // dd($ivaoUser);
        // Crea o actualiza el usuario en tu DB
        $user = User::updateOrCreate(
            [
                'ivao_id' => $ivaoUser['id'],
            ],
            [
                'name' => $ivaoUser['firstName'] . ' ' . $ivaoUser['lastName'],
                'email' => $ivaoUser['email'],
                'Division_id' => $ivaoUser['divisionId'],
                'Contry_id' => $ivaoUser['countryId'],
                'atc_rating_name' => $ivaoUser['rating']['atcRating']['name'] ?? null,
                'atc_rating_short' => $ivaoUser['rating']['atcRating']['shortName'] ?? null,
                'pilot_rating_name' => $ivaoUser['rating']['pilotRating']['name'] ?? null,
                'pilot_rating_short' => $ivaoUser['rating']['pilotRating']['shortName'] ?? null,
                'is_admin' => $isAdmin,

            ]

        );
        Auth::login($user);

        return redirect('/profile');

    }
}
