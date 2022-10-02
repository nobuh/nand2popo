<?php declare(strict_types=1);
use Nobuh\Nand2popo\Assembler\Parser;
use function PHPUnit\Framework\assertSame;

class AssemblerParserTest extends \PHPUnit\Framework\TestCase
{
    public function testHasMoreCommands(): void
    {
        $p = new Parser("tests/oneline.txt");
        assertSame(true, $p->hasMoreCommands());
        fgets($p->fd);
        assertSame(false, $p->hasMoreCommands());
    }
}