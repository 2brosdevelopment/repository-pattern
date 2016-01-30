<?php

    namespace TwoBros\RepositoryPattern\Tests;

    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Hash;
    use Mockery;
    use Orchestra\Testbench\TestCase;

    class LaravelAbstractRepositoryTest extends TestCase
    {

        /**
         * userMock
         *
         * @var \TwoBros\RepositoryPattern\Tests\User
         */
        protected $userModel;
        /**
         * roleMock
         *
         * @var \TwoBros\RepositoryPattern\Tests\Role
         */
        protected $roleModel;
        /**
         * userRepository
         *
         * @var UserRepository
         */
        protected $userRepository;

        /**
         * Define environment setup.
         *
         * @param  \Illuminate\Foundation\Application $app
         *
         * @return void
         */
        protected function getEnvironmentSetUp( $app )
        {

            // Setup default database to use sqlite :memory:
            $app[ 'config' ]->set( 'database.default', 'repository-tests' );
            $app[ 'config' ]->set( 'database.connections.repository-tests', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ] );
        }

        public function setUp()
        {

            parent::setUp();

            $this->artisan( 'migrate', [
                '--database' => 'repository-tests',
                '--realpath' => realpath( __DIR__ . '/Migration' ),
            ] );
            $this->userModel      = new User();
            $this->roleModel      = new Role();
            $this->userRepository = new UserRepository( $this->userModel );

            $this->seedDatabase();
        }

        protected function seedDatabase()
        {

            $this->roleModel = Role::create( [
                'name' => 'New Role'
            ] );
            User::create( [
                'role_id'  => $this->roleModel->id,
                'name'     => 'Test User',
                'email'    => 'test@example.com',
                'password' => Hash::make( 'Password' )
            ] );
            User::create( [
                'role_id'  => $this->roleModel->id,
                'name'     => 'Test User 2',
                'email'    => 'test2@example.com',
                'password' => Hash::make( 'Password' )
            ] );
        }

        public function tearDown()
        {

            unset( $this->userModel );
            unset( $this->roleModel );
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

            $result = $this->userRepository->getById( 1, [ '*' ], [ 'role' ] );
            $this->assertInstanceOf( 'TwoBros\RepositoryPattern\Tests\User', $result,
                "getById should return a User instance, but didn't!" );
            $this->assertInstanceOf( 'TwoBros\RepositoryPattern\Tests\Role', $result->role,
                "getById should return an associated Role instance, but didn't!" );
        }

        /**
         * testCreate
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testCreate()
        {

            $result = $this->userRepository->create( [
                'name'     => 'Vincent',
                'role_id'  => $this->roleModel->id,
                'email'    => 'test3@example.com',
                'password' => Hash::make( 'Password' )
            ] );
            $this->assertInstanceOf( 'TwoBros\RepositoryPattern\Tests\User', $result,
                "create should return the created user object, but didn't!" );
        }

        /**
         * testAll
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testAll()
        {

            $result = $this->userRepository->all();
            $this->assertInstanceOf( 'Illuminate\Database\Eloquent\Collection', $result,
                "all should return a collection object, but it didn't!" );
            $this->assertInstanceOf( 'TwoBros\RepositoryPattern\Tests\User', $result->first(),
                "The first result of the collection result should be a user instance, but it wasn't!" );
        }

        /**
         * testDestroy
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testDestroy()
        {

            $newUser = User::create( [
                'name'     => 'Vincent Tester',
                'role_id'  => $this->roleModel->id,
                'email'    => 'test5@example.com',
                'password' => Hash::make( 'Password' )
            ] );

            $result = $this->userRepository->destroy( $newUser->id );
            $this->assertEquals( 1, $result,
                "destroy should return the count of 1, since I was deleting a single object - it didn't!" );

            $findResult = User::find( $newUser->id );
            $this->assertNull( $findResult,
                "Attempting to find the destroyed user, which shouldn't return anything - returned something!" );
        }

        public function testDestroyArrayOfIds()
        {

            $newUser  = User::create( [
                'name'     => 'Vincent Tester',
                'role_id'  => $this->roleModel->id,
                'email'    => 'test5@example.com',
                'password' => Hash::make( 'Password' )
            ] );
            $newUser2 = User::create( [
                'name'     => 'Vincent Tester 2',
                'role_id'  => $this->roleModel->id,
                'email'    => 'test77@example.com',
                'password' => Hash::make( 'Password' )
            ] );
            $idList   = [ $newUser->id, $newUser2->id ];

            $result = $this->userRepository->destroy( $idList );
            $this->assertEquals( 2, $result,
                "destroy should return the count of 2, since I was deleting a single object - it didn't!" );

            $findResult = User::find( $newUser->id );
            $this->assertNull( $findResult,
                "Attempting to find the destroyed user, which shouldn't return anything - returned something!" );

            $findResult2 = User::find( $newUser2->id );
            $this->assertNull( $findResult2,
                "Attempting to find the destroyed user, which shouldn't return anything - returned something!" );

        }

        /**
         * testUpdate
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testUpdate()
        {

            $newUser = User::create( [
                'name'     => 'Vincent Tester',
                'role_id'  => $this->roleModel->id,
                'email'    => 'test5@example.com',
                'password' => Hash::make( 'Password' )
            ] );

            $result = $this->userRepository->update( $newUser->id, [ 'name' => 'Sposato Tester' ] );
            $this->assertEquals( 1, $result, "update should return the count of 1, but it didn't!" );

            $findResult = User::find( $newUser->id );
            $this->assertEquals( 'Sposato Tester', $findResult->name,
                "Name should have returned as Sposato Tester, but it didn't!" );
        }

        /**
         * testMake
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testMake()
        {

            $withoutMakeResult = $this->userRepository->getById( 1 );
            $this->assertEquals( 0, count( $withoutMakeResult->relationsToArray() ),
                "getById should return no defined with models if not set, but it did!" );

            $withMakeResult = $this->userRepository->getById( 1, [ '*' ], [ 'Role' ] );
            $this->assertEquals( 1, count( $withMakeResult->relationsToArray() ),
                "getById should return exactly 1 defined with models if set, but it didn't!" );
        }

        /**
         * testGetFirstBy
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testGetFirstBy()
        {

            User::create( [
                'name'     => 'Sposato Tester Test',
                'role_id'  => $this->roleModel->id,
                'email'    => 'test6@example.com',
                'password' => Hash::make( 'Password' )
            ] );
            User::create( [
                'name'     => 'Sposato Tester Test',
                'role_id'  => $this->roleModel->id,
                'email'    => 'test7@example.com',
                'password' => Hash::make( 'Password' )
            ] );

            $result = $this->userRepository->getFirstBy( 'name', 'Sposato Tester Test' );

            $this->assertEquals( 'test6@example.com', $result->email,
                "getFirstBy should return the lower of the ids including email address, and it did not!" );
            $this->assertNotEquals( 'test7@example.com', $result->email,
                "getFirstBy should return the higher of the ids including email address, and it did not!" );

        }

        /**
         * testGetManyBy
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testGetManyBy()
        {

            User::firstOrCreate( [
                'name'     => 'Sposato Tester Test',
                'role_id'  => $this->roleModel->id,
                'email'    => 'test6@example.com',
                'password' => Hash::make( 'Password' )
            ] );
            User::firstOrCreate( [
                'name'     => 'Sposato Tester Test',
                'role_id'  => $this->roleModel->id,
                'email'    => 'test7@example.com',
                'password' => Hash::make( 'Password' )
            ] );

            $result = $this->userRepository->getManyBy( 'name', 'Sposato Tester Test' );

            $this->assertInstanceOf( 'Illuminate\Database\Eloquent\Collection', $result,
                "all should return a collection object, but it didn't!" );
            $this->assertInstanceOf( 'TwoBros\RepositoryPattern\Tests\User', $result->first(),
                "The first result of the collection result should be a user instance, but it wasn't!" );
        }

        /**
         * testGetByPage
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testGetByPage()
        {

            $result = $this->userRepository->getByPage( 1, 1 );
            $this->assertInstanceOf( 'StdClass', $result, "getByPage should return StdClass, but it didn't!" );
            $this->assertEquals( 1, count( $result->items ),
                "Based on parameters, there should be only 1 item in the list but there is not" );
            $this->assertInstanceOf( 'TwoBros\RepositoryPattern\Tests\User', $result->items[ 0 ],
                "getByPage should return an array of User objects, but it doesn't appear that it did!" );
            $firstEmail = $result->items[ 0 ]->email;
            $this->assertEquals( 'test@example.com', $firstEmail,
                "Email address for the first record should have been test@example.com, but it wasn't!" );

            $result = $this->userRepository->getByPage( 2, 1 );
            $this->assertInstanceOf( 'StdClass', $result, "getByPage should return StdClass, but it didn't!" );
            $this->assertEquals( 1, count( $result->items ),
                "Based on parameters, there should be only 1 item in the list but there is not" );
            $this->assertInstanceOf( 'TwoBros\RepositoryPattern\Tests\User', $result->items[ 0 ],
                "getByPage should return an array of User objects, but it doesn't appear that it did!" );
            $this->assertNotEquals( $firstEmail, $result->items[ 0 ]->email,
                "Email from first record should not be the same as the second record, but it was!" );
            $this->assertEquals( 'test2@example.com', $result->items[ 0 ]->email,
                "Email address for the second record should have been test1@example.com, but it wasn't!" );
        }

        /**
         * testHas
         *
         * @author  Vincent Sposato <vincent.sposato@gmail.com>
         * @version v1.0
         */
        public function testHas()
        {

            User::create( [
                'name'     => 'Sposato Tester Without Role',
                'email'    => 'test10@example.com',
                'password' => Hash::make( 'Password' )
            ] );

            $totalRecords    = $this->userRepository->all();
            $recordsWithRole = $this->userRepository->has( 'Role' );

            $this->assertNotEquals( $totalRecords->count(), $recordsWithRole->count(),
                "The count of all records in the database should not equal the count of records that contain a Role, but it did!" );

        }
    }
