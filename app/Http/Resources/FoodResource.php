<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', app()->getLocale());

        // Check if the requested language is 'ar' or 'ckb', otherwise use the default app locale
        $localizedLang = in_array($lang, ['ar', 'ckb']) ? $lang : app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->{'name_' . $localizedLang}, // Fetch localized name based on $localizedLang
            'image' => $this->image,
            'is_available' => $this->is_available,
            'price'=>$this->price,
            'full_path_image_relable' => $this->full_path_image_relable,
            'created_at_relable' => $this->created_at_relable,
            'category' => new CategoryMinimalResource($this->category), // Use the minimal resource here
            'user_id' => new UserResource($this->user), // Assuming UserResource exists
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'lang' => $request->header('Accept-Language', app()->getLocale()),
                'pagination' => [
                    'total' => $this->resource->total(),
                    'per_page' => $this->resource->perPage(),
                    'current_page' => $this->resource->currentPage(),
                    'last_page' => $this->resource->lastPage(),
                    'from' => $this->resource->firstItem(),
                    'to' => $this->resource->lastItem(),
                ],
            ],
            'data' => $this->resource->items(),
        ];
    }
}
