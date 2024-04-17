# HashTable
Based off Crackstations HashTable (https://github.com/defuse/crackstation-hashdb)

# Features
Takes a folder of wordlists in the format "hash:word" and builds a hash table for "quick" lookups.

# IMPORTANT
- The wordlists must be in the format "hash:word" 
- Ensure the wordlist folder is in the same direcotry as the buildindex.sh script
- wordlists must be split into parts to make processing easier
- if you need to split files (`split -n l/20 --numeric-suffixes input output`) -> splits into 20 parts
- default memory allocation for sortidx is 2GB, if you need to change this edit the buildindex.sh script

# Usage
## Build indexes
Create a folder with the wordlists in the format "hash:word" (can be created using [hashgen](https://github.com/cyclone-github/hashgen) with `-hashplain` flag)

The following commands will build the hash table indexes. The index files will be outputted to a folder called "index" in the same directory as the buildindex.sh script. The script will create an index file for every file in the provided wordlist folder. the index file will be named the same as the wordlist file with the extension .idx. e.g. wordlist1 -> wordlist1.idx 
(**NOTE: the wordlist files had no extension when I tested this, so the script may need to be modified to work with files with extensions**)

Building tested in Ubuntu 22.04

* Remove previous build files:
`make clean`

* Compile build files: 
`make all`

* Mark the buildindex.sh script as executable: 
`chmod +x buildindex.sh`

* Run the buildindex.sh script with the folder containing the wordlists as an argument:
`./buildindex.sh "wordlist folder"`


## Searching
Searching should work with any system that has PHP

multisearch.php can be used to search for a single hash in the hash table

```php
php multisearch.php "NTLM" "index folder" "dictionary folder" "hash"
```
example `php multisearch.php NTLM index dicts a4f49c406510bdcab6824ee7c30fd852`


multisearch-files.php can be used to search for multiple hashes in the hash table by providing a file with hashes

```php
php multisearch-files.php "index folder" "dictionary folder" "hash file"
```

example 2 `php multisearch-files.php index dicts hashes.txt`

# Problems
If you get invalid `Error: Hash is not a valid hex string.` and the wordlist is in the correct format, it may be that the txt file is encoded with UTF-16LE. To fix this, convert the file to UTF-8.