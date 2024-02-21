# HashTable
Based off Crackstations HashTable (https://github.com/defuse/crackstation-hashdb)

# TODO
- potentially add parser for "user:hash" in search (for use with collected NTDIS) and output uesr:password
- fix web 

# Features
Takes a folder of wordlists in the format "hash:word" and builds a hash table for quick lookups.

# IMPORTANT
- The wordlists must be in the format "hash:word" 
- Ensure the wordlist folder is in the same direcotry as the buildindex.sh script
- wordlists must be split into parts to make processing easier
- if you need to split files (`split -n l/20 --numeric-suffixes input output`) -> splits into 20 parts
- There are 2 versions to build the index (multicreateidx.php and threadedmulti.php) to choose which version change in the buildindex.sh script
- default memory allocation for sortidx is 2GB, if you need to change this edit the buildindex.sh script

# Usage
## Build indexes
a 4gb wordlist will take around 20 minutes to build the index, this will vary on systems

```
make clean
make all
chmod +x sortcheck.sh
chmod +x buildindex.sh
./buildindex.sh "wordlist folder"
```

## Searching
```php
php multisearch.php "hashtype" "index folder" "dictionary folder" "hash"

php multisearch-files.php "hashtype" "index folder" "dictionary folder" "hash file"
```
example `php multisearch.php NTLM index dicts a4f49c406510bdcab6824ee7c30fd852`

example 2 `php multisearch-files.php NTLM index dicts hashes.txt`