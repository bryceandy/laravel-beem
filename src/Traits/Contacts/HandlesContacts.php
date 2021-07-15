<?php

namespace Bryceandy\Beem\Traits\Contacts;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Traits\MakesHttpRequests;
use Illuminate\Http\Client\Response;

trait HandlesContacts
{
    use MakesHttpRequests;

    /**
     * @param string|null $q
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function addressBooks(string $q = null): Response
    {
        $data = $q ? compact('q') : [];

        return $this->call(
            'https://apicontacts.beem.africa/public/v1/address-books',
            'GET',
            $data
        );
    }

    /**
     * @param string $addressbook
     * @param string $description
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function addAddressBook(string $addressbook, string $description): Response
    {
        return $this->call(
            'https://apicontacts.beem.africa/public/v1/address-books',
            'POST',
            compact('addressbook', 'description')
        );
    }
}