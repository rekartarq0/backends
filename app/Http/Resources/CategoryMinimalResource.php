<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMinimalResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', app()->getLocale());
        $localizedLang = in_array($lang, ['ar', 'ckb']) ? $lang : app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->{'name_' . $localizedLang},
        ];
    }
}
