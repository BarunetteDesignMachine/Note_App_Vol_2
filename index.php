<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'database.php';

$stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$notes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notes</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style type="text/css">
        body {
            background-color: #f7f7f7;
        }
        .note-card {
            margin-bottom: 20px;
        }
        .note-card .card-body {
            min-height: 100px;
        }
        .note-card .card-footer {
            text-align: right;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Notes App</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="create.php">New Note</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php foreach ($notes as $note): ?>
                <div class="card note-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($note['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($note['content']); ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="edit.php?id=<?php echo $note['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="delete.php?id=<?php echo $note['id']; ?>" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>