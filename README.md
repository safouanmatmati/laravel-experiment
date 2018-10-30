# Shop

### Goal
*Shop* is a mini project to test my ability to develop website from unknow technologies as PHP Laravel framework.

### Technologies
* [PHP 5.6](https://nodejs.org/en/)
* [Laravel 5.2](https://yarnpkg.com/en/) : PHP framework
* [Redis](https://reactstrap.github.io/) : memory data structure store used as a cache
* [NodeJS 8](https://nodejs.org/en/)
* [Bootstrap 3](https://getbootstrap.com/docs/3.3/) : front end development toolkit
* [Gulp module](https://flow.org/) : a node builder
* [Docker for Windows](https://docs.docker.com/docker-for-windows/install/) : isolate environment manager

### Expected
#### Product page integration
* Use "template.png" as base for front integration
* logo, basket, breadcrumb are statics
* Tab system for : Description / Delivery / Warranties
* A thumbnail preview slider

#### Product database
* A product has a name, a price, a brand, a description, one or many images
* A brand has a name
* An image has a file name

#### Back Office Creation Product Module

* Ability to add / modify / delete a product
* Link a brand to a product
* Link one or more images to a product

#### Notes
* Use cache
* Manage front/back authentication

## Installation

### Requirements
* GIT
* [Docker](https://www.docker.com/get-started)

### Step
#### Clone the repository

```
git clone https://github.com/safouanmatmati/laravel-experiment.git
cd shop/
```

#### Define an administrator

Open ".env" file and edit ADMIN_\* variables with user admin access you want.
This user will be created later.(cf *Generate contents*)

#### Build images
```
docker-compose build
```

#### Create volumes

```
docker volume create shop_db_data
docker volume create shop_cache_data
```

#### Run services
```
docker-compose up -d
```
#### Generate contents
```
docker-compose exec application composer run-script post-create-project-cmd
```
## Usage
Visits [http://localhost:8081](http://localhost:8081).
