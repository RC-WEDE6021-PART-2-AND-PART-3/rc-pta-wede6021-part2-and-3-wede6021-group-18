<?php
// ==========================================
// LOAD DATABASE SCRIPT
// ==========================================

// Include DB connection
include 'DBConn.php';

// Read SQL file
$sqlFile = file_get_contents("myClothingStore.sql");

// Execute multiple queries
if ($conn->multi_query($sqlFile)) {

    do {
        // Store result
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());

    echo "Database successfully loaded!";
} else {
    echo "Error loading database: " . $conn->error;
}
?>