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

        <div id="main" class="main align-center justify-center">
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

    let user_search = (username) => {
        studycat_search_users(username, (results) => {
            if (results.length === 0) {
                $("#search-results").html("<p><i>No users found!</i></p>");
            } else {
                let html = "";
                for (const [key, value] of Object.entries(results)) {
                    html += "<div class='row align-center'><div class='small-profile-picture'><img src='https://cdn.nathcat.net/pfps/" + value.pfpPath + "' /></div><div class='column align-center justify-center' style='padding-left: 25px;'><h3>" + value.fullName + "</h3><h3><i>" + value.username + "</i></h3></div><button onclick='studycat_add_to_group(" + id + ", " + value.id + ", () => { location.reload(); }, alert)'>Add " + value.fullName + " to this group</button></div>";
                }

                $("#search-results").html(html);
            }

        }, alert);
    };


    studycat_get_chat_info(id, (group, members) => {
        $(".main #name").text(group.name);
        $(".main .profile-picture").html("<img src='https://cdn.nathcat.net/pfps/" + group.ownerPfpPath + "' />");
        $(".main #ownerFullName").text(group.ownerFullName);
        $(".main #ownerUsername").html("<i>" + group.ownerUsername + "</i>");

        studycat_check_if_admin(id, (isAdmin) => {
            for (let i = 0; i < members.length; i++) {
                document.getElementById("member-list").innerHTML += "<div class='row align-center'><div class='small-profile-picture'><img src='https://cdn.nathcat.net/pfps/" + members[i].pfpPath + "' /></div><h3 style='padding-left: 25px;'>" + members[i].fullName + "</h3>" + (members[i].admin ? "<h3 style='padding-left: 25px;'><i>Administrator</i></h3>" : "") + (isAdmin === 1 ? "<button onclick='studycat_toggle_admin(" + id + ", " + members[i].id + ", () => { location.reload(); }, alert)'>Toggle admin for " + members[i].fullName + "</button>" : "") + "</div>";
            }

            if (isAdmin === 1) {
                document.getElementById("main").innerHTML += "<div class='column content-card'><h2>Add users to group</h2><input id='user-search-field' type='text' placeholder='Enter username...'><button onclick='user_search($(\"#user-search-field\").val())'>Search</button><div id='search-results' class='column'></div></div>";
                $("#user-search-field").on("keydown", function(e) {
                    if (e.key === "Enter") {
                        user_search($(this).val());
                    }
                });
            }
        }, alert);
    }, alert);
</script>

</html>