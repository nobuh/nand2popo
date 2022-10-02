<?php declare(strict_types=1);
namespace Nobuh\Nand2popo\Assembler;

class Parser
{
    public $fd;
    public string $line;

    public function __construct(string $filename)
    {
        $this->fd = fopen($filename, "r");
    }

    public function hasMoreCommands(): bool
    {
        return !feof($this->fd);
    }
}
