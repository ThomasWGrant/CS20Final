<?php
$server = "localhost"; 
$userid = "upuhy74kupyur"; 
$pw = "TGmysql1231"; 
$db = "db8jmkswptdwip"; 

// Create connection
$conn = new mysqli($server, $userid, $pw);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

$conn->select_db($db);


$city = trim($_POST['city']); // gets names and trim spaces
$city = ucfirst(strtolower($city)); // all lowercase but first letter uppercase

// SQL query - check if city already exists
$stmt = $conn->prepare("SELECT count FROM cities WHERE name = ?");
$stmt->bind_param("s", $city);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {   // if city exiists: count +1
    $stmt = $conn->prepare("UPDATE cities SET count = count + 1 WHERE name = ?");
    $stmt->bind_param("s", $city);
    $stmt->execute();
    echo "Updated search count for $city.";
} else {    // if city does not exists: insert into database
    $stmt = $conn->prepare("INSERT INTO cities (name) VALUES (?)");
    $stmt->bind_param("s", $city);
    $stmt->execute();
    echo "Added new city: $city.";
}

// show resultss
$result = $conn->query("SELECT name, count FROM cities ORDER BY count DESC");

echo "<h2>City Search Counts</h2><ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($row['name']) . ": " . $row['count'] . "</li>";
}
echo "</ul>";


$conn->close();
?>

<br><a href="database.html">Search Again</a>