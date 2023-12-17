<?php
require "config.php";

if (empty($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$userEmail = $_SESSION["email"];


$checkCompanyQuery = "SELECT c.*, s.Student_ID 
                      FROM company_registration c
                      INNER JOIN student_registration s ON c.Student_ID = s.Student_ID
                      WHERE s.Student_email = '$userEmail'";

$resultCompany = mysqli_query($conn, $checkCompanyQuery);

$hasCompany = mysqli_num_rows($resultCompany) > 0;
$companyName = "";
$companyLogo = "";

if ($hasCompany) {
    $row = mysqli_fetch_assoc($resultCompany);
    $companyName = $row["Company_name"];
    $companyLogo = $row["Company_logo"]; 

    header("Location: company.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Company - Launchpad</title>
    <link rel="icon" href="/launchpad/images/favicon.svg" />
        <link rel="stylesheet" href="css/navbar.css">

       
</head>
<body>
    
    


<aside class="sidebar">
            <header class="sidebar-header">
                <img src="\launchpad\images\logo-text.svg" class="logo-img">
            </header>

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
                        <i ><img src="\launchpad\images\project-checker-icon.png" alt="home-logo" class="logo-ic"></i>
                        <span>Project Idea Checker</span>
                    </span>
                </button>
    </a>
    <a href="invitations.php">
                <button>
                    <span>
                        <i ><img src="\launchpad\images\invitation-icon.png" alt="home-logo" class="logo-ic"></i>
                        <span>Invitations</span>
                    </span>
                </button>
    </a>
                <p class="divider-company">YOUR COMPANY</p>
                         
                

                <a href="<?php echo $hasCompany ? 'company.php' : 'create-company.php'; ?>" class="active">
        <button>
            <span class="<?php echo $hasCompany ? 'btn-company-created' : 'btn-create-company'; ?>">
                <div class="circle-avatar">
                    <?php if ($hasCompany && !empty($companyLogo)): ?>
                        <img src="\launchpad\<?php echo $companyLogo; ?>" alt="Company Logo" class="img-company">
                    <?php else: ?>
                        <img src="\launchpad\images\join-company-icon.png" alt="Join Company Icon">
                    <?php endif; ?>
                </div>
                <span class="create-company-text">
                    <?php echo $hasCompany ? $companyName : 'Create your company'; ?>
                </span>
            </span>
        </button>
    </a>





                <p class="divider-company">COMPANIES YOU'VE JOINED</p>
                <a href="#">
                <button>
                    <span  class="btn-join-company">
                        <i > <div class="circle-avatar">
                            <img src="\launchpad\images\join-company-icon.png" alt="">
                        </div></i>
                        <span class="join-company-text">Join companies</span>
                    </span>
                </button>
                </a>
<a href="profile.php">
                <button>
                    <span>
                        <img src="logo.png" alt="">
                        <span>Profile</span>
                    </span>
                </button>
</a>
               
            </nav>


        </aside>











<div class="content">
    <h2>Company Information Form</h2>

    <form action="backend.php" method="post" enctype="multipart/form-data">
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" required>

        <br><br>

        <label for="company_description">Company Description:</label>
        <textarea id="company_description" name="company_description" rows="4" required></textarea>

        <br><br>

        <label for="company_logo">Company Logo:</label>
        <input type="file" id="company_logo" name="company_logo" accept="image/*" required>

        <br><br>

        <button type="submit">Submit</button>
    </form>
    </div>
</body>
</html>