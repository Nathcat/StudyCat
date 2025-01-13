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
        #question-preview {
            margin-right: 25px;
            margin-left: 25px;
            width: 100%;
        }

        #question-preview * {
            text-wrap: wrap;
        }

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
                    <textarea style="min-height: fit-content; height: 50vw; min-width: 50vw; width: 100%; font-size: 1.5em;" id="question-edit-content"></textarea>
                    <div id="question-preview" class="post-content" style="margin-left: 10px"></div>
                </div>

                <div class="row align-center justify-center" style="width: 100%">
                    <button onclick="console.log('submit');" style="font-size: 2vw; padding: 25px;">Submit</button>
                    <span class="half-spacer"></span>
                    <button onclick="location = '/group/?id=' + group_id;" style="font-size: 2vw; padding: 25px;">Delete</button>
                </div>
            </div>

            <script>
                var converter = new showdown.Converter();

                $("#question-edit-content").on("input", function(e) {
                    let content = $(this).val();
                    content = content.split("$$");
                    for (let i = 0; i < content.length; i += 2) {
                        content[i] = converter.makeHtml(content[i]);
                    }

                    content = content.join("$$");

                    document.getElementById("question-preview").innerHTML = content;
                    MathJax.typeset();

                    $(".post-content a").each(function() {
                        $(this).attr("target", "_blank");
                    });
                });

                var ask_before_unload = function() {
                    if ($("#question-edit-content").val() !== "") {
                        return "This question will be lost if you close this tab, are you sure you want to continue?";
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