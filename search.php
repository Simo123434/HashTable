<?php

// Include the required classes and functions
require_once('MoreHashes.php');
require_once('LookupTable.php');

// Check if the correct number of command-line arguments is provided
if ($argc != 5) {
    printUsage();
    die();
}

// Command-line arguments
$indexFile = $argv[1];
$dictFile = $argv[2];
$hashType = $argv[3];
$hash = $argv[4];



try {
    // Create a lookup table

    // Load the index and dictionary files
    $lookupTable = new LookupTable($indexFile, $dictFile, $hashType);

    // Crack the hash
    $results = $lookupTable->crack($hash);

        // Output the password only
    if (!empty($results)) {
        foreach ($results as $result) {
            echo $result->getPlaintext() . "\n";
        }
    }
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Function to print script usage
function printUsage()
{
    echo "Usage: php lookup.php <indexFile> <dictFile> <hashType> <hash>\n\n";
    echo "indexFile - Path to the folder containing index files.\n";
    echo "dictFile - Path to the folder containing dictionary files.\n";
    echo "hashType - Hash algorithm. See: hash()\n";
    echo "hash - The hash to search for.\n\n";
}
?>

