<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'address' => [
                'street' => $this->address_street,
                'cityState' => $this->address_city_state,
                'zip' => $this->address_zip,
                'googleMapsUrl' => $this->google_maps_url,
            ],
            'social' => [
                'facebook' => $this->social_facebook,
                'instagram' => $this->social_instagram,
                'linkedin' => $this->social_linkedin,
                'youtube' => $this->social_youtube,
                'tiktok' => $this->social_tiktok,
            ],
        ];
    }
}