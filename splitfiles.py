import os
import sys

def split_file(input_file, num_parts, encoding='utf-8'):
    # Check if the file exists
    if not os.path.exists(input_file):
        print(f"Error: File '{input_file}' not found.")
        return

    # Calculate the number of lines in the file
    with open(input_file, 'rb') as file:
        lines = [line.decode(encoding, errors='replace') for line in file.readlines()]
        num_lines = len(lines)

    # Calculate the number of lines per part and the remainder
    lines_per_part, remainder = divmod(num_lines, num_parts)

    # Create output directory
    output_dir = "dicts"
    os.makedirs(output_dir, exist_ok=True)

    # Split the file into parts
    start_idx = 0
    for part_num in range(1, num_parts + 1):
        end_idx = start_idx + lines_per_part + (1 if part_num <= remainder else 0)
        part_file_name = f"{os.path.splitext(os.path.basename(input_file))[0]}-{part_num}.txt"
        part_file = os.path.join(output_dir, part_file_name)

        with open(part_file, 'w', encoding=encoding) as part:
            part.writelines(lines[start_idx:end_idx])

        start_idx = end_idx

    print(f"File '{input_file}' has been split into {num_parts} parts in '{output_dir}'.")

if __name__ == "__main__":
    # Check if correct number of command line arguments is provided
    if len(sys.argv) != 3:
        print("Usage: python script.py <input_file> <num_parts>")
        sys.exit(1)

    # Get input file and number of parts from command line arguments
    input_file = sys.argv[1]
    num_parts = int(sys.argv[2])

    # Call the function to split the file
    split_file(input_file, num_parts)
