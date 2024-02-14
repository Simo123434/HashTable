<?php

/*
 * Usage: php createidx.php <hashtype> <input_directory> <output_directory>
 *
 * hashtype - The type of hash. See the php hash() function documentation.
 * input_directory - The directory containing files with hashes and words in the format "hash:word".
 * output_directory - The directory where the index files will be stored.
 *
 * The script reads each file in the input directory and creates an index file for each file.
 * The index file is created in the output directory with the same name as the input file, but with the extension changed to ".idx".
 *
 * The index file has the following format:
 *
 *      [HASH_PART][WORDLIST_OFFSET][HASH_PART][WORDLIST_OFFSET]...
 *
 *   HASH_PART is the first 64 BITS of the hash, right-padded with zeroes if necessary.
 *   WORDLIST_OFFSET is the position of the first character of the word in the dictionary encoded as a 48-bit LITTLE ENDIAN integer.
 *
 * NOTE: This only supports the hashes supported by php's hash() function. If 
 *       you want more, just modify the code.
 */

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

// check if the input directory exists, if not create it
if (!is_dir($inputDirectory)) {
    echo "Input directory does not exist.\n";
    echo "Creating input directory: $inputDirectory\n";
    if (!mkdir($inputDirectory, 0777, true)) {
        echo "Failed to create input directory: $inputDirectory\n";
    }
}

// check if the output directory exists, if not create it
if (!is_dir($outputDirectory)) {
    echo "Output directory does not exist.\n";
    echo "Creating output directory: $outputDirectory\n";
    if (!mkdir($outputDirectory, 0777, true)) {
        echo "Failed to create output directory: $outputDirectory\n";
    }
}

// Get the list of files in the input directory
$files = scandir($inputDirectory);
foreach ($files as $file) {
    // Skip . and .. files
    if ($file === '.' || $file === '..') {
        continue;
    }


    $inputFile = $inputDirectory . '/' . $file;
    // Create the output file name as the input file name with the extension changed to .idx
    $outputFile = $outputDirectory . '/' . pathinfo($file, PATHINFO_FILENAME) . '.idx';

    if (($input = fopen($inputFile, "rb")) == FALSE) {
        echo "Couldn't open input file: $inputFile.\n";
        continue;
    }

    if (($index = fopen($outputFile, "wb")) == FALSE) {
        echo "Could not open output file: $outputFile.\n";
        fclose($input);
        continue;
    }

    $progressLines = 0;
    $position = ftell($input);
    while (($line = fgets($input)) !== FALSE) {
        $line = trim($line, "\n\r"); // Get rid of any extra newline characters, but don't get rid of spaces or tabs.
        $parts = explode(':', $line, 2);
        if (count($parts) !== 2) {
            // check if the line is in the format "hash:word"
            echo "Skipping line [$line] in file $inputFile because it is not in the format 'hash:word'.\n";
            continue;
        }
        $hash = $parts[0];
        $word = $parts[1];
        // get first 64 bits of the hash
        $hash = getFirst64Bits($hash);
        fwrite($index, $hash);
        fwrite($index, encodeTo48Bits($position));

        $position = ftell($input);
        $progressLines++;
        if($progressLines % 1000000 == 0) // Arbitrary.
        {
            $gb = round((double)$position / pow(1024, 3), 3);
            echo "So far, completed $progressLines lines " . $gb . "GB ...\n";
        }
    }

    fclose($input);
    fclose($index);

    echo "Index creation complete for file: $inputFile. Index file: $outputFile\n";
}

echo "All index files created. Please sort the index files using the C program.\n";

/* Encode 64 bit integer to 48-bit little endian */
function encodeTo48Bits($n)
{
    // 6 Bytes = 48-bits.
    $foo = array('\0', '\0', '\0', '\0', '\0', '\0');
    for ($i = 0, $p = 0; $i < 48; $i += 8, $p++) {
        $foo[$p] = chr(($n >> $i) % 256);
    }
    return implode('', $foo);
}

/* Get first 64 bits of binary hash
    * Always padded to 64 bits with null bytes
    */
function getFirst64Bits($fullHash)
{
    // Convert the hex hash to binary
    $binaryHash = hex2bin($fullHash);

    // Extract the first 8 bytes (64 bits)
    $first64Bits = substr($binaryHash, 0, 8);

    // If the length is less than 8 bytes, pad with zeroes
    if (strlen($first64Bits) < 8) {
        $first64Bits = str_pad($first64Bits, 8, "\x00", STR_PAD_RIGHT);
    }
    return $first64Bits;
}

function printUsage()
{
    echo "Usage: php createidx.php <input_directory> <output_directory>\n\n";
    echo "input_directory - The directory containing files with hashes and words in the format \"hash:word\"\n";
    echo "output_directory - The directory where the index files will be stored.\n";
}
?>