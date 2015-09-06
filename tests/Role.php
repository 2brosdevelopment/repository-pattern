<?php

    namespace TwoBros\RepositoryPattern\Tests;

    use Illuminate\Database\Eloquent\Model;

    class Role extends Model
    {

        public function user()
        {

            return $this->hasOne( 'User', 'role_id' );
        }
    }
