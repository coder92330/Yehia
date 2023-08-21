<?php

namespace App\Services;

use Cheesegrits\FilamentGoogleMaps\Fields\Map as BaseMap;

class Map extends BaseMap
{
    public function getTypes(): array
    {
        $types = $this->evaluate($this->types);

        if (count($types) === 0) {
            $types = [];
        }

        return $types;
    }
}
