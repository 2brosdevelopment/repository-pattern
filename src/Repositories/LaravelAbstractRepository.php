<?php

    namespace TwoBros\RepositoryPattern\Repositories;

    use Illuminate\Database\Eloquent\Model;

    abstract class LaravelAbstractRepository
    {

        /**
         * @var  model
         */
        protected $model;

        /**
         * @param $model
         */
        public function __construct( $model )
        {

            $this->model = $model;
        }

        /**
         * create
         *
         * create a new record
         *
         * @param array $attributes
         *
         * @return mixed
         */
        public function create( array $attributes )
        {

            return $this->model->create( $attributes );
        }

        /**
         * all
         *
         * return all records
         *
         * @param array|string $columns
         *
         * @return mixed
         */
        public function all( $columns = [ '*' ] )
        {

            return $this->model->all( $columns );
        }

        /**
         * destroy
         *
         * delete a record
         *
         * @param $ids
         *
         * @return mixed
         */
        public function destroy( $ids )
        {

            return $this->model->destroy( [ $ids ] );
        }

        /**
         * edit
         *
         * edit a record
         *
         * @param       $id
         * @param array $attributes
         *
         * @return mixed
         */
        public function update( $id, array $attributes )
        {

            $model = $this->getById( $id );
            $model->fill( $attributes );

            return $model->save( $attributes );
        }

        /**
         * getById
         *
         * @param       $id
         * @param array $columns
         * @param array $with
         *
         * @return mixed
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function getById( $id, array $columns = [ '*' ], array $with = [ ] )
        {

            $query = $this->make( $with );

            return $query->find( $id, $columns );
        }

        /**
         * make
         *
         * @param array $with
         *
         * @return mixed
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function make( array $with = [ ] )
        {

            return $this->model->with( $with );
        }

        /**
         * Find a single entity by key value
         *
         * @param string $key
         * @param string $value
         * @param array  $with
         */
        public function getFirstBy( $key, $value, array $with = [ ] )
        {

            return $this->make( $with )
                        ->where( $key, '=', $value )
                        ->first();
        }

        /**
         * Find many entities by key value
         *
         * @param string $key
         * @param string $value
         * @param array  $with
         */
        public function getManyBy( $key, $value, array $with = [ ] )
        {

            return $this->make( $with )
                        ->where( $key, '=', $value )
                        ->get();
        }

        /**
         * Get Results by Page
         *
         * @param int   $page
         * @param int   $limit
         * @param array $with
         *
         * @return StdClass Object with $items and $totalItems for pagination
         */
        public function getByPage( $page = 1, $limit = 10, $with = [ ] )
        {

            $result             = new \StdClass;
            $result->page       = $page;
            $result->limit      = $limit;
            $result->totalItems = 0;
            $result->items      = [ ];

            $query = $this->make( $with );

            $model = $query->skip( $limit * ( $page - 1 ) )
                           ->take( $limit )
                           ->get();

            $result->totalItems = $this->model->count();
            $result->items      = $model->all();

            return $result;
        }

        /**
         * Return all results that have a required relationship
         *
         * @param string $relation
         */
        public function has( $relation, array $with = [ ] )
        {

            $entity = $this->make( $with );

            return $entity->has( $relation )
                          ->get();
        }
    }
