<?php
namespace App\Lib;
class Sudoku {
	protected $puzzle = array();
	protected $solution = array();

	public function getPuzzle()
	{
		return $this->puzzle;
	}
	public function setPuzzle(array $puzzle = array())
    {
		$this->puzzle = $puzzle;
		$this->setSolution($this->generateEmptyPuzzle());
		return true;
    }
	public function getSolution()
    {
        return $this->solution;
    }
	public function setSolution(array $solution)
    {
		$this->solution = $solution;
		return true;
    }
	public function solve()
    {
        if ($this->isSolvable()) {
            $this->solution = $this->calculateSolution($this->puzzle);
            return true;
        } else {
            return false;
        }
    }
	public function isSolved()
    {
        if (!$this->checkConstraints($this->solution)) {
            return false;
        }

        foreach ($this->puzzle as $x => $row) {
            foreach ($row as $y => $col) {
                if ($col !== 0) {
                    if ($this->puzzle[$x][$y] != $this->solution[$x][$y]) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
	public function isSolvable()
    {
        return $this->checkConstraints($this->puzzle, true);
    }
	public function generatePuzzle($cellCount)
    {
        if (!is_integer($cellCount) || $cellCount < 16 || $cellCount > 80) {
            return false;
        }
		$this->puzzle = $this->calculateSolution($this->generateEmptyPuzzle());
		$tempPuzzle = $this->puzzle;
		$is_repeat = false;
		do{
			$cells = array_rand(range(0, 80), $cellCount);
			$i = 0;
			foreach ($this->puzzle as &$row) {
				foreach ($row as &$cell) {
					if (!in_array($i++, $cells)) {
						$cell = 0;
					}
				}
			}
			foreach($this->puzzle as $x=>$rr) {
				if($x % 3 == 0)
					$hit = false;
				$result = array_unique($rr);
				if(count($result) == 1)
				{
					if($hit)
					{
						$is_repeat = true;
						$this->puzzle = $tempPuzzle;
						break;
					}
					else
						$hit = true;
				}
			}
			if(!$is_repeat)
			{
				$arr = $this->puzzle;
				for($y=0;$y<9;$y++)
				{
					if($y % 3 == 0)
						$hit = false;
					$temp = array();
					for($x=0;$x<9;$x++)
					{
						$temp[] = $arr[$x][$y];
					}
					$result = array_unique($temp);
					if(count($result) == 1)
					{
						if($hit)
						{
							$is_repeat = true;
							$this->puzzle = $tempPuzzle;
							break;
						}
						else
							$hit = true;
					}
				}
			}
		} while($is_repeat);
		$this->setSolution($this->generateEmptyPuzzle());
        return true;
    }
	protected function checkConstraints(array $puzzle, $allowZeros = false)
    {
        foreach ($puzzle as $x => $row) {
            if (!$this->checkContainerForViolations($row, $allowZeros)) {
                return false;
            }
            foreach ($row as $y => $cell) {
                if ($cell == 0) {
                    continue;
                }
                if (!in_array($cell, [1, 2, 3, 4, 5, 6, 7, 8, 9])) {
                    return false;
                }
                $columns[$y][] = $cell;
                if ($x % 3 == 0) {
                    $boxRow = $x;
                } else {
                    $boxRow = $x - $x % 3;
                }
                if ($y % 3 == 0) {
                    $boxColumn = $y;
                } else {
                    $boxColumn = $y - $y % 3;
                }
                $boxes[$boxRow . $boxColumn][] = $cell;
            }
        }
        if (isset($columns)) {
            foreach ($columns as $column) {
                if (!$this->checkContainerForViolations($column, $allowZeros)) {
                    return false;
                }
            }
        }
        if (isset($boxes)) {
            foreach ($boxes as $box) {
                if (!$this->checkContainerForViolations($box, $allowZeros)) {
                    return false;
                }
            }
        }
        return true;
    }
	protected function generateEmptyPuzzle()
    {
        return array_fill(0, 9, array_fill(0, 9, 0));
    }
	protected function calculateSolution(array $puzzle)
    {
        while (true) {
            $options = null;
            foreach ($puzzle as $x => $row) {
                $y = array_search(0, $row);
                if ($y === false) {
                    continue;
                }
                $validOptions = $this->getValidOptions($puzzle, $x, $y);
                if (count($validOptions) == 0) {
                    return false;
                }
                $options = array(
                    'rowIndex' => $x,
                    'columnIndex' => $y,
                    'validOptions' => $validOptions
                );
                break;
            }
            if ($options == null) {
                return $puzzle;
            }
            if (count($options['validOptions']) == 1) {
                $puzzle[$options['rowIndex']][$options['columnIndex']] = current($options['validOptions']);
                continue;
            }
            foreach ($options['validOptions'] as $value) {
                $tempPuzzle = $puzzle;
                $tempPuzzle[$options['rowIndex']][$options['columnIndex']] = $value;
                $result = $this->calculateSolution($tempPuzzle);
                if ($result == true) {
                    return $result;
                }
            }
            return false;
        }
    }
	protected function getValidOptions(array $grid, $rowIndex, $columnIndex)
    {
        $invalid = $grid[$rowIndex];
        for ($i = 0; $i < 9; $i++) {
            $invalid[] = $grid[$i][$columnIndex];
        }
        if ($rowIndex % 3 == 0) {
            $boxRow = $rowIndex;
        } else {
            $boxRow = $rowIndex - $rowIndex % 3;
        }
        if ($columnIndex % 3 == 0) {
            $boxColumn = $columnIndex;
        } else {
            $boxColumn = $columnIndex - $columnIndex % 3;
        }
        $invalid = array_unique(
            array_merge($invalid, array_slice($grid[$boxRow], $boxColumn, 3), array_slice($grid[$boxRow + 1], $boxColumn, 3), array_slice($grid[$boxRow + 2], $boxColumn, 3))
        );
        $valid = array_diff(range(1, 9), $invalid);
        shuffle($valid);
        return $valid;
    }
	protected function checkContainerForViolations(array $container, $allowZeros = false)
    {
        if (!$allowZeros && in_array(0, $container)) {
            return false;
        }
        if (($keys = array_keys($container, 0)) !== false) {
            foreach ($keys as $key) {
                unset($container[$key]);
            }
        }
        if (count($container) != count(array_unique($container))) {
            return false;
        }
        return true;
    }
}