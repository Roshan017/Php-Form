<?php

$dataFile = 'users.json';

function deleteUser($roll) {
    global $dataFile;
    if (file_exists($dataFile)) {
        $jsonData = file_get_contents($dataFile);
        $users = json_decode($jsonData, true);
        
        $users = array_filter($users, function($user) use ($roll) {
            return $user['roll'] !== $roll;
        });
        
        $users = array_values($users);
        file_put_contents($dataFile, json_encode($users));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $rollToDelete = htmlspecialchars($_POST['delete']);
        deleteUser($rollToDelete);
    } else {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $roll = htmlspecialchars($_POST['roll']);
        
        if (file_exists($dataFile)) {
            $jsonData = file_get_contents($dataFile);
            $users = json_decode($jsonData, true);
        } else {
            $users = [];
        }

        $users[] = ['name' => $name, 'email' => $email, 'roll' => $roll];

        file_put_contents($dataFile, json_encode($users));
    }
}

$usersData = '';
if (file_exists($dataFile)) {
    $jsonData = file_get_contents($dataFile);
    $users = json_decode($jsonData, true);

    $usersData .= "<h3>Users List:</h3><div class='users-list'>";
    foreach ($users as $user) {
        $usersData .= "<div class='user'><strong>Name:</strong> " . $user['name'] . "<br>";
        $usersData .= "<strong>Roll No:</strong> " . $user['roll'] . "<br>";
        $usersData .= "<strong>Email:</strong> " . $user['email'] . "<br>";
        $usersData .= "<form method='POST' style='display:inline;'>
                        <input type='hidden' name='delete' value='" . $user['roll'] . "'>
                        <input type='submit' value='Delete'>
                       </form><br></div>";
    }
    $usersData .= "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic PHP Form</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container { width: 50%; margin: auto; }
        .users-list { margin-top: 20px; }
        .user { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        .user strong { display: block; }
    </style>
</head>
<body>

<div class="form-container">
    <form method="POST">
        Name: <input type="text" name="name" required>
        Roll No: <input type="text" name="roll" required>
        Email: <input type="email" name="email" required>
        <input type="submit" value="Submit">
    </form>
    
    <button id="toggleButton">Display Data</button>

    <div id="userData">
        <?php echo $usersData; ?>
    </div>
</div>

<script>
    document.getElementById("toggleButton").addEventListener("click", function() {
        var userDataDiv = document.getElementById("userData");
        if (userDataDiv.style.display === "none" || userDataDiv.style.display === "") {
            userDataDiv.style.display = "block";  
            this.textContent = "Hide Data";  
        } else {
            userDataDiv.style.display = "none";  
            this.textContent = "Display Data";  
        }
    });
</script>

</body>
</html>
