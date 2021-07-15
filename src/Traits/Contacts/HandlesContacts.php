<?php

namespace Bryceandy\Beem\Traits\Contacts;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Traits\MakesHttpRequests;
use Carbon\Carbon;
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

    /**
     * @param string $addressbook_id
     * @param string $addressbook
     * @param string $description
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function editAddressBook(string $addressbook_id, string $addressbook, string $description): Response
    {
        return $this->call(
            "https://apicontacts.beem.africa/public/v1/address-books/$addressbook_id",
            'PUT',
            compact('addressbook', 'description')
        );
    }

    /**
     * @param string $addressBookId
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function deleteAddressBook(string $addressBookId): Response
    {
        return $this->call(
            "https://apicontacts.beem.africa/public/v1/address-books/$addressBookId",
            'DELETE'
        );
    }

    /**
     * @param string $addressBookId
     * @param string|null $q
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function contacts(string $addressBookId, string $q = null): Response
    {
        $data = $q ? compact('q') : [];

        return $this->call(
            "https://apicontacts.beem.africa/public/v1/contacts?addressbook_id=$addressBookId",
            'GET',
            $data
        );
    }

    /**
     * @param array $addressbook_id
     * @param string $mob_no
     * @param string|null $fname
     * @param string|null $lname
     * @param string|null $title
     * @param string|null $gender
     * @param string|null $mob_no2
     * @param string|null $email
     * @param string|null $country
     * @param string|null $city
     * @param string|null $area
     * @param string|null $birth_date
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function addContact(
        array $addressbook_id,
        string $mob_no,
        string $fname = null,
        string $lname = null,
        string $title = null,
        string $gender = null,
        string $mob_no2 = null,
        string $email = null,
        string $country = null,
        string $city = null,
        string $area = null,
        string $birth_date = null
    ): Response
    {
        $data = collect(compact(
            'fname', 'lname', 'title', 'gender', 'mob_no2', 'email', 'country', 'city', 'area', 'birth_date'
        ))
            ->reject(fn($datum) => is_null($datum))
            ->map(fn($item, $key) => $key === 'birth_date'
                ? Carbon::parse($item)->format('Y-m-d')
                : $item
            )
            ->all();

        return $this->call(
            'https://apicontacts.beem.africa/public/v1/contacts',
            'POST',
            array_merge(compact('mob_no', 'addressbook_id'), $data)
        );
    }

    /**
     * @param string $contact_id
     * @param array $addressbook_id
     * @param string $mob_no
     * @param string|null $fname
     * @param string|null $lname
     * @param string|null $title
     * @param string|null $gender
     * @param string|null $mob_no2
     * @param string|null $email
     * @param string|null $country
     * @param string|null $city
     * @param string|null $area
     * @param string|null $birth_date
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function editContact(
        string $contact_id,
        array $addressbook_id,
        string $mob_no,
        string $fname = null,
        string $lname = null,
        string $title = null,
        string $gender = null,
        string $mob_no2 = null,
        string $email = null,
        string $country = null,
        string $city = null,
        string $area = null,
        string $birth_date = null
    ): Response
    {
        $data = collect(compact(
            'fname', 'lname', 'title', 'gender', 'mob_no2', 'email', 'country', 'city', 'area', 'birth_date'
        ))
            ->reject(fn($datum) => is_null($datum))
            ->map(fn($item, $key) => $key === 'birth_date'
                ? Carbon::parse($item)->format('Y-m-d')
                : $item
            )
            ->all();

        return $this->call(
            "https://apicontacts.beem.africa/public/v1/contacts/$contact_id",
            'PUT',
            array_merge(compact('mob_no', 'addressbook_id'), $data)
        );
    }

    /**
     * @param array $addressbook_id
     * @param array $contacts_id
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function deleteContact(array $addressbook_id, array $contacts_id): Response
    {
        return $this->call(
            'https://apicontacts.beem.africa/public/v1/contacts',
            'DELETE',
            compact('addressbook_id', 'contacts_id')
        );
    }
}