<!DOCTYPE html>
<html>
    <head>
        <title>StudyCat</title>

        <link rel="stylesheet" href="https://nathcat.net/static/css/new-common.css">
        <link rel="stylesheet" href="/static/styles/create-group.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="/static/scripts/db.js"></script>
    </head>

    <body>
        <div id="page-content" class="content">
            <?php include("../header.php"); ?>

            <div class="main">
                <div class="form-container">
                    <div class="text">
                        <a href="/">Return home</a>

                        <h2>Create a new study group</h2>
                        <p>
                            You can use this page to create a new study group.
                        </p>
                        <p>
                            Once you have created the group, you will be able to
                            add people to the group. You can choose people in
                            the group to allow to make questions. Every week,
                            the questions that allowed people in your group have
                            submitted will be released to everyone in the group
                            to answer!
                        </p>
                    </div>

                    <div class="form">
                        <input id="name-field" type="text" placeholder="Group name..." />
                        <button id="submit-button" onclick="studycat_create_group($('#name-field').val(), (id) => { location = '/group/?id=' + id; }, alert)">Create group</button>

                        <script>
                            $("#name-field").on("keydown", function(e) {
                                if (e.key === "Enter") {
                                    studycat_create_group($('#name-field').val(), (id) => { location = '/group/?id=' + id; }, alert);
                                }
                            })
                        </script>
                    </div>
                </div>
            </div>

            <?php include("../footer.php"); ?>
        </div>
    </body>
</html>