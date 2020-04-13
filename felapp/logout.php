<?php
session_start();
session_destroy();
echo "<h3>You have been logged out successfully!</h3>";
echo "<a href=\"index.php\">Home</a>";
?>
