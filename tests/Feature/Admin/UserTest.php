<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @return void
     */
    public function testUnsuccessfulSimpleGuestVisit(): void
    {
        // users index page - must be 302 redirect
        $this->get(route('admin.users.index'))->assertStatus(Response::HTTP_FOUND);

        // users creation page - must be 302 redirect
        $this->get(route('admin.users.create'))->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @return void
     */
    public function testSuccessfulAuthorizedUserVisit(): void
    {
        $this->actingAsAuthorizedUser();

        // users index page - must be 200 status
        $this->get(route('admin.users.index'))->assertStatus(Response::HTTP_OK);

        // users creation page - must be 200 status
        $this->get(route('admin.users.create'))->assertStatus(Response::HTTP_OK);
    }

    /**
     * @dataProvider getUserData
     * @param array $data
     * @return void
     */
    public function testSuccessfulUserCreation(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // send user creation request
        $userCreationResponse = $this->post(route('admin.users.store'), $data['valid-creation']);

        // session must be without errors
        $userCreationResponse->assertSessionDoesntHaveErrors();

        // user should be redirected after user creation
        $userCreationResponse->assertStatus(Response::HTTP_FOUND);

        // validate created (latest) user values
        $user = User::all()->last();
        $this->assertEquals($data['valid-creation']['name'], $user->name);
        $this->assertEquals($data['valid-creation']['email'], $user->email);
        $this->assertTrue(Hash::check($data['valid-creation']['password'], $user->password));
    }

    /**
     * @dataProvider getUserData
     * @param array $data
     * @return void
     */
    public function testSuccessfulUserUpdate(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // at first create user for future update
        $this->post(route('admin.users.store'), $data['valid-creation']);

        // get created user for future usage
        $createdUser = User::all()->last();

        // visit edit page and check if authenticated user can access it
        $this->get(route('admin.users.edit', $createdUser))->assertStatus(Response::HTTP_OK);

        // send user update request
        $userUpdateResponse = $this->patch(route('admin.users.update', $createdUser), $data['valid-update']);

        // session must be without errors
        $userUpdateResponse->assertSessionDoesntHaveErrors();

        // user should be redirected after user update
        $userUpdateResponse->assertStatus(Response::HTTP_FOUND);

        // get updated user with the same ID as created user
        $updatedUser = User::find($createdUser->id);

        // validate updated user values
        $this->assertEquals($data['valid-update']['name'], $updatedUser->name);
        $this->assertEquals($data['valid-update']['email'], $updatedUser->email);

        // if password is empty - it should have old value, not empty one
        if ($data['valid-update']['password'] !== '') {
            $this->assertTrue(Hash::check($data['valid-update']['password'], $updatedUser->password));
        } else {
            $this->assertFalse(Hash::check($data['valid-update']['password'], $updatedUser->password));
        }
    }

    /**
     * @dataProvider getUserData
     * @param array $data
     * @return void
     */
    public function testSuccessfulUserDelete(array $data): void
    {
        $this->actingAsAuthorizedUser();

        $usersQuantityBeforeDelete = User::all()->count();

        // at first create user for future update
        $this->post(route('admin.users.store'), $data['valid-creation']);

        // get created user for future usage
        $user = User::all()->last();

        // send user delete request
        $userDeleteResponse = $this->delete(route('admin.users.destroy', $user));

        // session must be without errors
        $userDeleteResponse->assertSessionDoesntHaveErrors();

        // user should be redirected after user removing
        $userDeleteResponse->assertStatus(Response::HTTP_FOUND);

        // validate if user quantity is the same as before
        $this->assertEquals($usersQuantityBeforeDelete, User::all()->count());
    }

    /**
     * @dataProvider getUserData
     * @param array $data
     * @return void
     */
    public function testUnsuccessfulUserCreation(array $data): void
    {
        $this->actingAsAuthorizedUser();

        $usersQuantityBeforeCreation = User::all()->count();

        // send invalid user creation request
        $userCreationResponse = $this->post(route('admin.users.store'), $data['invalid-creation']);

        // this field is not independent and related to password
        unset($data['invalid-creation']['password_confirmation']);

        // session has errors
        $userCreationResponse->assertSessionHasErrors(array_keys($data['invalid-creation']));

        // user should be redirected after unsuccessful creation
        $userCreationResponse->assertStatus(Response::HTTP_FOUND);

        // validate if user quantity is the same as before
        $this->assertEquals($usersQuantityBeforeCreation, User::all()->count());
    }

    /**
     * @dataProvider getUserData
     * @param array $data
     * @return void
     */
    public function testUnsuccessfulUserUpdate(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // create user
        $this->post(route('admin.users.store'), $data['valid-creation']);

        // get created user for future usage
        $createdUser = User::all()->last();

        // send invalid user creation request
        $userUpdateResponse = $this->patch(route('admin.users.update', $createdUser), $data['invalid-update']);

        // this field is not independent and related to password
        unset($data['invalid-update']['password_confirmation']);

        // session has errors
        $userUpdateResponse->assertSessionHasErrors(array_keys($data['invalid-update']));

        // user should be redirected after unsuccessful update
        $userUpdateResponse->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulUserDelete(): void
    {
        $this->actingAsAuthorizedUser();

        // send user delete request
        $userDeleteResponse = $this->delete(route('admin.users.destroy', ['user' => PHP_INT_MAX]));

        // there is no requested user (404 error)
        $userDeleteResponse->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulYourselfDelete(): void
    {
        $user = $this->actingAsAuthorizedUser();

        $usersQuantityBeforeCreation = User::all()->count();

        // send user delete request
        $this->delete(route('admin.users.destroy', $user));

        // validate if user quantity is the same as before
        $this->assertEquals($usersQuantityBeforeCreation, User::all()->count());
    }

    /**
     * @dataProvider getUserData
     * @param array $data
     * @return void
     */
    public function testUnsuccessfulUserCreationWithTheSameEmail(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // send first valid user creation request
        $userCreationResponse = $this->post(route('admin.users.store'), $data['valid-creation']);

        // session must be without errors
        $userCreationResponse->assertSessionDoesntHaveErrors();

        // send second user creation request with the same data (and the same slug)
        $userInvalidCreationResponse = $this->post(route('admin.users.store'), $data['valid-creation']);

        // validate if there is the same slug session error
        $userInvalidCreationResponse->assertSessionHasErrors(['email']);
    }

    /**
     * @dataProvider getUserData
     * @param array $data
     * @return void
     */
    public function testSuccessfulUserUpdateWithTheSameEmail(array $data): void
    {
        $this->actingAsAuthorizedUser();

        // send first valid user creation request
        $userCreationResponse = $this->post(route('admin.users.store'), $data['valid-creation']);

        // session must be without errors
        $userCreationResponse->assertSessionDoesntHaveErrors();

        // get created user for future usage
        $createdUser = User::all()->last();

        // send user update request with the same data (and the same slug)
        $userUpdateResponse = $this->patch(route('admin.users.update', $createdUser), $data['valid-creation']);

        // validate if there is no errors in session
        $userUpdateResponse->assertSessionDoesntHaveErrors();
    }

    /**
     * @return void
     */
    public function testSuccessfulUserLogin(): void
    {
        // try to log in with default user credentials
        $loginResponse = $this->post(route('admin.login'), [
            'email'    => 'john.smith@gmail.com',
            'password' => '12345678'
        ]);

        // session must be without errors
        $loginResponse->assertSessionDoesntHaveErrors();

        // user should be redirected after successful login
        $loginResponse->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulUserLogin(): void
    {
        // try to log in with some random credentials
        $loginResponse = $this->post(route('admin.login'), [
            'email'    => 'john.smith.john@gmail.com',
            'password' => '87654321'
        ]);

        // session must be with errors
        $loginResponse->assertSessionHasErrors();

        // guest should be redirected after unsuccessful login
        $loginResponse->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @return array
     */
    public function getUserData(): array
    {
        return [
            [
                [
                    'valid-creation' => [
                        'name'                  => 'First User Name',
                        'email'                 => 'first@email.com',
                        'password'              => '12345678',
                        'password_confirmation' => '12345678'
                    ],
                    'valid-update' => [
                        'name'                  => 'Updated First User Name',
                        'email'                 => 'updated@email.com',
                        'password'              => '987654321',
                        'password_confirmation' => '987654321'
                    ],
                    'invalid-creation' => [
                        'name'                  => '',
                        'email'                 => 'email.com',
                        'password'              => '.',
                        'password_confirmation' => ''
                    ],
                    'invalid-update' => [
                        'name'                  => 'Very Long Name ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',
                        'email'                 => 'verylongemail@emaillllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll.com',
                        'password'              => ['12345678'],
                        'password_confirmation' => ['12345678']
                    ]
                ],
            ],
            [
                [
                    'valid-creation' => [
                        'name'                  => 'Second User Name',
                        'email'                 => 'second@email.com',
                        'password'              => 'password',
                        'password_confirmation' => 'password'
                    ],
                    'valid-update' => [
                        'name'                  => 'Updated Second User Name',
                        'email'                 => 'updatedsecond@email.com',
                        'password'              => '',
                        'password_confirmation' => ''
                    ],
                    'invalid-creation' => [
                        'name'                  => '',
                        'email'                 => '',
                        'password'              => '',
                        'password_confirmation' => ''
                    ],
                    'invalid-update' => [
                        'name'                  => ['Wrong Name'],
                        'email'                 => ['Wrong Email'],
                        'password'              => ['Wrong Password'],
                        'password_confirmation' => ['Wrong Password']
                    ]
                ]
            ],
        ];
    }
}
