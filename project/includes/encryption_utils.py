import random
import string
import sys


def generate_password(
    length=16,
    use_uppercase=True,
    use_lowercase=True,
    use_numbers=True,
    use_symbols=True,
):
    """
    Generates a strong password with the given length.
    Allows customization of character sets.
    """
    if length < 8:
        return "Error: Password length must be at least 8 characters."

    characters = ""
    if use_uppercase:
        characters += string.ascii_uppercase
    if use_lowercase:
        characters += string.ascii_lowercase
    if use_numbers:
        characters += string.digits
    if use_symbols:
        characters += "!@#$%^&*()_+"

    if not characters:
        return "Error: No character set selected."

    password = "".join(random.choice(characters) for _ in range(length))
    return password


if __name__ == "__main__":
    # Get the password length and character set options from the command-line arguments
    length = int(sys.argv[1]) if len(sys.argv) > 1 else 16
    use_uppercase = sys.argv[2].lower() == "true" if len(sys.argv) > 2 else True
    use_lowercase = sys.argv[3].lower() == "true" if len(sys.argv) > 3 else True
    use_numbers = sys.argv[4].lower() == "true" if len(sys.argv) > 4 else True
    use_symbols = sys.argv[5].lower() == "true" if len(sys.argv) > 5 else True

    print(
        generate_password(
            length, use_uppercase, use_lowercase, use_numbers, use_symbols
        )
    )
