<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Booking class
 */
class BookingTest extends TestCase
{
    private $mockConnection;
    private $mockStatement;
    private $booking;

    protected function setUp(): void
    {
        // Create mock PDO connection
        $this->mockConnection = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);
        
        // Mock the prepare method
        $this->mockConnection->method('prepare')
            ->willReturn($this->mockStatement);
        
        // Mock lastInsertId
        $this->mockConnection->method('lastInsertId')
            ->willReturn(1);
        
        // Create Booking instance
        require_once __DIR__ . '/../classes/Booking.php';
        $this->booking = new Booking($this->mockConnection);
    }

    /**
     * Test booking creation with valid data
     */
    public function testCreateBookingWithValidData()
    {
        // Set booking properties
        $this->booking->tourist_id = 1;
        $this->booking->tour_schedule_id = 1;
        $this->booking->num_participants = 2;
        $this->booking->total_price = 100.00;
        $this->booking->booking_notes = "Test booking";

        // Mock successful execution
        $this->mockStatement->expects($this->atLeastOnce())
            ->method('execute')
            ->willReturn(true);

        // Mock fetch for guide query
        $this->mockStatement->expects($this->any())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(['guide_id' => 2]);

        // Execute create
        $result = $this->booking->create();

        // Assert
        $this->assertTrue($result);
        $this->assertNotEmpty($this->booking->booking_reference);
        $this->assertStringStartsWith('GG', $this->booking->booking_reference);
    }

    /**
     * Test getting booking by ID
     */
    public function testGetBookingById()
    {
        $bookingId = 1;
        
        // Mock rowCount and fetch
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id' => 1,
                'booking_reference' => 'GG123456',
                'tourist_id' => 1,
                'total_price' => 100.00
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getBookingById
        $result = $this->booking->getBookingById($bookingId);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('GG123456', $result['booking_reference']);
    }

    /**
     * Test checking availability
     */
    public function testCheckAvailabilityWithEnoughSpots()
    {
        $scheduleId = 1;
        $numParticipants = 2;
        
        // Mock rowCount and fetch
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(['available_spots' => 10]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute checkAvailability
        $result = $this->booking->checkAvailability($scheduleId, $numParticipants);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test checking availability with insufficient spots
     */
    public function testCheckAvailabilityWithInsufficientSpots()
    {
        $scheduleId = 1;
        $numParticipants = 5;
        
        // Mock rowCount and fetch
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(['available_spots' => 2]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute checkAvailability
        $result = $this->booking->checkAvailability($scheduleId, $numParticipants);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test updating booking status
     */
    public function testUpdateBookingStatus()
    {
        $bookingId = 1;
        $status = 'confirmed';
        
        // Mock successful execution - updateBookingStatus may call getBookingById which executes queries
        $this->mockStatement->expects($this->atLeastOnce())
            ->method('execute')
            ->willReturn(true);

        // Mock getBookingById for notification (called when status is 'confirmed')
        $this->mockStatement->expects($this->any())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->any())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id' => 1,
                'booking_reference' => 'GG123456',
                'tourist_id' => 1,
                'guide_id' => 2
            ]);

        // Execute updateBookingStatus
        $result = $this->booking->updateBookingStatus($bookingId, $status);

        // Assert
        $this->assertTrue($result);
    }
}

