<?php

namespace TeamZac\POI\Contracts;

use TeamZac\POI\Support\Address;

interface SearchQueryInterface
{
    /**
     * Search using the given term
     * 
     * @param   string $term
     * @return  $this
     */
    public function search($term = null);

    /**
     * Search near a given address
     * 
     * @param   Address $address
     * @throws  InsufficientAddressException
     * @return  $this
     */
    public function near(Address $address);

    /**
     * Search within a given boundary
     * 
     * @param   
     * @return  $this
     */
    public function within($geometry);

    /**
     * Run the query 
     * 
     * @return  PlaceCollection
     */
    public function get();

    /**
     * Run the query based on the given next page cursor
     * 
     * @param   string $cursor
     * @return  PlaceCollection
     */
    public function fromCursor($cursor);
}