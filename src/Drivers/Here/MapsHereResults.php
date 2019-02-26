<?php

namespace TeamZac\POI\Drivers\Here;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;

trait MapsHereResults
{
    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function mapResultToPlace($result)
    {
        return (new Place)->setRaw($result)->map([
            'provider' => 'here',
            'id' => $this->findValueWithFallback($result, 'placeId', 'id'),
            'name' => $this->findValueWithFallback($result, 'name', 'title'),
            'address' => Address::make([
                'formatted' => str_replace(
                    '<br/>', ', ', $this->findValueWithFallback($result, 'location.address.text', 'vicinity')
                ),
                'latLng' => LatLng::make(
                    $this->findValueWithFallback($result, 'location.position.0', 'position.0'),
                    $this->findValueWithFallback($result, 'location.position.1', 'position.1')
                ),
            ]),
            'phone' => str_replace('+', '', Arr::get($result, 'contacts.phone.0.value')),
            'categories' => $this->mergeCategoriesAndTags($result),
            'references' => collect(Arr::get($result, 'references'))->map(function($reference, $key) {
                return [
                    'service' => $key,
                    'id' => $reference['id'],
                ];
            })->values()->toArray(),
        ]);
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function findValueWithFallback($array, $key, $fallback)
    {
        if (!$value = Arr::get($array, $key)) {
            $value = Arr::get($array, $fallback);
        }
        return $value;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function mergeCategoriesAndTags($result)
    {
        if (!$categories = Arr::get($result, 'categories')) {
            $category = Arr::get($result, 'category');
            if (is_string($category)) {
                $categories = [
                    'id' => $category,
                ];
            } else {
                $categories = [$category];
            }
        }

        return $this->extractTaxonomyIds($categories)
            ->concat(
                $this->extractTaxonomyIds(Arr::get($result, 'tags'))
            )
            ->unique()
            ->toArray();
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function extractTaxonomyIds($array)
    {
        return collect($array)->pluck('id');
    }
    
}