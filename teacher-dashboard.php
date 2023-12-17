<?php
    require "config.php";

    if (empty($_SESSION["email"])) {
        header("Location: login.php");
        exit(); 
    }
    $instructorEmail = $_SESSION["email"];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
    <title>Teacher</title>
    <style media="screen">
      embed{
        border: 2px solid black;
        margin-top: 30px;
      }
      .div1{
        margin-left: 170px;
      }
    </style>
</head>
<body>
<aside class="sidebar">
            <header class="sidebar-header">
                <img src="\launchpad\images\logo-text.svg" class="logo-img">
            </header>
            <hr>
            <nav>
                <a href="index.php" >
                <button>
                    <span>
                        <i ><img src="\launchpad\images\home-icon.png" alt="home-logo" class="logo-ic"></i>
                        <span>Home</span>
                    </span>
                </button>
            </a>
            <a href="project-idea-checker.php">
                <button>
                    <span>
                        <i ><img src="\launchpad\images\evaluation_img.png" alt="home-logo" class="logo-ic"></i>
                        <span>Evaluation</span>
                    </span>
                </button>
            </a>
            <br><br><br><br><p>My companies</p>
            <a href="">
                <button>
                    <span>
                        <?php
                        echo $instructorEmail;
                        ?>
                    </span>
                </button>
            </a>
        </nav>
    </aside>
    <div class="content">
        <div class="div1">
            <?php

            $sql = "SELECT * FROM ideation_phase";
            $query = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($query)) {
            ?>
                <embed type="application/pdf" src="<?php echo $row['Project_Modelcanvas']; ?>" width="800" height="500">
            <?php
            }
            mysqli_free_result($query);

            mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>