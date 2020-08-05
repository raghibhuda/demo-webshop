## WebShop 

Webshop is a tiny implementation of cart and order placing by following MVC pattern in Laravel.

#### This project has only these models
- Product
- Order
- Cart

which are representing corresponding tables in database. 
I kept things simple as much as possible on this case . 
As the cart doesn't belongs to any user for now so I made a constant key to recognize a cart .  

#### Controllers
- WebShopController

As it doesn't have that much functionality so I made only one controller for that (No service or repository to seperate business logic and other databse stuff).
It has several methods to handle these functionality. 


#### Controllers
- index
- cart

There's only two templates to handle view . Index page show and all the products and cart page is responsible for placing orders.

I have used some jQuery and bootstrap to make things user friendly. I have did some ajax request too !  

##Installation 
- ```git clone git@github.com:raghibhuda/demo-webshop.git```

- ```composer install```

- ```php artisan key:generate```

After this step make a .env file and set your database credentials on that file 

- ```php artisan migrate```

To run all the migration 

- ```php artisan db:seed```

This seeder will ask you for the number of products you want to seed and generate dummy products for you 

- ```php artisan serve```

No you are good to go ! Open your browser and go 

```localhost:8000```
