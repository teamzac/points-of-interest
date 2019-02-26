<?php

namespace TeamZac\POI\Contracts;

interface ProviderInterface
{
    /**
     * Retrieve the record with the given ID.
     *
     * @param   string $id
     * @return  TeamZac\POI\Support\Place
     */
    public function retrieve($id);

    /**
     * Get a match query object for this provider.
     *
     * @param   string $term
     * @return  TeamZac\POI\Contracts\MatchQueryInterface
     */
    public function match($term = null);

    /**
     * Get a search query object for this provider.
     *
     * @param   string $term
     * @return  TeamZac\POI\Contracts\SearchQueryInterface
     */
    public function search($term = null);
}
