<?php


namespace App\Services\AccountService\Utils;

/**
 * Class AccountHelper
 * @package App\Services\AccountService\Utils
 */
class AccountHelper
{
    /**
     * @param array $data
     * @return array
     */
    public function prepareData(array $data)
    {
        return [
            'client_id' => $data['clientId'],
            'account_name' => trim($data['accountName']),
            'iban' => trim($data['iban']),
            'balance' => $data['balance'],
            'currency' => $data['currency'],
        ];
    }
}
