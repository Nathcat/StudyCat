<!DOCTYPE html>
<html>

<head>
    <title>StudyCat</title>

    <link rel="stylesheet" href="https://nathcat.net/static/css/new-common.css">
    <link rel="stylesheet" href="https://blog.nathcat.net/static/styles/home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/showdown@2.1.0/dist/showdown.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script src="/static/scripts/db.js"></script>

    <style>
        mjx-container {
            overflow-x: scroll;
            max-width: 90vw;
            overflow-y: hidden;
            border: 2px solid var(--quad-color);
            border-radius: 25px;
            padding: 25px;
            color: var(--quad-color);
        }
    </style>
</head>

<body>
    <div class="content">
        <?php include("../header.php"); ?>

        <div id="main" class="main">
            <div class="main column align-center" style="margin-bottom: 50px">
                <div class="row align-center justify-center" style="width: 100%; height: 100%; margin-bottom: 50px;">
                    <div id="question-content" class="post-content" style="margin-left: 10px"></div>
                </div>


                <div class="row align-center justify-center" style="width: 100%">
                    <button onclick="submit_question()" style="font-size: 2vw; padding: 25px;">Submit</button>
                </div>
            </div>

            <script>
                var converter = new showdown.Converter();
                

                let submit_question = () => {
                    
                };

                var ask_before_unload = function() {
                    if ($("#question-edit-content").val() !== "") {
                        return "Any progress in this quiz will be lost, are you sure you want to continue?";
                    }
                };

                window.onbeforeunload = ask_before_unload;

                
            </script>
        </div>

        <?php include("../footer.php"); ?>
    </div>
</body>

<script>
    const searchParams = new URLSearchParams(window.location.search);

    if (!searchParams.has("id")) {
        location = "/";
    }

    var group_id = searchParams.get("id");
</script>

</html>