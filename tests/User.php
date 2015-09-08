<?php

    namespace TwoBros\RepositoryPattern\Tests;

    use Illuminate\Database\Eloquent\Model;

    class User extends Model
    {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'users';

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $guarded = [ ];

        /**
         * The attributes excluded from the model's JSON form.
         *
         * @var array
         */
        protected $hidden = [ 'password', 'remember_token' ];

        public function role()
        {

            return $this->belongsTo( 'TwoBros\RepositoryPattern\Tests\Role', 'role_id' );
        }
    }
