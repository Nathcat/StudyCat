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
                    <textarea style="min-height: fit-content; height: 50vw; min-width: 50vw; width: 50%; font-size: 1.5em;" id="question-edit-content"></textarea>
                    <div id="question-preview" class="post-content" style="margin-left: 10px"></div>
                </div>

                <div class="column align-center justify-center">
                    <div class="row align-center justify-center">
                        <p>Select answer type:</p>
                        <select style="margin-left: 10px;" id="answer-type">
                            <option>Text entry</option>
                            <option>Multiple choice</option>
                        </select>
                    </div>

                    <div id="mcq-options" class="column align-center justify-center">
                        <div id="fields" class="column align-center justify-center"></div>

                        <button onclick="mcq_add_field()">Add option</button>
                    </div>

                    <div id="text-entry" class="column align-center justify-center">
                        <input id="text-answer" type="text" placeholder="Enter answer..." />
                    </div>
                </div>

                <div class="row align-center justify-center" style="width: 100%">
                    <button onclick="submit_question()" style="font-size: 2vw; padding: 25px;">Submit</button>
                    <span class="half-spacer"></span>
                    <button onclick="location = '/group/?id=' + group_id;" style="font-size: 2vw; padding: 25px;">Delete</button>
                </div>
            </div>

            <script>
                var converter = new showdown.Converter();
                let mcq_fields = [];

                let mcq_field = (i) => {
                    return "<div id='field-" + i + "' class='row align-center justify-center'><input type='checkbox' /><input type='text' placholder='Enter option...' /><button onclick='mcq_remove(" + i + ")'>Remove</button></div>";
                };

                let mcq_add_field = () => {
                    mcq_fields.push({
                        checked: false,
                        content: ""
                    });

                    mcq_update_fields();
                };

                let mcq_remove = (i) => {
                    mcq_fields.splice(i, 1);

                    mcq_update_fields();
                };

                let mcq_update_fields = () => {
                    let elem = $("#mcq-options #fields");
                    elem.empty();

                    for (let i = 0; i < mcq_fields.length; i++) {
                        elem.append(mcq_field(i));
                        $("#mcq-options #fields #field-" + i + " input[type='checkbox']")
                            .prop('checked', mcq_fields[i].checked)
                            .on("change", function(e) {
                                //mcq_fields[parseInt($(this).parent().attr("id").split("-")[1])].checked = $(this).is(":checked"); 
                                mcq_update_checks($(this).prop("checked"), parseInt($(this).parent().attr("id").split("-")[1]));
                            });

                        $("#mcq-options #fields #field-" + i + " input[type='text']").val(mcq_fields[i].content).on("change", function(e) {
                            mcq_fields[parseInt($(this).parent().attr("id").split("-")[1])].content = $(this).val();
                        });;
                    }
                };

                let mcq_update_checks = (v, i) => {
                    if (v) {
                        mcq_fields.forEach((e, x) => {
                            if (x !== i) e.checked = false;
                            else e.checked = true;
                        });
                    }
                    else {
                        mcq_fields[i].checked = false;
                    }

                    mcq_update_fields();
                }

                let submit_question = () => {
                    let isMcq = $("#answer-type").val() === "Multiple choice";
                    let mcqAnswer = -1;
                    mcq_fields.forEach((e, i) => {if (e.checked) mcqAnswer = i; });

                    if (mcqAnswer === -1 && mcq_fields.length !== 0) {
                        alert("You must select an answer!");
                        return;
                    }

                    studycat_create_question(
                        group_id,
                        $("#question-edit-content").val(),
                        isMcq ? "mcq" : "string",
                        isMcq ? mcqAnswer : $("#text-entry input").val(),
                        isMcq ? mcq_fields.map((f) => f.content) : null,
                        () => {
                            window.onbeforeunload = undefined;
                            location = '/group/?id=' + group_id;
                        },
                        alert
                    );
                };

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

                $("#text-entry").css("display", "flex");
                $("#mcq-options").css("display", "none");

                $("#answer-type").on("change", function(e) {
                    if ($(this).val() === "Text entry") {
                        $("#text-entry").css("display", "flex");
                        $("#mcq-options").css("display", "none");
                    } else {
                        $("#text-entry").css("display", "none");
                        $("#mcq-options").css("display", "flex");
                    }
                });
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