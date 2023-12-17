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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hasCompany && !empty($companyName) ? $companyName." - Launchpad" : 'Create Company - Launchpad'; ?></title> 
    <link rel="icon" href="/launchpad/images/favicon.ico" id="favicon">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <style>
        .color-selected{
            color: green;
            font-weight: 700;
        }
    </style>
    <script>
        function changeFavicon(url) {
            const favicon = document.getElementById('favicon');
            favicon.href = url;
        }
        <?php if ($hasCompany && !empty($companyLogo)): ?>
            const companyLogoUrl = "/launchpad/<?php echo $companyLogo; ?>";
            changeFavicon(companyLogoUrl);
        <?php endif; ?>
    </script>
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
    <a href="invitations.php" >
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
    <h2>Create Project</h2>


    <form method="post" action="process_create_project.php" enctype="multipart/form-data">
      
        <label for="projectName">Project Name:</label>
        <input type="text" id="projectName" name="projectName" required><br><br>

        <label for="projectDescription">Project Description:</label>
        <textarea id="projectDescription" name="projectDescription" required></textarea><br><br><br>

        <label for="projectLogo">Project Logo: </label>
        <input type="file" name="projectLogo" required>
<hr><br>
      
        <label for="memberSearch">Add Members:</label><br><br>
        <input type="text" id="memberSearch" oninput="searchMembers(this.value)"><br>
        <div id="memberResults" class="search-results"></div><br>
        <h4>Selected Members</h4>
 
        <div id="selectedMembers" class="color-selected">
            
          
        </div><br><br>
<hr><br>
    
        <label for="mentorSearch">Add Mentor:</label><br><br>
        <input type="text" id="mentorSearch" oninput="searchMentors(this.value)"><br>
        <div id="mentorResults" class="search-results"></div><br>
        <h4>Selected Mentor</h4>
       
        <div id="selectedMentor"  class="color-selected">
        
        </div><br>

     
        <button type="submit">Create Project</button>
    </form>
</div>

<script>
    function searchMembers(query) {
        $.ajax({
            url: 'search_members.php',
            type: 'POST',
            data: { query: query },
            success: function (data) {
                $('#memberResults').html(data);
                attachClickHandlers('member');
            }
        });
    }

    function searchMentors(query) {
        $.ajax({
            url: 'search_mentors.php',
            type: 'POST',
            data: { query: query },
            success: function (data) {
                $('#mentorResults').html(data);
                attachClickHandlers('mentor');
            }
        });
    }

    function attachClickHandlers(type) {
        $(`.search-results .${type}-result`).click(function () {
            const id = $(this).data('id');
            const name = $(this).text(); 

            if (type === 'member') {
                addMember(id, name);
            } else {
                addMentor(id, name);
            }
        });
    }

    function addMember(studentID, studentName) {
        if ($('#selectedMembers').find(`[data-id="${studentID}"]`).length === 0) {
            $('#selectedMembers').append(`<div data-id="${studentID}">${studentName} <span onclick="removeMember('${studentID}')">x</span></div>`);
        }
    }

    function addMentor(mentorID, mentorName) {
        $('#selectedMentor').html(`<div data-id="${mentorID}">${mentorName} <span onclick="removeMentor('${mentorID}')">x</span></div>`);
    }

    function removeMember(studentID) {
        $(`#selectedMembers [data-id="${studentID}"]`).remove();
    }

    function removeMentor(mentorID) {
        $('#selectedMentor').empty();
    }
</script>
</body>
</html>