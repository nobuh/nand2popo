<?php declare(strict_types=1);
use Nobuh\Nand2popo\Assembler\Code;
use function PHPUnit\Framework\assertSame;

class AssemblerCodeTest extends \PHPUnit\Framework\TestCase
{
    public function testCode(): void
    {
        assertSame(0b000, Code::dest(''));
        assertSame(0b001, Code::dest('M'));
        assertSame(0b010, Code::dest('D'));
        assertSame(0b011, Code::dest('MD'));
        assertSame(0b100, Code::dest('A'));        
        assertSame(0b101, Code::dest('AM'));
        assertSame(0b110, Code::dest('AD'));
        assertSame(0b111, Code::dest('AMD'));

        assertSame(0b0101010, Code::comp('0'));
        assertSame(0b0111111, Code::comp('1'));
        assertSame(0b0111010, Code::comp('-1'));
        assertSame(0b0001100, Code::comp('D'));
        assertSame(0b0110000, Code::comp('A'));
        assertSame(0b0001101, Code::comp('!D'));
        assertSame(0b0110001, Code::comp('!A'));
        assertSame(0b0001111, Code::comp('-D'));
        // skip
        assertSame(0b0110111, Code::comp('A+1'));
        assertSame(0b1110111, Code::comp('M+1'));
        // skip
        assertSame(0b1010101, Code::comp('D|M'));
        assertSame(0b1111111, Code::comp(''));

        assertSame(0b000, Code::jump(''));
        // skip
        assertSame(0b111, Code::jump('JMP'));
    }
}