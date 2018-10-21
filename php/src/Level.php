<?php declare(strict_types=1);

namespace DJB;

abstract class Level
{
    protected $input;
    protected $expected_output;
    protected $level;

    private function loadJSON($name)
    {
        $filename = __DIR__ . '/../../jobs/backend/level' . $this->level . '/data/' . $name . '.json';
        return json_decode(file_get_contents($filename));
    }

    public function __construct(int $level)
    {
        $this->level = $level;
        $this->input = $this->loadJSON('input');
        $this->expected_output = $this->loadJSON('expected_output');
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getExpectedOutput()
    {
        return $this->expected_output;
    }
    abstract public function run();
}