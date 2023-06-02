<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'database.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM notes WHERE id = :id AND user_id = :user_id");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$note = $stmt->fetch();

if (!$note) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("DELETE FROM notes WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Note</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style type="text/css">
        body {
            background-color: #f7f7f7;
        }
        .note-form {
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 note-form">
            <h3>Delete Note</h3>
            <p>Are you sure you want to delete this note?</p>
            <form action="delete.php?id=<?php echo $note['id']; ?>" method="post">
                <button type="submit" class="btn btn-danger">Delete</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>