<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        try {
            if (!$request->has('code')) {
                Log::error('No se recibió código de autorización de IVAO');
                return redirect('/')->withErrors('No se recibió código de autorización de IVAO.');
            }

            $response = Http::asForm()->post('https://api.ivao.aero/v2/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => config('services.ivao.client_id'),
                'client_secret' => config('services.ivao.client_secret'),
                'redirect_uri' => route('ivao.callback'),
                'code' => $request->code,
            ]);

            if (!$response->successful()) {
                Log::error('Error de autenticación IVAO', ['response' => $response->json()]);
                return redirect('/')->withErrors('No se pudo autenticar con IVAO: ' . $response->status());
            }

            $accessToken = $response->json('access_token');
            session(['ivao_access_token' => $accessToken]);

            $userResponse = Http::withToken($accessToken)->get('https://api.ivao.aero/v2/users/me');

            if (!$userResponse->successful()) {
                Log::error('Error al obtener datos de usuario IVAO', ['response' => $userResponse->json()]);
                return redirect('/')->withErrors('No se pudieron obtener datos de usuario de IVAO.');
            }

            $ivaoUser = $userResponse->json();

            Log::info('Datos de usuario IVAO recibidos', ['user_id' => $ivaoUser['id']]);

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
                    'pilotRating_name' => $ivaoUser['rating']['pilotRating']['name'] ?? null,
                    'pilotRating_short' => $ivaoUser['rating']['pilotRating']['shortName'] ?? null,
                    'rank_ivao' => $ivaoUser['userStaffPositions'][0]['id'] ?? null,
                ]
            );


            if (!$user) {
                Log::error('No se pudo crear o actualizar el usuario');
                return redirect('/')->withErrors('Error al crear el usuario.');
            }

            Auth::login($user);


            if (Auth::check()) {
                Log::info('Usuario autenticado correctamente', ['user_id' => $user->id]);
                return redirect()->intended('/dashboard');
            } else {
                Log::error('La autenticación falló después de Auth::login()');
                return redirect('/')->withErrors('Error de autenticación.');
            }
        } catch (\Exception $e) {
            Log::error('Excepción en IVAO callback', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->withErrors('Error durante la autenticación: ' . $e->getMessage());
        }
    }
}
