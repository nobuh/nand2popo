<?php declare(strict_types=1);
use Nobuh\Nand2popo\Assembler\Command;
use Nobuh\Nand2popo\Assembler\Parser;
use function PHPUnit\Framework\assertSame;

class AssemblerParserTest extends \PHPUnit\Framework\TestCase
{
    public function testHasMoreCommands(): void
    {
        $p = new Parser("tests/oneline.txt");
        assertSame(true, $p->hasMoreCommands());

        $p->advance();
        // the fils contains only a comment line
        assertSame('', $p->currentCommand); 
        assertSame(false, $p->hasMoreCommands());
    }

    public function testCommandType(): void
    {
        $p = new Parser("tests/oneline.txt");
        $p->currentCommand = "  @A123   ";
        assertSame(Command::A_COMMAND, $p->commandType());    
        $p->currentCommand = "  @123   ";
        assertSame(Command::A_COMMAND, $p->commandType());    
        $p->currentCommand = "  AMD=D+1;JGT  ";
        assertSame(Command::C_COMMAND, $p->commandType());        
        $p->currentCommand = "  D&A  ";
        assertSame(Command::C_COMMAND, $p->commandType());        
        $p->currentCommand = "  AD=D|A  ";
        assertSame(Command::C_COMMAND, $p->commandType());        
        $p->currentCommand = "  AD=D-A;JMP  ";
        assertSame(Command::C_COMMAND, $p->commandType());        
        $p->currentCommand = "  (X123)   ";
        assertSame(Command::L_COMMAND, $p->commandType());   
        $p->currentCommand = "  (1)   ";
        assertSame(Command::L_COMMAND, $p->commandType());
    }

    public function testAdvance(): void
    {
        $p = new Parser("tests/commandsample.txt");
        $p->advance();
        assertSame('AD=D-1;JGT', $p->currentCommand);
        assertSame('AD', $p->dest);
        assertSame('D-1', $p->comp);
        assertSame('JGT', $p->jump);

        $p->advance();
        assertSame('', $p->dest);
        assertSame('(123)', $p->comp);
        assertSame('', $p->jump);

        $p->advance();
        assertSame('', $p->dest);
        assertSame('@123', $p->comp);
        assertSame('', $p->jump);
    }        
}