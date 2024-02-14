<?php
require_once('LookupTable.php');

$hashes = $_POST['hashes'];
$indexFolder = './index';
$dictFolder = './dicts';
$hashType = 'NTLM';

$idxFiles = scandir($indexFolder);
$idxFiles = array_diff($idxFiles, array('..', '.'));

$dictFiles = scandir($dictFolder);
$dictFiles = array_diff($dictFiles, array('..', '.'));

$resultsArray = array();

foreach ($hashes as $hash) {
    foreach ($idxFiles as $idxFile) {
        $idxPath = $indexFolder . '/' . $idxFile;
        $dictPath = $dictFolder . '/' . pathinfo($idxFile, PATHINFO_FILENAME);
        try {
            $lookupTable = new LookupTable($idxPath, $dictPath, $hashType);
            $results = $lookupTable->crack($hash);
            if (!empty($results)) {
                foreach ($results as $result) {
                    $resultsArray[] = $result->getPlaintext();
                }
            }
        }
        catch (Exception $e) {
            $resultsArray[] = "Error: " . $e->getMessage();
        }
    }
}

echo json_encode($resultsArray);
?>