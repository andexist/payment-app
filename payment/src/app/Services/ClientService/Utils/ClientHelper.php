<?php

namespace App\Services\ClientService\Utils;

/**
 * Class ClientHelper
 * @package App\Services\ClientService\Utils
 */
class ClientHelper
{
    /**
     * @param array $data
     * @return array
     */
    public function prepareData(array $data)
    {
        return [
            'username' => trim($data['username']),
            'first_name' => trim($data['firstName']),
            'last_name' => trim($data['lastName']),
        ];
    }
}
