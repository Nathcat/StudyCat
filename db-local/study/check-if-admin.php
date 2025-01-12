<?php
include("../start-session.php");

header("Content-Type: application/json");
header("Accept: application/json");
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");

if (!array_key_exists("user", $_SESSION)) {
    die("{\"status\": \"fail\", \"message\": \"Not logged in.\"}");
}

$r = json_decode(file_get_contents("php://input"), true);

if (!array_key_exists("groupId", $r)) {
    exit(json_encode([
        "status" => "fail",
        "message" => "Please specify the id of the group."
    ]));
}

$is_admin = 0;
$conn = new mysqli("localhost:3306", "study", "", "StudyCat");

if ($conn->connect_error) {
    die("{\"status\": \"fail\", \"message\": \"Failed to connect to the database: " . $conn->connect_error . "\"}");
}

try {
    mysqli_report(MYSQLI_REPORT_ALL);

    $stmt = $conn->prepare("SELECT `owner` FROM `groups` WHERE `id` = ?");
    $stmt->bind_param("i", $r["groupId"]);
    $stmt->execute();

    $owner = $stmt->get_result()->fetch_assoc()["owner"];
    if ($owner == $_SESSION["user"]["id"]) {
        $is_admin = 1;
    }

    $stmt->close();

} catch (Exception $e) {
    $conn->close();
    die("{\"status\": \"fail\", \"message\": \"$e\"}");
}

try {
    mysqli_report(MYSQLI_REPORT_ALL);

    $stmt = $conn->prepare("SELECT `admin` FROM `GroupMembers` JOIN SSO.Users ON `user` = SSO.Users.id WHERE `group` = ? AND `user` = ?");
    $stmt->bind_param("ii", $r["groupId"], $_SESSION["user"]["id"]);
    $stmt->execute();

    $set = $stmt->get_result();
    while ($row = $set->fetch_assoc()) {
        if ($row[`admin`] == 1) $is_admin = 1;
    }

    $stmt->close();

} catch (Exception $e) {
    $conn->close();
    die("{\"status\": \"fail\", \"message\": \"$e\"}");
}

echo json_encode([
    "status" => "success",
    "isAdmin" => $is_admin
]);

$conn->close();

?>