<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Fine class
 */
class FineTest extends TestCase
{
    private $mockConnection;
    private $mockStatement;
    private $fine;

    protected function setUp(): void
    {
        // Create mock PDO connection
        $this->mockConnection = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);
        
        // Mock the prepare method
        $this->mockConnection->method('prepare')
            ->willReturn($this->mockStatement);
        
        // Create Fine instance
        require_once __DIR__ . '/../classes/Fine.php';
        $this->fine = new Fine($this->mockConnection);
    }

    /**
     * Test fine creation with valid data
     */
    public function testCreateFineWithValidData()
    {
        // Set fine properties
        $this->fine->tourist_id = 1;
        $this->fine->booking_id = 1;
        $this->fine->booking_reference = "GG123456";
        $this->fine->amount = 25.00;
        $this->fine->original_price = 100.00;

        // Mock successful execution
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute create
        $result = $this->fine->create();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test getting fines by tourist ID
     */
    public function testGetFinesByTourist()
    {
        $touristId = 1;
        
        // Mock fetchAll to return fines
        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                [
                    'id' => 1,
                    'tourist_id' => 1,
                    'booking_id' => 1,
                    'amount' => 25.00,
                    'status' => 'pending'
                ]
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getFinesByTourist
        $result = $this->fine->getFinesByTourist($touristId);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals(25.00, $result[0]['amount']);
    }

    /**
     * Test getting fine by ID
     */
    public function testGetFineById()
    {
        $fineId = 1;
        
        // Mock rowCount and fetch
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id' => 1,
                'tourist_id' => 1,
                'booking_id' => 1,
                'amount' => 25.00,
                'status' => 'pending'
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getFineById
        $result = $this->fine->getFineById($fineId);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals(25.00, $result['amount']);
    }

    /**
     * Test marking fine as paid
     */
    public function testMarkFineAsPaid()
    {
        $fineId = 1;
        $paymentMethod = "credit_card";
        $paymentReference = "PAY123456";
        
        // Mock successful execution
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute markAsPaid
        $result = $this->fine->markAsPaid($fineId, $paymentMethod, $paymentReference);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test checking if fine exists for booking
     */
    public function testFineExistsForBooking()
    {
        $bookingId = 1;
        
        // Mock rowCount
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute fineExistsForBooking
        $result = $this->fine->fineExistsForBooking($bookingId);

        // Assert
        $this->assertTrue($result);
    }
}

