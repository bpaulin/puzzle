<?php
namespace AppBundle\Puzzle;

/**
 * Class Pattern
 * @package AppBundle\Puzzle
 */
class Pattern
{
    /**
     * unmarked square
     */
    const UNDEFINED = 0;
    /**
     * black square
     */
    const BLACK = 1;
    /**
     * white square
     */
    const WHITE = 2;

    /**
     * @var int
     */
    protected $sizeX;
    /**
     * @var int
     */
    protected $sizeY;

    /**
     * @var array
     */
    protected $solution;
    /**
     * @var array
     */
    protected $grid;

    /**
     * @param $sizeX
     * @param $sizeY
     */
    public function __construct($sizeX, $sizeY)
    {
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->solution = $this->fillArray();
        $this->grid = $this->fillArray();
    }

    /**
     * fill 2 dim array with UNDEFINED
     *
     * @return array
     */
    private function fillArray()
    {
        $return = array();
        for ($x=0; $x<$this->sizeX; $x++) {
            $line = array();
            for ($y = 0; $y < $this->sizeY; $y++) {
                $line[] = self::UNDEFINED;
            }
            $return[] = $line;
        }
        return $return;
    }

    /**
     * @return mixed
     */
    public function getSizeX()
    {
        return $this->sizeX;
    }

    /**
     * @return mixed
     */
    public function getSizeY()
    {
        return $this->sizeY;
    }

    /**
     * @return array
     */
    public function getSolution()
    {
        return $this->solution;
    }

    /**
     * @param $solution
     *
     * @return $this
     */
    public function setSolution($solution)
    {
        $this->solution = $solution;

        return $this;
    }


    /**
     * @param $grid
     *
     * @return $this
     */
    public function setGrid($grid)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * @return array
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @param $x
     * @param $y
     * @param $color
     *
     * @return $this
     */
    public function setSquare($x, $y, $color)
    {
        $this->grid[$x][$y] = $color;

        return $this;
    }

    /**
     * @param $x
     * @param $y
     * @return mixed
     */
    public function getSquare($x, $y)
    {
        return $this->grid[$x][$y];
    }

    /**
     * @param $x
     * @param $y
     * @return bool|null
     */
    public function checkSquare($x, $y)
    {
        if ($this->getSquare($x, $y) === self::UNDEFINED) {
            return null;
        }
        return ($this->getSquare($x, $y) === $this->getSolution()[$x][$y]);
    }

    /**
     * @param $y
     * @param bool $force
     * @return bool|null
     */
    public function checkLineY($y, $force = false)
    {
        foreach ($this->getLineY($y) as $x => $square) {
            if ($force && $square === self::UNDEFINED) {
                continue;
            }
            if ($square === self::UNDEFINED) {
                return null;
            }
            if ($square !== $this->solution[$x][$y]) {
                return false;
            }
        }
        return true;
    }

    protected function getLine2DimY($tab, $y)
    {
        $line = array();
        foreach ($tab as $lineX) {
            $line[] = $lineX[$y];
        }
        return $line;
    }

    /**
     * @param $y
     * @return array
     */
    public function getLineY($y)
    {
        return $this->getLine2DimY($this->grid, $y);
    }

    /**
     * @param $x
     * @return mixed
     */
    public function getLineX($x)
    {
        return $this->grid[$x];
    }

    /**
     * @param $x
     * @param bool $force
     * @return bool|null
     */
    public function checkLineX($x, $force = false)
    {
        foreach ($this->grid[$x] as $y => $square) {
            if ($force && $square === self::UNDEFINED) {
                continue;
            }
            if ($square === self::UNDEFINED) {
                return null;
            }
            if ($square !== $this->solution[$x][$y]) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $x
     * @return string
     */
    public function getGridStringX($x)
    {
        return join('', $this->grid[$x]);
    }

    /**
     * @param $y
     * @return string
     */
    public function getGridStringY($y)
    {
        return join('', $this->getLineY($y));
    }

    protected function get2DimHints($tab)
    {
        $hints = array();
        $current = 0;
        foreach ($tab as $square) {
            if ($square === self::BLACK) {
                $current += 1;
            } elseif ($current > 0) {
                $hints[] = $current;
                $current = 0;
            }
        }
        if ($current>0) {
            $hints[] = $current;
        }
        return $hints;
    }

    public function getHintsX($x)
    {
        return $this->get2DimHints($this->solution[$x]);
    }

    public function getHintsY($y)
    {
        return $this->get2DimHints($this->getLine2DimY($this->solution, $y));
    }

    public function randomFill($ratio)
    {
        for ($x = 0; $x<$this->getSizeX(); $x++) {
            for ($y = 0; $y<$this->getSizeY(); $y++) {
                if (rand()<$ratio) {
                    $this->setSquare($x, $y, self::BLACK);
                } else {
                    $this->setSquare($x, $y, self::WHITE);
                }
            }
        }
    }

    public function getSuccess()
    {
        for ($x = 0; $x<$this->getSizeX(); $x++) {
            for ($y = 0; $y<$this->getSizeY(); $y++) {
                if ($this->solution[$x][$y] != $this->grid[$x][$y]) {
                    return false;
                }
            }
        }
        return true;
    }
}
