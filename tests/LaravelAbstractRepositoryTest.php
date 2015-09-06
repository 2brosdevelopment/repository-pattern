<?php

    namespace TwoBros\RepositoryPattern\Tests;

    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\App;
    use Mockery;
    use Orchestra\Testbench\TestCase;

    class LaravelAbstractRepositoryTest extends TestCase
    {

        /**
         * userMock
         *
         * @var \TwoBros\RepositoryPattern\Tests\User
         */
        protected $userMock;
        /**
         * roleMock
         *
         * @var \TwoBros\RepositoryPattern\Tests\Role
         */
        protected $roleMock;
        /**
         * userRepository
         *
         * @var UserRepository
         */
        protected $userRepository;

        public function setUp()
        {

            parent::setUp();

            $this->userMock = Mockery::mock( 'TwoBros\RepositoryPattern\Tests\User' );
            $this->app->instance( 'TwoBros\RepositoryPattern\Tests\User', $this->userMock );

            $this->roleMock = Mockery::mock( 'TwoBros\RepositoryPattern\Tests\Role' );
            $this->app->instance( 'TwoBros\RepositoryPattern\Tests\Role', $this->roleMock );

            $this->userRepository = new UserRepository( $this->userMock );
        }

        public function tearDown()
        {

            unset( $this->userMock );
            unset( $this->roleMock );
            unset( $this->userRepository );
            Mockery::close();
        }

        /**
         * testGetById
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testGetById()
        {

            $this->userMock->shouldReceive( 'with' )
                           ->once()
                           ->andReturnSelf();
            $this->userMock->shouldReceive( 'find' )
                           ->once()
                           ->andReturnSelf();
            $result = $this->userRepository->getById( 1, [ '*' ], [ 'role' ] );
            $this->assertInstanceOf( 'TwoBros\RepositoryPattern\Tests\User', $result );
        }

        /**
         * testCreate
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testCreate()
        {

            $this->userMock->shouldReceive( 'create' )
                           ->once()
                           ->andReturnSelf();
            $result = $this->userRepository->create( [ 'name' => 'Vincent' ] );
            $this->assertInstanceOf( 'TwoBros\RepositoryPattern\Tests\User', $result );
        }

    }
