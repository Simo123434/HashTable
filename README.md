# HashTable
Based off Crackstations HashTable (https://github.com/defuse/crackstation-hashdb)

# TODO
- potentially add parser for "user:hash" in search (for use with collected NTDIS) and output uesr:password
- make the createidx multi-threaded?

# Features
Takes a folder of wordlists in the format "hash:word" and builds a hash table for quick lookups.

# IMPORTANT
- The wordlists must be in the format "hash:word" 
- Ensure the wordlist folder is in the same direcotry as the buildindex.sh script
- wordlists must be split into parts to make processing easier
- if you need to split files (`split -n l/20 --numeric-suffixes input output`) -> splits into 20 parts

# Usage
## Build indexes
1. make clean
2. make all
3. chmod +x sortcheck.sh
4. chmod +x buildindex.sh
5. ./buildindex.sh "wordlist folder"

## Searching
```php
php multisearch.php "hashtype" "index folder" "dictionary folder" "hash"
or
php multisearch-files.php "hashtype" "index folder" "dictionary folder" "hash file"
```
example `php multisearch.php NTLM index dicts a4f49c406510bdcab6824ee7c30fd852`

example 2 `php multisearch-files.php NTLM index dicts hashes.txt`