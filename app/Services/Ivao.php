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


}


