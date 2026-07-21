<!-- 
 Credits: 
 Creators: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
 Programmed/Written by: Nathaniel P. Solivio
 Tested by: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
 Design by: Nathaniel P. Solivio, Jae Marianne Almazan and Hayya Michaela Cajuday
-->
<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'qcsim_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
