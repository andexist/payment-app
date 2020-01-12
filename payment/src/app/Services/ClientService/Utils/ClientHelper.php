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
          'username' => $data['username'],
          'first_name' => $data['firstName'],
          'last_name' => $data['lastName'],
        ];
    }
}
