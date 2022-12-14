# PHP Shopping Cart
- a cart can add and remove items
- the amount of an item can be modified
- can assign and remove discount to an item in a cart
- a discount can be created by percentage or by amount

## Use Docker to Install Dependencies and Run Tests
0. You have installed Docker
1. Install dependencies
```
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp \
  composer install
```
2. Run tests. We can test Ihe code in multiple versions of PHP.
```
docker run -it --rm -v $PWD:/source -w /source php:7.3-alpine vendor/bin/phpunit tests
docker run -it --rm -v $PWD:/source -w /source php:7.4-alpine vendor/bin/phpunit tests
docker run -it --rm -v $PWD:/source -w /source php:8.0-alpine vendor/bin/phpunit tests
```
