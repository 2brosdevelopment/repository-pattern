<?php

    namespace TwoBros\RepositoryPattern\Tests;

    use Illuminate\Auth\Authenticatable;
    use Illuminate\Auth\Passwords\CanResetPassword;
    use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
    use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
    use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Foundation\Auth\Access\Authorizable;

    class User extends Model implements AuthenticatableContract,
        AuthorizableContract,
        CanResetPasswordContract
    {

        use Authenticatable, Authorizable, CanResetPassword;

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
