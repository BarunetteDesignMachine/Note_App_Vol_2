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
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE notes SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
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
    <title>Edit Note</title>
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
            <h3>Edit Note</h3>
            <form action="edit.php?id=<?php echo $note['id']; ?>" method="post">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($note['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($note['content']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>