## About the Tech Test 
### Purpose
The aim of the API is to allow authenticated users to request product data.
To obtain a token, a user must register and log in.
Once a user has a token, they are then able to access the end point to retrieve products 
from the database, the response is displayed in JSON format.
The requirements stated a minimum of five products,
so I decided to restrict the number of products returned to 5 to ensure efficiency. 
Users are also able to log out and delete their tokens.

### API Endpoints:
#### Register
**/api/register** - POST request - Requires a name, email and password to register a user.
For example, to register a user, add the following to the body of the request:
```
{
    "name": "John Doe",
    "email": "john@doe.co.uk",
    "password": "password"
}
```

#### Login
**/api/login** - POST request - Requires an email and password to log in a user.
For example, to log in as an authenticated user, add the following to the body of the request:
```
{
    "email": "john@doe.co.uk",
    "password": "password"
}
```
Once a user has logged in, they will receive a token which is required to access the product end point.

#### Logout
**/api/logout** - POST request - Requires a token to log out a user.
For example, to log out a user, add the following to the body of the request:
```
{
    "Authorization: Bearer <TOKEN>"
}
```
Once a user has logged out, their token will be deleted.

#### Products
**/api/products** - GET request - Requires a token to access the product end point.
The end point retrieves a list of products available in the system.
It is designed to return a maximum of five products at a time to ensure efficient data transfer and processing.
For example, to access the product end point, add the following to the header of the request:
```
{
    "Authorization: Bearer <TOKEN>"
}
```

#### Request Format
- Method: GET
- Authentication Required: Yes
- Headers: Authorization: Bearer "TOKEN" 
  - The token is obtained through the authentication process, typically by logging in.

#### Response Format
- Content-Type: application/json
- Status Codes:
  * 200 OK: Successfully retrieved the list of products.
  * 401 Unauthorized: The request lacks valid authentication credentials.
- Body:
 A JSON array of product objects, each containing:
  * id (integer): The unique identifier of the product.
  * name (string): The name of the product.
  * description (string): A brief description of the product.
  * price (float): The price of the product.
  * stock (integer): The quantity of the product in stock.
  * on_order (integer): The quantity of the product currently on order.
  * created_at (datetime): The creation date of the product record.
  * updated_at (datetime): The last update date of the product record.
  * deleted_at (datetime, nullable): The deletion date of the product record, if applicable.

#### Example Request
```
GET /api/products
Authorization: Bearer your_access_token_here
```

#### Example Response
```
[
    {
        "id": 1,
        "name": "Product Name",
        "description": "Product Description",
        "price": 99.99,
        "stock": 100,
        "on_order": 0,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z",
        "deleted_at": null
    }
]
```

### Authentication
The API uses Laravel Sanctum to authenticate users.
When a user registers and logs in, they receive a token which is required to access the product end point.
The token is stored in the database and is deleted when a user logs out.

### Testing
The API has been tested using Postman.
The tests include registering a user, logging in, accessing the product end point and logging out.
The tests have been successful and the API is working as expected.

### Unit Testing
The API has been unit tested using [PEST PHP](https://pestphp.com/docs/why-pest).
The tests include registering a user, logging in, accessing the product end point and logging out.
I also tested for data integrity and data validation.
The tests have been successful and the API is working as expected.

### Future Improvements
- Implementing a front end to allow users to interact with the API.
- Adding more features to the API such as updating user details, updating product details and adding new products.
- Implementing a feature to allow users to reset their password.
- Implementing a feature to allow users to delete their account.
- Implementing a feature to allow users to view more than 5 products at a time.
- Implementing a feature to allow users to search for products by name or description.
- Implementing a feature to allow users to sort products by price or stock details.
- Implementing a feature to allow users to filter products by price or stock details.
