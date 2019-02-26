<?php

namespace TeamZac\POI\Contracts;

use TeamZac\POI\Support\Address;

interface MatchQueryInterface
{
    /**
     * Search using the given term.
     *
     * @param   string $term
     * @return  $this
     */
    public function search($term = null);

    /**
     * Search using a given phone number, where available.
     *
     * @param   string $phone
     * @return  $this
     */
    public function phone($phone = null);

    /**
     * Search near a given address.
     *
     * @param   Address $address
     * @throws  InsufficientAddressException
     * @return  $this
     */
    public function near(Address $address);

    /**
     * Search within a given boundary.
     *
     * @param
     * @return  $this
     */
    public function within($geometry);

    /**
     * Run the query.
     *
     * @return  Place
     */
    public function get();
}
