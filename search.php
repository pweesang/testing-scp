<?php
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
generateCSRFToken();
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php");
}

require_once(__DIR__ . '/controllers/connection.php');

$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$search_query = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$search = $_GET['search_query'] ?? ''; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <style>
        header {
            background-color: #333;
            display: block;
            color: #fff;
            padding: 1%;
            position:sticky;
            
            top:0px;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        li {
            display: inline;
            margin-right: 3%;
        }

        a {
            text-decoration: none;
            color: #fff;
        }
        #login{
            display: inline;
            margin-right : 3%;
        }
        /* footer{
            position:sticky;
            bottom:0px;
            background-color: white;
            color: #fff;
            padding: 0%;
        } */
        .footer{
            color:black;
            margin-left:3%;
            margin-right:3%;
            margin-top:90%;
        }
        hr{
            color:lightgrey;
            
        }
        .form-group{
            margin-top: 10px;
        }

    </style>
</head>
<body>
    <header>
    <?php if($_SESSION['is_admin'] === true){ ?>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about-us.php">About</a></li>
                <li><a href="admin.php">Admin Panel</a></li>
                <li><a href="search.php">Search</a></li>
                <div id='login'><a href='controllers/logoutc.php'>Logout</a></div>
            <!-- <li><a href="#">Services</a></li>-->
            </ul>
            <?php }else{ ?>
                <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about-us.php">About</a></li>
                <li><a href="report.php">Report</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="controllers/logoutc.php">Logout</a></li>
            <!-- <li><a href="#">Services</a></li>-->
            </ul>
            <?php } ?>
    </header>
    
    <div class="container">
        <div class="section search">
            <h2>Search</h2>
            <form action="search.php" method="GET">
                <input type="text" name="search_query" placeholder="Search..." value="<?php echo $search_query; ?>" required>
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
    $sql = "SELECT * FROM items WHERE item_name LIKE ? OR item_desc LIKE ?";
    $stmt = $connection->prepare($sql);

    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Item ID: " . $row["item_id"]. " - Name: " . $row["item_name"]. " - Description: " . $row["item_desc"]. " - Stock: " . $row["item_stock"] . "<br>";
        }
    } else {
        echo "0 results found";
    }

    $stmt->close();
    $connection->close();

?>