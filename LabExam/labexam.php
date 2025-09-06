<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Catalog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        form {
            margin: 20px;
        }
        table {
            width: 85%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        a.delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
        a.delete-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>📚 Library Catalog</h2>
    <p><a href="addbook.php">➕ Add New Book</a></p>

    <!-- Search Form -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by Title, Author, or ISBN" 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <input type="submit" value="Search">
        <a href="labexam.php">Reset</a>
    </form>

    <?php
    // Database connection
    $conn = new mysqli("db", "root", "rootpassword", "books");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ✅ Handle Delete Action
    if (isset($_GET['delete'])) {
        $delete_id = intval($_GET['delete']);
        $conn->query("DELETE FROM books WHERE id=$delete_id");

        // ✅ Reset auto-increment if table becomes empty
        $check = $conn->query("SELECT COUNT(*) as total FROM books");
        $row = $check->fetch_assoc();
        if ($row['total'] == 0) {
            $conn->query("ALTER TABLE books AUTO_INCREMENT = 1");
        }

        echo "<p style='color:red;'>Book deleted successfully!</p>";
    }

    // ✅ Handle Search
    $search = "";
    if (isset($_GET['search']) && $_GET['search'] != "") {
        $search = $conn->real_escape_string($_GET['search']);
        $sql = "SELECT * FROM books 
                WHERE title LIKE '%$search%' 
                   OR author LIKE '%$search%' 
                   OR isbn LIKE '%$search%'";
    } else {
        $sql = "SELECT * FROM books";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>ISBN</th>
                    <th>Action</th>
                </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row["id"]."</td>
                    <td>".$row["title"]."</td>
                    <td>".$row["author"]."</td>
                    <td>".$row["year"]."</td>
                    <td>".$row["ISBN"]."</td>
                    <td><a class='delete-btn' href='labexam.php?delete=".$row["id"]."' 
                           onclick=\"return confirm('Are you sure you want to delete this book?');\">Delete</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No books available in the catalog.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
