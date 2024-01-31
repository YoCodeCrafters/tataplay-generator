<?php
unlink('secure/_sessionData');
http_response_code(307);
header("Location: index.php");
exit;
?>