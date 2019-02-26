<?php

namespace TeamZac\POI\Support;

use TeamZac\POI\Facades\POI;
use Illuminate\Support\Collection;

class PlaceCollection extends Collection
{
    /** @var string */
    protected $provider;

    /** @var string */
    protected $nextPageCursor;

    /**
     * Set the provider for this collection.
     *
     * @param   string $provider
     * @return  $this
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Set the cursor for the next page query.
     *
     * @param   string $cursor
     * @return  $this
     */
    public function setCursor($cursor)
    {
        $this->nextPageCursor = $cursor;

        return $this;
    }

    /**
     * @param
     * @return
     */
    public function nextPage()
    {
        return POI::driver($this->provider)->search()->fromCursor($this->nextPageCursor);
    }
}
