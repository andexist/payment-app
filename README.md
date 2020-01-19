# payment-app
payment app with laravel and docker

Run => 
* payment-app:~ docker-compose up -d
Run migrations => 
* docker exec -it payment bash
* php artisan migrate
      
Run generate app key if not working
* php artisan key:generate
* php artisan config:cache

Api:
(clients)
* api/clients => get all clients
* api/clients/{id} => get client
* api/clients/{clientId}/accounts => get client accounts
* api/clients/{clientId}/pymnets => get client payments
* api/clients/create => create client

(accounts)
* api/accounts => get all accounts
* api/accounts/{id} => get account
* api/accounsts/{accountId}/payments => get account paymenst
* api/accounts/create => create account

Api (payments)
* api/payments/create => create payment
* api/payments/approve => approve payment
* api/payments/reject => reject payment


Validation:
(clients)
* username: required|unique
* firstName: required
* lastName: required
    
(accounts)
* clientId: required|exists in clients table
* accountName: required
* iban: required|max32|regex(2 letters min 9 numbers: exmpl => LT123456789)
* balance: required|min0
* currency: required|in Available currencies [EUR, USD, GBP], you can add more in Account model
 
(payments)
* accountId: required|exists in accounts table
* currency: required|in Available currencies [EUR, USD, GBP], you can add more in Account model
* amount: required
* receiverAccount: required
* receiverName: required
* details: required
  
Examples

(clients)
* api/clients/create: {
      "username": "superman",
      "firstName": "Kent",
      "lastName": "Clark"
     }
    
(accounts)
* api/accounts/create: {
      "clientId": 1,
      "accountName": "EUR account",
      "iban": "LT123456789",
      "amount": 312.52,
      "currency": "EUR",
}
    
(payments)
* api/payments/create: {
      "accountId": 1,
      "currency": "EUR",
      "receiverAccount": "LT987654321",
      "receiverName": "Batman",
      "details": "debt on a kebab",
}  
    
    api/payments/approve: {
      "paymentId": 1,
      "code": 111,
    }  
    
    api/payments/reject: {
      "paymentId": 1,
    }  
    
 Note: you canot create new payment until you approve/reject last one
 
Complete payments
* docker ecex -it payment bash
* php artisan payments:process 1 (1 is client Id)

Run test
* docker ecex -it payment bash
* vendor/bin/phpunit
  
