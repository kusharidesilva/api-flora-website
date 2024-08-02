<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        // Mock the database connection
        $this->conn = $this->createMock(mysqli::class);
        // Initialize $_SESSION
        $_SESSION = [];
    }

    public function testLoginAdmin()
    {
        // Simulate POST data for admin login
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = 'adminpass';
        $_POST['submit'] = true;

        // Mock query result for admin login
        $result = $this->createMock(mysqli_result::class);
        $result->expects($this->any())->method('num_rows')->willReturn(1);
        $result->expects($this->any())->method('fetch_assoc')->willReturn([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'user_type' => 'admin'
        ]);

        // Mock mysqli_query function to return the mocked result
        $this->conn->expects($this->any())->method('query')->willReturn($result);

        // Mock mysqli_real_escape_string function
        $this->conn->expects($this->any())->method('real_escape_string')->willReturnCallback(function ($string) {
            return addslashes($string);
        });

        // Include the login script
        ob_start();
        include 'C:\xampp\htdocs\api-flora\path\to\your\login_script.php';
        ob_end_clean();

        // Assert session variables for admin login
        $this->assertEquals('Admin', $_SESSION['admin_name']);
        $this->assertEquals('admin@example.com', $_SESSION['admin_email']);
        $this->assertEquals(1, $_SESSION['admin_id']);
    }

    public function testLoginUser()
    {
        // Simulate POST data for user login
        $_POST['email'] = 'user@example.com';
        $_POST['pass'] = 'userpass';
        $_POST['submit'] = true;

        // Mock query result for user login
        $result = $this->createMock(mysqli_result::class);
        $result->expects($this->any())->method('num_rows')->willReturn(1);
        $result->expects($this->any())->method('fetch_assoc')->willReturn([
            'id' => 2,
            'name' => 'User',
            'email' => 'user@example.com',
            'user_type' => 'user'
        ]);

        // Mock mysqli_query function to return the mocked result
        $this->conn->expects($this->any())->method('query')->willReturn($result);

        // Mock mysqli_real_escape_string function
        $this->conn->expects($this->any())->method('real_escape_string')->willReturnCallback(function ($string) {
            return addslashes($string);
        });

        // Include the login script
        ob_start();
        include 'C:\xampp\htdocs\api-flora\path\to\your\login_script.php';
        ob_end_clean();

        // Assert session variables for user login
        $this->assertEquals('User', $_SESSION['user_name']);
        $this->assertEquals('user@example.com', $_SESSION['user_email']);
        $this->assertEquals(2, $_SESSION['user_id']);
    }

    public function testLoginFail()
    {
        // Simulate POST data for failed login
        $_POST['email'] = 'unknown@example.com';
        $_POST['pass'] = 'wrongpass';
        $_POST['submit'] = true;

        // Mock query result for failed login
        $result = $this->createMock(mysqli_result::class);
        $result->expects($this->any())->method('num_rows')->willReturn(0);

        // Mock mysqli_query function to return the mocked result
        $this->conn->expects($this->any())->method('query')->willReturn($result);

        // Mock mysqli_real_escape_string function
        $this->conn->expects($this->any())->method('real_escape_string')->willReturnCallback(function ($string) {
            return addslashes($string);
        });

        // Include the login script
        ob_start();
        include 'C:\xampp\htdocs\api-flora\path\to\your\login_script.php';
        ob_end_clean();

        // Assert error message
        $this->assertContains('incorrect email or password!', $message);
    }
}
