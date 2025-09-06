<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        form {
            margin: 20px auto;
            width: 300px;
            display: flex;
            flex-direction: column;
        }
        input {
            margin: 5px 0;
            padding: 8px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>➕ Add a New Book</h2>

    <form method="POST" action="">
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="number" name="year" placeholder="Publication Year" min="1000" max="2025"  required>
        <input type="text" name="isbn" placeholder="ISBN" required>
        <input type="submit" value="Add Book">
    </form>

    <?php
    // Database connection
    $conn = new mysqli("db", "root", "rootpassword", "books");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $conn->real_escape_string($_POST['title']);
        $author = $conn->real_escape_string($_POST['author']);
        $year = intval($_POST['year']);
        $isbn = $conn->real_escape_string($_POST['isbn']);

        $sql = "INSERT INTO books (title, author, year, isbn) 
                VALUES ('$title', '$author', '$year', '$isbn')";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Book added successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }

    $conn->close();
    ?>

    <p><a href="labexam.php">📚 Go to Catalog</a></p>
</body>
</html>
