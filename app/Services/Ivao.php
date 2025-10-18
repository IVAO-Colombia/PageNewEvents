<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class Ivao
{

    protected $ApiKey;


    public function __construct()
    {
        $this->ApiKey = config('services.ivao.api_key');
    }


    public function getImagenAirlines($iaco)
    {

        try{
            $response = Http::withHeaders([
                  'apiKey' => $this->ApiKey,
            ])->get('https://api.ivao.aero/v2/airlines/' . $iaco.'/logo');

            if ($response->successful()) {
                $base64Image = 'data:image/png;base64,' . base64_encode($response->body());

                $result = ['logo' => $base64Image];
                return $result;
            } else {
                Log::error('Error fetching airline image', [
                    'iaco' => $iaco,
                    'response' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching airline image', [
                'iaco' => $iaco,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function getPositionsAtc($icao)
    {
        try {
            $response = Http::withHeaders([
                'apiKey' => $this->ApiKey,
            ])->get('https://api.ivao.aero/v2/positions/search?icao=' . $icao . '&limit=20');

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Error fetching ATC positions', [
                    'icao' => $icao,
                    'response' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching ATC positions', [
                'icao' => $icao,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function verifiedRankAtc($callsing, $vid)
    {
        try{

            $response = Http::withHeaders([
                'apiKey' => $this->ApiKey,
            ])->get('https://api.ivao.aero/v2/fras/check/' . $callsing . '/' . $vid);

            if($response->status() == 200){
                return 200;
            }else
            {
                return 404;
            }
        }catch (\Exception $e) {
            Log::error('Error verifying ATC rank', [
                'callsing' => $callsing,
                'vid' => $vid,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

}


