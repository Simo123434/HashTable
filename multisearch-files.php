<?php

// Multi file search
// check hash against all idx files in the directory and matches them to their dictionary files

// include classes/functions
require_once "LookupTable.php";

// check args
if ($argc != 5) {
    printUsage();
    die();
}

// command line args
$hashType = $argv[1];
$indexFolder = $argv[2];
$dictFolder = $argv[3];
$hashFile = $argv[4];

// get the list of idx files and remove .. and .
$idxFiles = scandir($indexFolder);
$idxFiles = array_diff($idxFiles, ["..", "."]);

// get the list of dictionary files and remove .. and .
$dictFiles = scandir($dictFolder);
$dictFiles = array_diff($dictFiles, ["..", "."]);

// read the hashes from the file
$hashes = file($hashFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// loop through the idx files, create the lookup table and crack the hashes
foreach ($hashes as $hash) {
    // take the hash from the format user:hash
    $hashstr = explode(":", $hash)[1];
    $user = explode(":", $hash)[0];

    // Check for password found in any of the idx files
    $passwordFound = false;
    foreach ($idxFiles as $idxFile) {
        $idxPath = $indexFolder . "/" . $idxFile;
        $dictPath = $dictFolder . "/" . pathinfo($idxFile, PATHINFO_FILENAME);

        try {
            $lookupTable = new LookupTable($idxPath, $dictPath, $hashType);
            $results = $lookupTable->crack($hashstr);

            if (!empty($results)) {
                foreach ($results as $result) {
                    $crackedpassword = $result->getPlaintext() . "\n";
                    $crackedpassword = $user . ":" . $crackedpassword;
                    echo $crackedpassword;
                    $passwordFound = true; // Mark password as found
                    break 2; // Exit both loops to move to the next hash
                }
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            // continue to try with the next idx file
        }
    }

    // If password wasn't found in any idx file, proceed to the next hash
    if (!$passwordFound) {
        echo "Password for hash $hashstr not found.\n";
    }
}

// Usage explanation
function printUsage()
{
    echo "Usage: php MultiFileSearch.php <hash> <idx dir> <dict dir> <hash file>\n";
    echo "  <hash> - the hash to search for\n\n";
    echo " ASSUMES THAT THE IDX FILES ARE NAMED THE SAME AS THE DICTIONARY FILES\n\n";
    echo "  <idx dir> - the directory containing the idx files\n";
    echo "  <dict dir> - the directory containing the dictionary files\n";
    echo " <hash file> - the file containing the hashes to search for\n\n";
}
