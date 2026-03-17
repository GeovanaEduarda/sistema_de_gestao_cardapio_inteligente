<?php

/* You should enable error reporting for mysqli before attempting to make a connection */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = mysqli_connect('localhost', 'braien', '1234', 'cardapio');

/* Set the desired charset after establishing a connection */
mysqli_set_charset($conn, 'utf8mb4');