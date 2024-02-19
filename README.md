# youapply

Authentication API

1. Register

URL: /api/register
Method: POST
Headers:
Content-Type: application/json
Body:

    {
      "name": "John Doe",
      "phone": "0781234567",
      "password": "password123"
    }

Success Response:
Code: 201 Created
Content:

    {
      "message": "User registered successfully! Please verify your phone number.",
      "phone": "0781234567",
      "sms_code": "1234" // Note: sms_code is for testing purposes; in production, the code would be sent to the user's phone.
    }

2. Login

URL: /api/login
Method: POST
Headers:
Content-Type: application/json
Body:

    {
      "phone": "0781234567",
      "password": "password123"
    }

Success Response:
Code: 200 OK
Content:

    {
      "access_token": "token",
      "token_type": "Bearer"
    }

3. Verify SMS Code

URL: /api/verify-sms
Method: POST
Headers:
Content-Type: application/json
Body:

    {
      "phone": "0781234567",
      "sms_code": "1234"
    }

Success Response:
Code: 200 OK
Content:

    {
      "message": "Phone number verified successfully.",
      "access_token": "token",
      "token_type": "Bearer"
    }


4. Logout

URL: /api/logout
Method: POST
Headers:
Authorization: Bearer {token}
Success Response:
Code: 200 OK
Content:

    {
      "message": "Logged out"
    }


<!-- Product API -->

1. List Products

URL: /api/products
Method: GET
Headers:
Authorization: Bearer {token}
Success Response:
Code: 200 OK
Content: 

    [
        {
            "id": 1,
            "name": "Product Name",
            "slug": "product-name", 
            "description": "Product Description", 
            "price": 100.0
        }
    ]

2. Create Product

URL: /api/products
Method: POST
Headers:
Content-Type: application/json
Authorization: Bearer {token}
Body:

    {
      "name": "New Product",
      "slug": "new-product",
      "description": "Product Description",
      "price": 150.0
    }

Success Response:
Code: 201 Created
Content:

    {
       "id": 2, 
       "name": "New Product", 
       "slug": "new-product", 
       "description": "Product Description",
       "price": 150.0
    }


3. Update Product

URL: /api/products/{id}
Method: PUT
Headers:
Content-Type: application/json
Authorization: Bearer {token}
Body:

    {
      "name": "Updated Product",
      "slug": "updated-product",
      "description": "Updated Description",
      "price": 200.0
    }

Success Response:
Code: 200 OK
Content:

    {
        "id": 2, "name": "Updated Product", "slug": "updated-product", "description": "Updated Description", "price": 200.0
    }


4. Delete Product

URL: /api/products/{id}
Method: DELETE
Headers:
Authorization: Bearer {token}
Success Response:
Code: 200 OK

Content: 

    {
        "message": "Product deleted successfully"
    }
