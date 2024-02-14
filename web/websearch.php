<?php
require_once('LookupTable.php');

$hash = $_POST['hash'];
$indexFolder = './index';
$dictFolder = './dicts';
$hashType = 'NTLM';

$idxFiles = scandir($indexFolder);
$idxFiles = array_diff($idxFiles, array('..', '.'));

$dictFiles = scandir($dictFolder);
$dictFiles = array_diff($dictFiles, array('..', '.'));

foreach ($idxFiles as $idxFile) {
    $idxPath = $indexFolder . '/' . $idxFile;
    $dictPath = $dictFolder . '/' . pathinfo($idxFile, PATHINFO_FILENAME);
    try {
        $lookupTable = new LookupTable($idxPath, $dictPath, $hashType);
        $results = $lookupTable->crack($hash);
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
?>