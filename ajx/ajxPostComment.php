<?php
require "../engine/sql.php";
require "../class/Comment.class.php";

$comment = new Comment($pdo, null, $_POST['postid'], $_POST['accountid'], $_POST['text'], null);
$mss = $comment->save();
echo json_encode($_POST['text']);
