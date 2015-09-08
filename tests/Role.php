<?php

    namespace TwoBros\RepositoryPattern\Tests;

    use Illuminate\Database\Eloquent\Model;

    class Role extends Model
    {

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $guarded = [ ];

        public function user()
        {

            return $this->hasOne( 'TwoBros\RepositoryPattern\Tests\User', 'role_id' );
        }
    }
