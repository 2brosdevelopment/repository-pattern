<?php

    namespace TwoBros\RepositoryPattern\Tests;

    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use TwoBros\RepositoryPattern\Repositories\LaravelAbstractRepository;

    class UserRepository extends LaravelAbstractRepository
    {

        /**
         * @var string modelClassName
         */
        protected $model;

        public function __construct( User $model )
        {

            $this->model = $model;

            parent::__construct($model);
        }

    }