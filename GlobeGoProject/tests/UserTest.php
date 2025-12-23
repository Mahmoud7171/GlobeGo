<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for User class
 */
class UserTest extends TestCase
{
    private $mockConnection;
    private $mockStatement;
    private $user;

    protected function setUp(): void
    {
        // Create mock PDO connection
        $this->mockConnection = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);
        
        // Mock the prepare method
        $this->mockConnection->method('prepare')
            ->willReturn($this->mockStatement);
        
        // Create User instance
        require_once __DIR__ . '/../classes/User.php';
        $this->user = new User($this->mockConnection);
    }

    /**
     * Test user registration with valid data
     */
    public function testRegisterUserWithValidData()
    {
        // Set user properties
        $this->user->email = "test@example.com";
        $this->user->password = "password123";
        $this->user->first_name = "John";
        $this->user->last_name = "Doe";
        $this->user->role = "tourist";
        $this->user->phone = "1234567890";

        // Mock successful execution
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute register
        $result = $this->user->register();

        // Assert
        $this->assertTrue($result);
        // Password should be hashed
        $this->assertNotEquals("password123", $this->user->password);
    }

    /**
     * Test user login with correct credentials
     */
    public function testLoginWithCorrectCredentials()
    {
        $email = "test@example.com";
        $password = "password123";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Mock rowCount and fetch
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id' => 1,
                'email' => $email,
                'password' => $hashedPassword,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'role' => 'tourist',
                'verified' => 1,
                'status' => 'active',
                'suspend_until' => null
            ]);

        // Login calls autoUnsuspendExpired() first, then the login query
        // So we need to allow multiple execute calls
        $this->mockStatement->expects($this->atLeastOnce())
            ->method('execute')
            ->willReturn(true);

        // Execute login
        $result = $this->user->login($email, $password);

        // Assert
        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
    }

    /**
     * Test user login with incorrect password
     */
    public function testLoginWithIncorrectPassword()
    {
        $email = "test@example.com";
        $password = "wrongpassword";
        $hashedPassword = password_hash("correctpassword", PASSWORD_DEFAULT);
        
        // Mock rowCount and fetch
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id' => 1,
                'email' => $email,
                'password' => $hashedPassword,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'role' => 'tourist',
                'verified' => 1,
                'status' => 'active',
                'suspend_until' => null
            ]);

        // Login calls autoUnsuspendExpired() first, then the login query
        // So we need to allow multiple execute calls
        $this->mockStatement->expects($this->atLeastOnce())
            ->method('execute')
            ->willReturn(true);

        // Execute login
        $result = $this->user->login($email, $password);

        // Assert
        $this->assertIsArray($result);
        $this->assertFalse($result['success']);
    }

    /**
     * Test checking if email exists
     */
    public function testEmailExists()
    {
        $email = "test@example.com";
        
        // Mock rowCount
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute emailExists
        $result = $this->user->emailExists($email);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test getting user by ID
     */
    public function testGetUserById()
    {
        $userId = 1;
        
        // Mock rowCount and fetch
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id' => 1,
                'email' => 'test@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'role' => 'tourist',
                'phone' => '1234567890',
                'profile_image' => null,
                'bio' => null,
                'languages' => null,
                'verified' => 1,
                'status' => 'active'
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getUserById
        $result = $this->user->getUserById($userId);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(1, $this->user->id);
        $this->assertEquals('test@example.com', $this->user->email);
    }
}

