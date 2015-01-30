<?php

namespace AppBundle\Tests\Puzzle;

use AppBundle\Puzzle\Pattern;

class PatternTest extends \PHPUnit_Framework_TestCase
{
    /** @var  $pattern Pattern */
    protected $pattern;
    protected $solution;

    protected function setUp()
    {
        $this->pattern = new Pattern(10, 15);
        $w = Pattern::WHITE;
        $b = Pattern::BLACK;
        $u = Pattern::UNDEFINED;
        $this->solution = array(
            array( $b, $b, $b, $b, $b, $b, $b, $b, $b, $b, $w, $w, $b, $b, $w ),
            array( $b, $b, $b, $b, $b, $b, $b, $b, $b, $w, $w, $w, $b, $b, $w ),
            array( $b, $b, $b, $b, $b, $b, $b, $b, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $b, $b, $b, $b, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $b, $b, $b, $w, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $b, $b, $w, $w, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $b, $w, $w, $b, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $w, $w, $w, $w, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $w, $w, $w, $w, $b, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $w, $w, $w, $w, $w, $b, $w, $w, $w, $w, $w, $w, $w, $w )
        );
        $this->grid = array(
            array( $b, $b, $b, $b, $b, $b, $b, $b, $u, $u, $w, $w, $b, $b, $w ),
            array( $b, $b, $b, $b, $b, $b, $b, $b, $b, $u, $w, $w, $b, $b, $w ),
            array( $b, $b, $b, $b, $b, $b, $b, $b, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $b, $b, $b, $b, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $b, $b, $b, $w, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $b, $b, $w, $w, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $b, $w, $w, $b, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $b, $w, $w, $w, $w, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $b, $w, $w, $w, $w, $b, $w, $w, $w, $w, $w, $w, $w, $w ),
            array( $b, $w, $w, $w, $w, $w, $b, $w, $w, $w, $w, $w, $w, $w, $w )
        );
    }

    public function testSizeCanBeModified()
    {
        $this->assertEquals(10, $this->pattern->getSizeX());
        $this->assertEquals(15, $this->pattern->getSizeY());
    }

    public function testSolutionIsInitialized()
    {
        for ($x = 0; $x<10; $x++) {
            for ($y = 0; $y < 15; $y++) {
                $this->assertEquals(Pattern::UNDEFINED, $this->pattern->getSquare($x, $y));
            }
        }
    }

    public function testSolutionCanBeSet()
    {
        $this->pattern->setSolution($this->solution);
        $this->assertEquals($this->solution, $this->pattern->getSolution());
        $this->assertEquals(Pattern::WHITE, $this->pattern->getSolution()[1][9]);
    }

    public function testGridCanBeSet()
    {
        $this->pattern->setGrid($this->grid);
        $this->assertEquals($this->grid, $this->pattern->getGrid());
        $this->assertEquals(Pattern::UNDEFINED, $this->pattern->getGrid()[1][9]);
    }

    public function testGridIsInitialized()
    {
        $grid = $this->pattern->getGrid();
        $this->assertEquals(10, count($grid));
        $this->assertEquals(15, min(array_map('count', $grid)));
        $this->assertEquals(15, max(array_map('count', $grid)));
    }

    public function testSquareCanBeSet()
    {
        $this->assertEquals(Pattern::UNDEFINED, $this->pattern->getSquare(2, 2));
        $this->pattern->setSquare(2, 2, Pattern::WHITE);
        $this->assertEquals(2, 2, Pattern::BLACK);
    }

    public function testSquareCanBeChecked()
    {
        $this->pattern->setSolution($this->solution);
        $this->pattern->setSquare(1, 1, Pattern::WHITE);
        $this->pattern->setSquare(1, 2, Pattern::BLACK);
        $this->assertFalse($this->pattern->checkSquare(1, 1));
        $this->assertTrue($this->pattern->checkSquare(1, 2));
        $this->assertNull($this->pattern->checkSquare(1, 3));
    }

    public function testLineYCanBeChecked()
    {
        $this->pattern->setSolution($this->solution);
        $this->pattern->setGrid($this->grid);
        $this->assertNull($this->pattern->checkLineY(8));
        $this->pattern->setSquare(0, 8, Pattern::WHITE);
        $this->assertFalse($this->pattern->checkLineY(8));
        $this->pattern->setSquare(0, 8, Pattern::BLACK);
        $this->assertTrue($this->pattern->checkLineY(8));
    }

    public function testLineYCanBeForcedChecked()
    {
        $this->pattern->setSolution($this->solution);
        $this->pattern->setGrid($this->grid);
        $this->assertTrue($this->pattern->checkLineY(9, true));
        $this->pattern->setSquare(1, 9, Pattern::BLACK);
        $this->assertFalse($this->pattern->checkLineY(9, true));
        $this->pattern->setSquare(1, 9, Pattern::WHITE);
        $this->assertTrue($this->pattern->checkLineY(9, true));
    }

    public function testGetLineY()
    {
        $w = Pattern::WHITE;
        $b = Pattern::BLACK;
        $this->pattern->setGrid($this->grid);
        $this->assertSame(
            $this->pattern->getLineY(1),
            array( $b, $b, $b, $b, $b, $b, $b, $b, $b, $w)
        );
    }

    public function testGetLineX()
    {
        $w = Pattern::WHITE;
        $b = Pattern::BLACK;
        $u = Pattern::UNDEFINED;
        $this->pattern->setGrid($this->grid);
        $this->assertSame(
            $this->pattern->getLineX(1),
            array( $b, $b, $b, $b, $b, $b, $b, $b, $b, $u, $w, $w, $b, $b, $w )
        );
    }

    public function testLineXCanBeChecked()
    {
        $this->pattern->setSolution($this->solution);
        $this->pattern->setGrid($this->grid);
        $this->assertNull($this->pattern->checkLineX(1));
        $this->pattern->setSquare(1, 9, Pattern::BLACK);
        $this->assertFalse($this->pattern->checkLineX(1));
        $this->pattern->setSquare(1, 9, Pattern::WHITE);
        $this->assertTrue($this->pattern->checkLineX(1));
    }

    public function testLineXCanBeForcedChecked()
    {
        $this->pattern->setSolution($this->solution);
        $this->pattern->setGrid($this->grid);
        $this->assertTrue($this->pattern->checkLineX(1, true));
        $this->pattern->setSquare(1, 9, Pattern::BLACK);
        $this->assertFalse($this->pattern->checkLineX(1, true));
        $this->pattern->setSquare(1, 9, Pattern::WHITE);
        $this->assertTrue($this->pattern->checkLineX(1, true));
    }

    public function testGetHintsX()
    {
        $this->pattern->setSolution($this->solution);
        $this->pattern->setGrid($this->grid);
        $this->assertSame(
            $this->pattern->getHintsX(1),
            array(9,2)
        );
    }

    public function testGetHintsY()
    {
        $this->pattern->setSolution($this->solution);
        $this->pattern->setGrid($this->grid);
        $this->assertSame(
            $this->pattern->getHintsY(6),
            array(4, 1, 2)
        );
    }

    public function testRandomFill()
    {
        $this->pattern->randomFill(0.5);
        for ($x = 0; $x<10; $x++) {
            for ($y = 0; $y < 15; $y++) {
                $this->assertTrue(
                    Pattern::BLACK === $this->pattern->getSquare($x, $y) or
                    Pattern::WHITE === $this->pattern->getSquare($x, $y)
                );
            }
        }
    }

    public function testCanBeWon()
    {
        $this->pattern->setSolution($this->solution);
        $this->pattern->setGrid($this->grid);
        $this->assertFalse($this->pattern->getSuccess());
        $this->pattern->setSquare(1, 9, Pattern::WHITE);
        $this->assertFalse($this->pattern->getSuccess());
        $this->pattern->setSquare(0, 8, Pattern::BLACK);
        $this->assertFalse($this->pattern->getSuccess());
        $this->pattern->setSquare(0, 9, Pattern::BLACK);
        $this->assertTrue($this->pattern->getSuccess());

    }
}
