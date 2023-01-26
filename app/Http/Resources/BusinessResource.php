<?php

namespace App\Http\Resources;

use App\Models\BusinessesCategory;
use App\Models\BusinessesCoordinate;
use App\Models\BusinessesLocation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $category = new BusinessesCategory();
        $categories = $category->getCategories($this->id);
        $coordinate = new BusinessesCoordinate();
        $coord = $coordinate->getCoordinate($this->id);
        $locations = new BusinessesLocation();
        $location = $locations->getLocation($this->id);
        
        return [
            'id' => $this->id,
            'alias' => $this->alias,
            'categories' => $categories,
            'coordinates' => $coord,
            'phone' => $this->phone,
            'distance' => $this->distance,
            "image_url" => asset('storage/'.$this->image_url), 
            "is_closed" => $this->is_closed,
            "location" => $location,
            "name" => $this->name,
            "phone" => $this->phone,
            "price" => $this->price,
            "rating" => $this->rating,
            "review_count" => $this->review_count,
            "transactions" => $this->transaction_url,
            "url" => $this->url

        ];
    }
}
