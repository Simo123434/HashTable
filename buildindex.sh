#!/bin/bash
# Build the index files for each file in the given directory

# Check for the correct number of arguments
# should be 1: the folder that contains the wordlists

if [ $# -ne 1 ]; then
  echo "Usage: $0 <wordlist folder>"
  exit 1
fi

# Check if the folder exists and containts files
if [ ! -d $1 ]; then
  echo "Error: $1 is not a directory"
  exit 1
fi

# call the multicreateidx.php with the folder as argument and the second argumnet "./index"
 php multicreateidx.php $1 ./index

# run the sortcheck.sh on the index folder
for file in "index"/*; do
    if [ -f "$file" ]; then
        echo "Processing file: $file"

        # Run the first command
        ./sortidx -r 2048 "$file"

        # Run the second command
        ./checksort "$file"

        echo "Done processing file: $file"
        echo
    fi
done

# echo that all index files have been created and sorted.
echo "All index files have been created and sorted."



