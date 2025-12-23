<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Tour class
 */
class TourTest extends TestCase
{
    private $mockConnection;
    private $mockStatement;
    private $tour;

    protected function setUp(): void
    {
        // Create mock PDO connection
        $this->mockConnection = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);
        
        // Mock the prepare method to return mock statement
        $this->mockConnection->method('prepare')
            ->willReturn($this->mockStatement);
        
        // Create Tour instance with mock connection
        require_once __DIR__ . '/../classes/Tour.php';
        $this->tour = new Tour($this->mockConnection);
    }

    /**
     * Test tour creation with valid data
     */
    public function testCreateTourWithValidData()
    {
        // Set tour properties
        $this->tour->guide_id = 1;
        $this->tour->attraction_id = 1;
        $this->tour->title = "Test Tour";
        $this->tour->description = "Test Description";
        $this->tour->price = 50.00;
        $this->tour->duration_hours = 2;
        $this->tour->max_participants = 10;
        $this->tour->meeting_point = "Test Location";
        $this->tour->category = "Historical Tour";
        $this->tour->image_url = "test.jpg";

        // Mock successful execution
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute create
        $result = $this->tour->create();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test getting tours with location filter
     */
    public function testGetToursWithLocationFilter()
    {
        $filters = ['location' => 'Egypt'];
        
        // Mock fetchAll to return sample tours
        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                [
                    'id' => 1,
                    'title' => 'Egypt Tour',
                    'location' => 'Egypt, Cairo'
                ]
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getTours
        $result = $this->tour->getTours($filters);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Egypt Tour', $result[0]['title']);
    }

    /**
     * Test getting featured tours
     */
    public function testGetFeaturedTours()
    {
        // Mock fetchAll to return featured tours
        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                ['id' => 1, 'title' => 'Tour 1'],
                ['id' => 2, 'title' => 'Tour 2']
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getFeaturedTours
        $result = $this->tour->getFeaturedTours();

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    /**
     * Test getting tour by ID
     */
    public function testGetTourById()
    {
        $tourId = 1;
        
        // Mock rowCount and fetch
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id' => 1,
                'title' => 'Test Tour',
                'price' => 50.00
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getTourById
        $result = $this->tour->getTourById($tourId);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Test Tour', $result['title']);
    }

    /**
     * Test getting tour by non-existent ID
     */
    public function testGetTourByIdNotFound()
    {
        $tourId = 999;
        
        // Mock rowCount to return 0 (not found)
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(0);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getTourById
        $result = $this->tour->getTourById($tourId);

        // Assert
        $this->assertFalse($result);
    }
}

