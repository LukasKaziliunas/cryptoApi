# Api documentation

* [User](#User)
* [Authentication](#Authentication)
* [Assets](#Assets)
* [Errors](#Errors)

## Important

Currently for simplicity's sake and testing logging in is not required, a default static user will be automaticaly authenticated before every request. To generate a default user please seed the database.

`php artisan migrate --seed`


## User

* [Register](#Register)
* [Login](#Login)
* [Profile](#Profile)
* [Logout](#Logout)
* [Refresh token](#Refresh-token)

### Register

create a user account 
* Url

`POST` localhost/api/register
* Body Parameters

| Parameter | Type      | Description |
| --------- | --------- | ----------- |
| name        | string | name of the user, must be entirely alphabetic characters, max length is 255 |
| lastname        | string | last name of the user, must be entirely alphabetic characters, max length is 255 |
| email        | string | email address, used for logging in, max length is 255 |
| password        | string | password, at least 8 chars long, up to 255 characters |
| password2        | string | must match password |

* Success response
  * **Code:** 200 <br />
    **Content:** 
    
  ```
    {
        "message" : "Successfully Created user"
    }
  ```
 * **Error Response:**

    * **Code:** 422 UNPROCESSABLE ENTITY<br />
    **Content:** 
    ```
    {
        "errors": {
            "<field name>": [
                "<error message>"
            ]
        },
        "fields": {
            "name": <value>,
            "email": <value>,
            "password": <value>,
            "password2": <value>,
            "lastname": <value>
        }
    }
    ```

**[⬆ Back to top](#Api-documentation)**

### Login

recieve an authorization token
* Url

`POST` localhost/api/login
* Body Parameters

| Parameter | Type      | Description |
| --------- | --------- | ----------- |
| email        | string | email address of the account |
| password        | string | password of the account |

* Success response
  * **Code:** 200 <br />
    **Content:** 
    
  ```
    {
    "token": <token>
    }
  ```
 * **Error Response:**

    * **Code:** 401 UNAUTHORIZED<br />
    **Content:** 
    ```
    {
    "error": "Invalid Credentials"
    }
    ```

    * **Code:** 422 UNPROCESSABLE ENTITY<br />
    **Content:** 
    ```
    {
        "errors": {
            "<field name>": [
                "<error message>"
            ]
        },
        "fields": {
            "email": <value>,
            "password": <value>
        }
    }
    ```

**[⬆ Back to top](#Api-documentation)**

### Profile

get logged in user profile data
* Url

`GET` localhost/api/user
* headers

`Authorization: Bearer <token>`

* Success response
  * **Code:** 200 <br />
    **Content:** 
    
  ```
    {
        "user": {
            "name": "Jonas",
            "lastname": "Jonaitis",
            "email": "jonas@example.com"
        }
    }
  ```
 * **Error Response:**

    * **Code:** 401 UNAUTHORIZED <br />
        **Content:** 
    ```
    { "message": "Token not parsed" }
    ```

**[⬆ Back to top](#Api-documentation)**

### Logout

makes auth token invalid
* Url

`GET` localhost/api/logout
* headers

`Authorization: Bearer <token>`

* Success response
  * **Code:** 200 <br />
    **Content:** 
    
  ```
    {
    "message": "Successfully logged out"
    }
  ```
 * **Error Response:**

    * **Code:** 401 UNAUTHORIZED <br />
        **Content:** 
    ```
    { "message": "Token not parsed" }
    ```

**[⬆ Back to top](#Api-documentation)**

### Refresh token

recieve a new token.
* Url

`GET` localhost/api/refresh
* headers

`Authorization: Bearer <token>`

* Success response
  * **Code:** 200 <br />
    **Content:** 
    
  ```
   {
    "token": <new token>
    }
  ```
 * **Error Response:**

    * **Code:** 401 UNAUTHORIZED <br />
        **Content:** 
    ```
    {
    "message": "Invalid token"
    }
    ```

**[⬆ Back to top](#Api-documentation)**

## Authentication

When accesing any endpoint described in [Assets](#Assets) section, you are required to provide an auth token inside a header of the 
request.

`Authorization: Bearer <token>`

Authorization token can be obtained by [logging in](#Login). 
## Assets

* [Show portfolio](#Show-assets)
* [Show asset](#Show-asset)
* [Save asset](#Save-asset)
* [Update asset](#Update-asset)
* [Delete asset](#Delete-asset)

### Show portfolio

show user portfolio, all prices are in USD.
* Url

`GET` localhost/api/assets

* Success response
  * **Code:** 200 <br />
    **Content:** 
    
  ```
   {
    "total": 143572.5947827543,
    "data": [
        {
            "id": 3,
            "label": "wallet 1",
            "amount": 1.2,
            "crypto": "BTC",
            "price": 50672.532368162756
        },
        {
            "id": 4,
            "label": "binance account",
            "amount": 2,
            "crypto": "DOGE",
            "price": 0.41973962649826
        },
        {
            "id": 6,
            "label": "wallet 2",
            "amount": 1,
            "crypto": "BTC",
            "price": 42227.1103068023
        },
        {
            "id": 7,
            "label": "ledger nano wallet",
            "amount": 1.2,
            "crypto": "BTC",
            "price": 50672.532368162756
        }
    ]
    }
  ```
 * **Error Response:**

    * **Code:** 401 UNAUTHORIZED <br />
    **Content:** 
    ```
    { "message": "Token not parsed" }
    ```

**[⬆ Back to top](#Api-documentation)**

### Show asset 

show a particular asset.
* Url

`GET` localhost/api/assets/{id}

* Route Parameters

| Parameter | Type      | Description |
| --------- | --------- | ----------- |
| id        | integer | id of the asset |

* Success response
  * **Code:** 200 <br />
    **Content:** 
    
  ```
    {
        "data": {
            "id": 3,
            "label": "binance",
            "amount": 0.05,
            "crypto": "BTC",
            "price": 2119.9982980265418
        }
    }
  ```
 * **Error Response:**

    * **Code:** 401 UNAUTHORIZED <br />
    **Content:** 
    ```
    { "message": "Token not parsed" }
    ```
    
    * **Code:** 404 NOT FOUND <br />
    **Content:** 
    ```
    { "message": "Object not found" }
    ```

**[⬆ Back to top](#Api-documentation)**
### Save asset
 
* Url

`POST` localhost/api/assets

* Body Parameters

| Parameter | Type      | Description |
| --------- | --------- | ----------- |
| label        | string | label of the asset, max length is 255 |
| amount        | double | the amount of crypto currency, min value is 0 |
| crypto        | string | symbol of the crypto currency, allowed values: BTC, ETH, DOGE |


* Success response
  * **Code:** 201 <br />
    **Content:** 
    
  ```
    {
        "message": "asset created.",
        "id": 4
    } 
  ```
 * **Error Response:**

    * **Code:** 401 UNAUTHORIZED <br />
    **Content:** 
    ```
    { "message": "Token not parsed" }
    ```

    * **Code:** 422 UNPROCESSABLE ENTITY<br />
    **Content:** 
    ```
    {
        "errors": {
            "<field name>": [
                "<error message 1>"
                "<error message 2>",
            ],
            "<field name>": [
                "<error message>",
            ]
        },
        "fields": {
            "name": <value>,
            "lastname": <value>,
            "position_id": <value>,
        }
    }
    ```

**[⬆ Back to top](#Api-documentation)**
### Update asset
 
* Url

`PUT` localhost/api/assets/{id}

* Route Parameters

| Parameter | Type      | Description |
| --------- | --------- | ----------- |
| id        | integer | id of the asset |

* Body Parameters

| Parameter | Type      | Description |
| --------- | --------- | ----------- |
| label        | string | lanel of the asset, max length is 255 |
| amount        | double | the amount of crypto currency, min value is 0 |
| crypto        | string | symbol of the crypto currency, allowed values: BTC, ETH, DOGE |

* Success response
  * **Code:** 200 <br />
    **Content:** 
    
  ```
    {
        "message": "asset updated.",
        "id": 7
    }
  ```
 * **Error Response:**

    * **Code:** 401 UNAUTHORIZED <br />
    **Content:** 
    ```
    { "message": "Token not parsed" }
    ```
    
    * **Code:** 404 NOT FOUND <br />
    **Content:** 
    ```
    { "message": "Object not found" }
    ```

    * **Code:** 422 UNPROCESSABLE ENTITY<br />
    **Content:** 
    ```
    {
        "errors": {
            "<field name>": [
                "<error message 1>"
                "<error message 2>",
            ],
            "<field name>": [
                "<error message>",
            ]
        },
        "fields": {
            "name": <value>,
            "lastname": <value>,
            "position_id": <value>,
        }
    }
    ```

**[⬆ Back to top](#Api-documentation)**
### Delete asset

* Url

`DELETE` localhost/api/assets/{id}

* Route Parameters

| Parameter | Type      | Description |
| --------- | --------- | ----------- |
| id        | integer | id of the asset |

* Success response
  * **Code:** 200 <br />
    **Content:** 
    
  ```
    {
    "message": "asset deleted."
    }
  ```
 * **Error Response:**

    * **Code:** 401 UNAUTHORIZED <br />
    **Content:** 
    ```
    { "message": "Token not parsed" }
    ```
    
    * **Code:** 404 NOT FOUND <br />
    **Content:** 
    ```
    { "message": "Object not found" }
    ```

**[⬆ Back to top](#Api-documentation)**

## Errors

* **Code:** 401 UNAUTHORIZED <br />
Returned when submitted token is not correct or user has logged out <br />
        **Content:** 
    ```
    {
    "message": "Invalid token"
    }
    ```

* **Code:** 401 UNAUTHORIZED <br />
Returned when submitted token is expired and needs to be refreshed <br />
        **Content:** 
    ```
    {
    "message": "Token has Expired"
    }
    ```

* **Code:** 401 UNAUTHORIZED <br />
Returned when no token is provided <br />
        **Content:** 
    ```
    {
    "message": "Token not parsed"
    }
    ```

* **Code:** 405 METHOD NOT ALLOWED <br />
Returned when wrong method is used for a route <br />
        **Content:** 
    ```
    {
    "message": "Method not allowed"
    }
    ```

* **Code:** 403 FORBIDDEN <br />
Returned when a current user is not authorized to perform an action <br />
        **Content:** 
    ```
    {
    "message": "This action is unauthorized"
    }
    ```

* **Code:** 500 INTERNAL SERVER ERROR <br />
Returned when an unecpected internal error occurs <br />
        **Content:** 
    ```
    {
    "message": "Internal error"
    }
    ```

* **Code:** 500 INTERNAL SERVER ERROR <br />
Returned if something bad happened while making a request against an external crypto api, more information is provided inside logs/exceptionsErrors.log file. <br />
        **Content:** 
    ```
    {
    "message": "Bad request to external api"
    }
    ```

**[⬆ Back to top](#Api-documentation)**