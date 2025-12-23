<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Attraction class
 */
class AttractionTest extends TestCase
{
    private $mockConnection;
    private $mockStatement;
    private $attraction;

    protected function setUp(): void
    {
        // Create mock PDO connection
        $this->mockConnection = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);
        
        // Mock the prepare method
        $this->mockConnection->method('prepare')
            ->willReturn($this->mockStatement);
        
        // Create Attraction instance
        require_once __DIR__ . '/../classes/Attraction.php';
        $this->attraction = new Attraction($this->mockConnection);
    }

    /**
     * Test attraction creation with valid data
     */
    public function testCreateAttractionWithValidData()
    {
        // Set attraction properties
        $this->attraction->name = "Pyramids of Giza";
        $this->attraction->description = "Ancient Egyptian pyramids";
        $this->attraction->location = "Giza, Egypt";
        $this->attraction->latitude = 29.9792;
        $this->attraction->longitude = 31.1342;
        $this->attraction->category = "Historical";
        $this->attraction->image_url = "pyramids.jpg";

        // Mock successful execution
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute create
        $result = $this->attraction->create();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test getting attractions with location filter
     */
    public function testGetAttractionsWithLocationFilter()
    {
        $filters = ['location' => 'Egypt'];
        
        // Mock fetchAll to return sample attractions
        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                [
                    'id' => 1,
                    'name' => 'Pyramids of Giza',
                    'location' => 'Giza, Egypt'
                ]
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getAttractions
        $result = $this->attraction->getAttractions($filters);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Pyramids of Giza', $result[0]['name']);
    }

    /**
     * Test getting popular attractions
     */
    public function testGetPopularAttractions()
    {
        // Mock fetchAll to return popular attractions
        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                ['id' => 1, 'name' => 'Attraction 1', 'rating' => 4.8],
                ['id' => 2, 'name' => 'Attraction 2', 'rating' => 4.7]
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getPopularAttractions
        $result = $this->attraction->getPopularAttractions();

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    /**
     * Test getting attraction by ID
     */
    public function testGetAttractionById()
    {
        $attractionId = 1;
        
        // Mock rowCount and fetch
        $this->mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id' => 1,
                'name' => 'Pyramids of Giza',
                'location' => 'Giza, Egypt',
                'rating' => 4.8
            ]);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getAttractionById
        $result = $this->attraction->getAttractionById($attractionId);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Pyramids of Giza', $result['name']);
    }

    /**
     * Test getting attraction categories
     */
    public function testGetCategories()
    {
        // Mock fetchAll to return categories
        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_COLUMN)
            ->willReturn(['Historical', 'Cultural', 'Adventure']);

        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Execute getCategories
        $result = $this->attraction->getCategories();

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertContains('Historical', $result);
    }
}

