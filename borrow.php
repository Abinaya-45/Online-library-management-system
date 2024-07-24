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

// Handle borrowing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    session_start();
    $user_id = $_SESSION['user_id']; // Assuming user is logged in and their ID is stored in session
    $book_id = $_POST['book_id'];
    $borrow_date = date('Y-m-d');
    $return_date = date('Y-m-d', strtotime('+14 days')); // Assuming a 14-day borrowing period

    // Check if the book is available
    $check_available = "SELECT * FROM books WHERE id = $book_id AND available > 0";
    $result = $conn->query($check_available);

    if ($result->num_rows > 0) {
        // Update book availability
        $update_book = "UPDATE books SET available = available - 1 WHERE id = $book_id";
        if ($conn->query($update_book) === TRUE) {
            // Record borrowing
            $record_borrowing = "INSERT INTO borrowings (user_id, book_id, borrow_date, return_date) 
                                VALUES ($user_id, $book_id, '$borrow_date', '$return_date')";
            if ($conn->query($record_borrowing) === TRUE) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error recording borrowing: " . $conn->error;
            }
        } else {
            echo "Error updating book availability: " . $conn->error;
        }
    } else {
        echo "Selected book is not available for borrowing.";
    }
}

$conn->close();
?>
