#!/bin/bash
# Check if the folder path is provided as a command line argument
if [ "$#" -ne 1 ]; then
    echo "Usage: $0 <folder_path>"
    exit 1
fi

# Get the folder path from the command line argument
folder_path="$1"

# Check if the folder exists
if [ ! -d "$folder_path" ]; then
    echo "Folder does not exist: $folder_path"
    exit 1
fi

# Loop through each file in the folder
for file in "$folder_path"/*; do
    if [ -f "$file" ]; then
        echo "Processing file: $file"

        # Run the first command
        ./sortidx "$file"

        # Run the second command
        ./checksort "$file"

        echo "Done processing file: $file"
        echo
    fi
done

echo "Script completed."
