<!DOCTYPE html>
<html>
    <head>
        <title>StudyCat</title>

        <link rel="stylesheet" href="https://nathcat.net/static/css/new-common.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="/static/scripts/db.js"></script>
    </head>

    <body>
        <div class="content">
            <?php include("../header.php"); ?>

            <div class="main align-center justify-center">
                <h1 id="name"></h1>
                <h2>Created by</h2>
                <div class="profile-picture"></div>
                <h2 id="ownerFullName"></h2>
                <h3 id="ownerUsername"></h3>

                <div class="content-card">
                    <h2>Group Members</h2>
                    <div id="member-list" class="column align-center"></div>
                </div>
            </div>

            <?php include("../footer.php"); ?>
        </div>
    </body>

    <script>
        const searchParams = new URLSearchParams(window.location.search);

        if (!searchParams.has("id")) {
            location = "/";
        }
        
        let id = searchParams.get("id");

        studycat_get_chat_info(id, (group, members) => {
            $(".main #name").text(group.name);
            $(".main .profile-picture").html("<img src='https://cdn.nathcat.net/pfps/" + group.ownerPfpPath + "' />");
            $(".main #ownerFullName").text(group.ownerFullName);
            $(".main #ownerUsername").html("<i>" + group.ownerUsername + "</i>");

            for (let i = 0; i < members.length; i++) {
                document.getElementById("member-list").innerHTML += "<div class='row align-center'><div class='small-profile-picture'><img src='https://cdn.nathcat.net/pfps/" + members[i].pfpPath + "' /></div><h3 style='padding-left: 25px;'>" + members[i].fullName + "</h3>" + (members[i].admin ? "<h3 style='padding-left: 25px;'><i>Administrator</i></h3>" : "") + "</div>";
            }
        }, alert);
    </script>
</html>