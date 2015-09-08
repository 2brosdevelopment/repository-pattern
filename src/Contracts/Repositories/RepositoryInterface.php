<?php

    namespace TwoBros\RepositoryPattern\Contracts\Repositories;

    /**
     * Interface RepositoryInterface
     *
     * This interface defines the behavior expected of ANY repository
     *
     * @package TwoBros\RepositoryPattern\Contracts
     *
     * @author  John Sposato, Jr.
     * @version 1.0
     */
    interface RepositoryInterface
    {

        public function create( array $attributes );

        public function update( $id, array $attributes );

        public function all( $columns = '*' );

        public function getById( $id, array $columns = [ '*' ], array $with = [ ] );

        public function destroy( $ids );

        public function make( array $with = [ ] );

        public function getFirstBy( $key, $value, array $with = [ ] );

        public function getManyBy( $key, $value, array $with = [ ] );

        public function getByPage( $page = 1, $limit = 10, $with = [ ] );

        public function has( $relation, array $with = [ ] );
    }
