<!DOCTYPE html>
<html>
    <head>
        <title>StudyCat</title>

        <link rel="stylesheet" href="https://nathcat.net/static/css/new-common.css">
        <link rel="stylesheet" href="/static/styles/home.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="/static/scripts/db.js"></script>
    </head>

    <body>
        <div id="page-content" class="content">
            <?php include("header.php"); ?>

            <div class="main">
                <div class="user">
                    <div class="profile-picture">
                        <img src="https://cdn.nathcat.net/pfps/<?php echo $_SESSION["user"]["pfpPath"]; ?>">
                    </div>

                    <p><i><b>Logged in as...</b></i></p>

                    <h2><?php echo $_SESSION["user"]["fullName"]; ?></h2>
                    <h3><i><?php echo $_SESSION["user"]["username"]; ?></i></h3>
                </div>

                <div class="groups">
                    <h2>Your study groups</h2>
                    <div id="groups-container" class="container"></div>
                </div>
            </div>

            <?php include("footer.php"); ?>
        </div>
    </body>

    <script>
        studycat_get_all_groups((groups, ownedGroups) => {
            let html = "";
            let g_html = (g) => { return "<div class='content-card' onclick='location = \"/group/?id=" + g.id + "\"'><h3>" + g.name + "</h3></div>"; }
            
            for (let i = 0; i < groups.length; i++) {
                html += g_html(groups[i]);
            }

            for (let i = 0; i < ownedGroups.length; i++) {
                html += g_html(ownedGroups[i]);
            }

            document.getElementById("groups-container").innerHTML = html;
        }, alert);
    </script>
</html>