# Installation & Setup [![Build Status](https://travis-ci.org/2brosdevelopment/repository-pattern.svg?branch=master)](https://travis-ci.org/2brosdevelopment/repository-pattern)

## Composer
You can install this package via <a href="http://getcomposer.org">Composer</a> by running the command: `composer require "two_bros/repository-pattern:~1.0"`.

Or by adding the package to your `composer.json`
```json
    {
        "require": {
            "two_bros/repository-pattern": "~1.0"
        }
    }
```
Then install it via `composer install` or `composer update`

# Basic Usage

The purpose of the repository pattern is to separate ORM choice from the application, so that you can switch out your 
data layer without the need for touching the majority of your application. This is a solid first step towards that goal, 
although there are still some things that need to be done to make this completely separated from the ORM. One of the 
things that need to be done is to not return any Eloquent specific objects from the repository.

You will need to have each of your repositories extend `TwoBros\RepositoryPattern\Repositories\LaravelAbstractRepository`.

This repository will provide the methods below to support your basic needs for most normal data needs. This means 
that each model should have a repository paired with it, so that you can keep the layer between your application and 
the actual data. As your data needs within a specific domain evolve, you would add that functionality to the domain
specific repository that you created. Each repository should have its own interface created as well, to ensure that 
any domain specific functionality is built under contract. This will make sure that if you need to change ORMs, then 
you will know exactly what functionality you will need to replicate.

### create( array $attributes )
    Will create a model from an array of attributes, and return the created model.
    
### update( integer $id, array $attributes )
    Will update a model using an id to locate and with an array of attributes, and return the updated model. 

### destroy( integer $id )
    Will destroy a model using an id to locate the model, and will return a 1 for the count of successfully deleted 
    models.
    
### all( array $columns = ['*'] )
    Will return an Eloquent collection object with the columns requested, or all columns by default.
    
### getById( integer $id, array $columns = ['*'], array $with = [] )
    Will return a model with the columns requested and adding any relations requested. All columns are returned by default,
    and no relations.
    
### getByPage( integer $page = 1, integer $limit = 10, array $with = [] )
    Will return a StdClass object that can be used in a Laravel Pagination object. It takes the page number, per page 
    limit, and an array of relations that you want to include. The page defaults to 1 with 10 items per page, and no 
    added relations. The return object will have an items array that includes an Eloquent collection object, and a 
    totalItems property that will contain the count of total items in the database.
    
### getFirstBy( string $key, string $value, array $with = [] )
    Will return the first model from the database that contains the value for the selected column, and including any 
    relations you decide to load. 

### getManyBy( string $key, string $value, array $with = [] )
    Will return an Eloquent collection object from the database that contains the value for the selected column, and including any 
    relations you decide to load. 

### has( string $relation, array $with = [] )
    Will return an Eloquent collection object that has a specific relationship, and loading the array of relations provided.

### make( array $with = [] )
    Will return a model object with the array of relations loaded. This is more of an intermediary to an actual data return, 
    and is used by any of the other methods that include loaded relations.

# License

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.