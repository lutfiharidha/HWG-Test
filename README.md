# HWG-Book

## Features
#### 1. Auth
#### 2. Category management
#### 3. Book management
#### 4. Borrowing management


## Documentation

The API documentation for this project is available on [Postman](https://documenter.getpostman.com/view/8160577/2sA2r81iWH).

## Installation & Usage
```
# Clone the repository
git clone https://github.com/lutfiharidha/hwg-test.git

# Install dependencies
composer install

# Set up environment variables
cp .env.example .env
set DB value on .env file

# Set up laravel application
php artisan key:generate
php artisan jwt:secret

# Set up database
php artisan migrate

# Start the development server
php artisan serve
```

## Pretest in golang

```
package main

import "fmt"

func main() {
    for i := 1; i <= 100; i++ {
        if i%3 == 0 && i%5 == 0 {
            fmt.Println("TigaLima")
        } else if i%3 == 0 {
            fmt.Println("Tiga")
        } else if i%5 == 0 {
            fmt.Println("Lima")
        } else {
            fmt.Println(i)
        }
    }
}

```
