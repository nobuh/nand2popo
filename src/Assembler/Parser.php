<?php declare(strict_types=1);
namespace Nobuh\Nand2popo\Assembler;

class Parser
{
    public $fd;
    public string $currentCommand;
    public string $dest;
    public string $comp;
    public string $jump;

    public function __construct(string $filename)
    {
        $this->fd = fopen($filename, "r");
    }

    public function hasMoreCommands(): bool
    {
        return !feof($this->fd);
    }

    public function advance(): void
    {
        if ($this->hasMoreCommands() && ($s = fgets($this->fd))) {
            // remove tail new line char
            $s = rtrim($s);
            // remove comment
            $s = preg_replace('/\/\/.*$/', '', $s);
            // remove space and tab
            $s = preg_replace('/ |\t/', '', $s);

            // split the input into 2 by `=` and the first part is $dest
            $destToken = explode('=', $s, 2);
            if (count($destToken) < 2) {
                $this->dest = '';
            } else {
                $this->dest = $destToken[0];
                array_shift($destToken);
            }
            // and split by ';'
            $jumpToken = explode(';', $destToken[0], 2);
            $this->jump = '';
            $this->comp = $jumpToken[0];
            if (count($jumpToken) > 1) {
                $this->jump = $jumpToken[1];
            }

            // comp or A or L command must contain some alpha numerics
            if (preg_match('/[A-Z0-9]+/', $this->comp)) {
                $this->currentCommand = $s;
            } else {
                $this->currentCommand = '';
                $this->comp = '';
            }
        }
    }

    public function commandType(): int
    {
        if (preg_match("/@[A-Z0-9]+/", $this->currentCommand)) {
            return Command::A_COMMAND;
        }
        if (preg_match("/\([A-Z0-9]+\)/", $this->currentCommand)) {
            return Command::L_COMMAND;
        }
        return Command::C_COMMAND;
    }

    public function dest(): string
    {
        if (preg_match('/[AMD]+/', $this->dest)) {
            return $this->dest;
        } else {
            return '';
        }
    }
}
