import sys

def remove_duplicates(input_file, output_file):
    try:
        with open(input_file, 'r') as infile:
            lines = infile.readlines()

        # Removing duplicates
        unique_lines = list(set(lines))

        with open(output_file, 'w') as outfile:
            outfile.writelines(unique_lines)

        print(f"Duplicates removed. Unique lines written to {output_file}")

    except FileNotFoundError:
        print(f"Error: File '{input_file}' not found.")
    except Exception as e:
        print(f"An error occurred: {str(e)}")


if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python remove_duplicates.py <input_file> <output_file>")
        sys.exit(1)

    input_file_path = sys.argv[1]
    output_file_path = sys.argv[2]

    remove_duplicates(input_file_path, output_file_path)
