<?php declare(strict_types=1);
require 'vendor/autoload.php';
use Nobuh\Nand2popo\Assembler\Code;
use Nobuh\Nand2popo\Assembler\Command;
use Nobuh\Nand2popo\Assembler\Parser;

if ($argc < 2) {
    echo 'usage: php Assembler.php <source .asm file>' . PHP_EOL;
    exit(1);
}

$asmName = $argv[1];
$hackName = basename($asmName, '.asm') . '.hack';

if (! file_exists($asmName)) {
    echo 'Assembler.php: input file not exists.' . PHP_EOL;
    exit(2);
};
if (!($hack = fopen($hackName, "w"))) {
    echo 'Assembler.php: output file open failed.' . PHP_EOL;
    exit(3);
};

$parser = new Parser($asmName);

while ($parser->hasMoreCommands()) {
    $parser->advance();

    switch ($parser->commandType()) {
        case Command::A_COMMAND:
            $value = $parser->symbol() & 0b0111_1111_1111_1111;
            break;
        case Command::C_COMMAND:
            if ($parser->comp() === '') {
                $value = null;
            } else {
                $value = 0b1110_0000_0000_0000;   // C Command's left 3 bits must be on
                $value += Code::comp($parser->comp()) * 2**6; // shift 6 bits.
                $value += Code::dest($parser->dest()) * 2**3; // shift 3 bits.
                $value += Code::jump($parser->jump());    
            }
            break;
        case Command::L_COMMAND:
        default:
            $value = null;
    }

    // Write Hack Code 
    if (!is_null($value)) {
        $code = sprintf("%016b", $value) . PHP_EOL;
        if (!fwrite($hack, $code)) {
            echo 'Assembler.php: write failed on ' . $hackName . PHP_EOL;
            exit(4);
        }
    }
}

fclose($hack);
fclose($parser->fd);