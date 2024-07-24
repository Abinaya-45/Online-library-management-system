<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Library Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .borrow-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Online Library Management System</h2>
        
        <!-- Form to add a new book -->
        <h3>Add New Book</h3>
        <form action="index.php" method="post">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br><br>
            <label for="author">Author:</label><br>
            <input type="text" id="author" name="author" required><br><br>
            <label for="category">Category:</label><br>
            <input type="text" id="category" name="category"><br><br>
            <label for="isbn">ISBN:</label><br>
            <input type="text" id="isbn" name="isbn"><br><br>
            <label for="quantity">Quantity:</label><br>
            <input type="number" id="quantity" name="quantity" min="1" required><br><br>
            <input type="submit" value="Add Book">
        </form>

        <!-- Display available books -->
        <h3>Available Books</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>ISBN</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
            <?php
            // Database connection
            $host = 'localhost';  // Database host
            $user = 'root';       // Database username
            $password = '';       // Database password
            $dbname = 'library_system'; // Database name

            $conn = new mysqli($host, $user, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle form submission to add a new book
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $title = $_POST['title'];
                $author = $_POST['author'];
                $category = $_POST['category'];
                $isbn = $_POST['isbn'];
                $quantity = $_POST['quantity'];

                $sql = "INSERT INTO books (title, author, category, isbn, quantity, available) 
                        VALUES ('$title', '$author', '$category', '$isbn', $quantity, $quantity)";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Book added successfully!</p>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            // Display available books
            $sql = "SELECT * FROM books WHERE available > 0 ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                    echo "<td>" . $row['available'] . "</td>";
                    echo '<td><form action="borrow.php" method="post">
                              <input type="hidden" name="book_id" value="' . $row['id'] . '">
                              <button type="submit" class="borrow-btn">Borrow</button>
                          </form></td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No books available</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
