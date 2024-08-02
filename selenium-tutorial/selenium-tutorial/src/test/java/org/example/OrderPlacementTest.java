package org.example;

import org.junit.jupiter.api.AfterEach; 
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.edge.EdgeDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.time.Duration;

import static org.junit.jupiter.api.Assertions.assertTrue;

/**
 * @author 
 */
public class OrderPlacementTest {

    private WebDriver driver;
    private WebDriverWait wait;

    @BeforeEach
    public void setUp() {
        driver = new EdgeDriver();
        wait = new WebDriverWait(driver, Duration.ofSeconds(60));
        driver.manage().window().maximize();
    }

    @AfterEach
    public void tearDown() {
        if (driver != null) {
            driver.quit();
        }
    }

    @Test
    public void testPlaceOrder() {
        // Open the login page
        driver.get("http://localhost/api-flora/login.php");

        // Find and fill the email field
        WebElement emailField = driver.findElement(By.name("email"));
        emailField.sendKeys("para@gmail.com");

        // Find and fill the password field
        WebElement passwordField = driver.findElement(By.name("pass"));
        passwordField.sendKeys("123");

        // Find and click the login button
        WebElement loginButton = driver.findElement(By.name("submit"));
        loginButton.click();

        // Wait until the landing page is loaded
        wait.until(ExpectedConditions.urlToBe("http://localhost/api-flora/home.php"));

        // Open the checkout page
        driver.get("http://localhost/api-flora/checkout.php");

        // Fill in the order details
        driver.findElement(By.name("name")).sendKeys("parami");
        driver.findElement(By.name("number")).sendKeys("1234567890");
        driver.findElement(By.name("address1")).sendKeys("colombo");
        driver.findElement(By.name("city")).sendKeys("colombo");
        driver.findElement(By.name("country")).sendKeys("Sri Lanka");
        driver.findElement(By.name("pin_code")).sendKeys("12345");
        driver.findElement(By.name("email")).sendKeys("para2@gmail.com");
        WebElement methodDropdown = driver.findElement(By.name("method"));
        methodDropdown.click();
        WebElement codOption = driver.findElement(By.xpath("//option[@data-display='Cash on delivery']"));
        codOption.click();

        // Place the order
        WebElement placeOrderButton = driver.findElement(By.name("order"));
        placeOrderButton.click();

        // Wait until the order confirmation page or message appears
        wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("//p[contains(text(),'order placed successfully!')]")));

        // Verify the order placement success message
        WebElement successMessage = driver.findElement(By.xpath("//p[contains(text(),'order placed successfully!')]"));
        assertTrue(successMessage.isDisplayed(), "Order placement failed or success message not found.");
        
    }
}
