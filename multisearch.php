<?php

// Multi file search
// check hash against all idx files in the directory and matches them to their dictionary files

// include classes/functions
require_once('LookupTable.php');

// check args
if ($argc != 5) {
    printUsage();
    die();
}

// command line args
$hashType = $argv[1];
$indexFolder = $argv[2];
$dictFolder = $argv[3];
$hash = $argv[4];


// get the list of idx files and remove .. and .
$idxFiles = scandir($indexFolder);
$idxFiles = array_diff($idxFiles, array('..', '.'));

// get the list of dictionary files and remove .. and .
$dictFiles = scandir($dictFolder);
$dictFiles = array_diff($dictFiles, array('..', '.'));


// loop through the idx files, create the lookup table and crack the hash
foreach ($idxFiles as $idxFile) {
    $idxPath = $indexFolder . '/' . $idxFile;
    $dictPath = $dictFolder . '/' . pathinfo($idxFile, PATHINFO_FILENAME);


    try {
        // create a lookup table
        $lookupTable = new LookupTable($idxPath, $dictPath, $hashType);

        // crack the hash
        $results = $lookupTable->crack($hash);

        // output the password only
        if (!empty($results)) {
            foreach ($results as $result) {
                echo $result->getPlaintext() . "\n";
            }
        }
    }
    catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// Usage explanation
function printUsage() {
    echo "Usage: php MultiFileSearch.php <hash> <idx dir> <dict dir>\n";
    echo "  <hash> - the hash to search for\n\n";
    echo " ASSUMES THAT THE IDX FILES ARE NAMED THE SAME AS THE DICTIONARY FILES\n\n";
    echo "  <idx dir> - the directory containing the idx files\n";
    echo "  <dict dir> - the directory containing the dictionary files\n";
}