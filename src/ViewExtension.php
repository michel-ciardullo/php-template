<?php

namespace App;

class ViewExtension {

    /**
     * @return array
     */
    public function getGlobals(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            'currentYear' => fn() => date('Y')
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            'uppercase' => fn($str) => strtoupper($str)
        ];
    }

    /**
     * @return array
     */
    public function getComponents(): array
    {
        return [];
    }

}