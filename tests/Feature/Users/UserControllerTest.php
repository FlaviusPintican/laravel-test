<?php declare(strict_types=1);

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessTokenResult;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function itReturnsEmptyUsersList(): void
    {
        $response = $this->get('/api/users');
        static::assertEmpty($response->json());
        $response->assertOk();
    }

    /**
     * @test
     *
     * @return void
     */
    public function itReturnsUsersList(): void
    {
        $user = factory(User::class)->create(['username' => 'test']);
        $response = $this->get('/api/users');
        $response->assertOk();
        static::assertSame($user->id, $response->json('0.id'));
        static::assertSame($user->email, $response->json('0.email'));
        $response = $this->get('/api/users?searchValue=es');
        static::assertSame($user->id, $response->json('0.id'));
        static::assertSame($user->email, $response->json('0.email'));
    }

    /**
     * @test
     * @param array $data
     * @dataProvider getLoginCredentials
     *
     * @return void
     */
    public function itReturnsLoginFailed(array $data): void
    {
        factory(User::class)->create([]);
        $response = $this->post(
            '/login',
            $data,
            [
                'Content-Type' => 'multipart/form-data',
                'Accept' => 'application/json'
            ]
        );
        static::assertSame('Invalid username or password', $response->json('message'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @param array $data
     * @dataProvider getLoginCredentials
     *
     * @return void
     */
    public function itReturnsTokenAfterLogin(array $data): void
    {
        Auth::shouldReceive('attempt')->once()->with($data)->andReturn(true);

        /** @var MockObject $user */
        $user = $this->createPartialMock(User::class, ['createToken']);
        $personalAccessTokenResult = $this->createPartialMock(PersonalAccessTokenResult::class, []);
        $personalAccessTokenResult->accessToken = $token = 'xxxx';
        $user->method('createToken')->with(User::class)->willReturn($personalAccessTokenResult);
        Auth::shouldReceive('user')->once()->andReturn($user);

        $response = $this->post(
            '/login',
            $data,
            [
                'Content-Type' => 'multipart/form-data',
                'Accept' => 'application/json'
            ]
        );

        static::assertSame($token, $response->json('token'));
        $response->assertOk();
    }

    /**
     * @return array
     */
    public function getLoginCredentials(): array
    {
        return [
            'user login' => [
                ['email' => 'test@test.com', 'password' => 'test']
            ]
        ];
    }

    /**
     * @test
     *
     * @return void
     */
    public function itFailedToCreateNewUser(): void
    {
        Passport::actingAs(factory(User::class)->create());
        $response = $this->post(
            '/api/users',
            [],
            [
                'Content-Type' => 'multipart/form-data',
                'Accept' => 'application/json'
            ]
        );
        static::assertSame('The given data was invalid.', $response->json('message'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCreatesANewUser(): void
    {
        Passport::actingAs(factory(User::class)->create());
        $response = $this->post(
            '/api/users',
            [
                'username' => 'test',
                'password' => 'test',
                'first_name' => 'test',
                'family_name' => 'test',
                'email' => $email = 'test@test/com',
                'phone_number' => '+407222444555',
            ],
            [
                'Content-Type' => 'multipart/form-data',
                'Accept' => 'application/json'
            ]
        );
        static::assertSame($email, $response->json('email'));
        $response->assertCreated();
    }
}
