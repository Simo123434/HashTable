<?php
if (PHP_INT_SIZE < 8) {
    echo "This script requires 64-bit PHP.\n";
    die();
}

if ($argc < 3) {
    printUsage();
    die();
}

$inputDirectory = $argv[1];
$outputDirectory = $argv[2];

if (!file_exists($inputDirectory) && !mkdir($inputDirectory, 0777, true)) {
    die("Failed to create input directory: $inputDirectory\n");
}

if (!file_exists($outputDirectory) && !mkdir($outputDirectory, 0777, true)) {
    die("Failed to create output directory: $outputDirectory\n");
}

$files = glob($inputDirectory . '/*');

foreach ($files as $inputFile) {
    $outputFile = $outputDirectory . '/' . pathinfo($inputFile, PATHINFO_FILENAME) . '.idx';

    if (!($input = fopen($inputFile, "rb"))) {
        echo "Couldn't open input file: $inputFile.\n";
        continue;
    }

    if (!($index = fopen($outputFile, "wb"))) {
        echo "Could not open output file: $outputFile.\n";
        fclose($input);
        continue;
    }

    $progressLines = 0;
    $position = ftell($input);
    $buffer = '';

    while (($line = fgets($input)) !== FALSE) {
        $line = trim($line, "\n\r");
        $parts = explode(':', $line, 2);

        if (count($parts) !== 2) {
            echo "Skipping line [$line] in file $inputFile because it is not in the format 'hash:word'.\n";
            continue;
        }

        $hash = getFirst64Bits($parts[0]);
        $offset = encodeTo48Bits($position);
        $buffer .= $hash . $offset;

        $position = ftell($input);
        $progressLines++;

        if ($progressLines % 1000000 == 0) {
            echo "So far, completed $progressLines lines ...\n";
        }
    }

    fwrite($index, $buffer);
    fclose($input);
    fclose($index);

    echo "Index creation complete for file: $inputFile. Index file: $outputFile\n";
}

echo "All index files created. Please sort the index files using the C program.\n";

function encodeTo48Bits($n)
{
    $foo = pack('P', $n); // Use pack to convert to 48 bits
    return substr($foo, 0, 6); // Get the first 6 bytes (48 bits)
}

function getFirst64Bits($fullHash)
{
    $binaryHash = hex2bin($fullHash);
    return str_pad(substr($binaryHash, 0, 8), 8, "\x00", STR_PAD_RIGHT);
}

function printUsage()
{
    echo "Usage: php createidx.php <input_directory> <output_directory>\n\n";
    echo "input_directory - The directory containing files with hashes and words in the format \"hash:word\"\n";
    echo "output_directory - The directory where the index files will be stored.\n";
}
?>
