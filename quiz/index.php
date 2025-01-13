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
            padding: 25px;
            color: var(--quad-color);
        }

        .mcq-option input[type='checkbox'] {
            height: 30px;
            aspect-ratio: 1;
        }

        .content-card {
            width: 75%;
        }
    </style>
</head>

<body>
    <div class="content">
        <?php include("../header.php"); ?>


        <div id="main" class="main column align-center" style="margin-bottom: 50px">
            <a href="/">Return home</a>

            <div class="row align-center" style="width: 100%; height: 100%; margin-bottom: 50px;">
                <div id="question-content" class="post-content"></div>
            </div>

            <div id="text-entry" class="column align-center justify-center">
                <input type="text" placeholder="Enter answer..." />
            </div>

            <div style="display: none;" id="mcq-options" class="column align-center justify-center">

            </div>

            <div class="row align-center justify-center" style="width: 100%">
                <button onclick="next_question()" style="font-size: 2vw; padding: 25px;">Submit answer</button>
            </div>
        </div>

        <script>
            const searchParams = new URLSearchParams(window.location.search);

            if (!searchParams.has("id")) {
                location = "/";
            }

            var group_id = searchParams.get("id");

            var converter = new showdown.Converter();
            var current_question = 0;
            var questions;
            var isMcq;
            var answers = [];
            var mcq_fields;

            let mcq_field = (i) => {
                return "<div id='field-" + i + "' class='mcq-option row align-center justify-center'><input type='checkbox' /><div class='content-card'></div></div>";
            };

            let mcq_update_fields = () => {
                let elem = $("#mcq-options");
                elem.empty();

                for (let i = 0; i < mcq_fields.length; i++) {
                    elem.append(mcq_field(i));
                    $("#mcq-options #field-" + i + " input[type='checkbox']")
                        .prop('checked', mcq_fields[i].checked)
                        .on("change", function(e) {
                            //mcq_fields[parseInt($(this).parent().attr("id").split("-")[1])].checked = $(this).is(":checked"); 
                            mcq_update_checks($(this).prop("checked"), parseInt($(this).parent().attr("id").split("-")[1]));
                        });

                    $("#mcq-options #field-" + i + " .content-card").html(mcq_fields[i].content);
                }
            };

            let mcq_update_checks = (v, i) => {
                if (v) {
                    mcq_fields.forEach((e, x) => {
                        if (x !== i) e.checked = false;
                        else e.checked = true;
                    });
                } else {
                    mcq_fields[i].checked = false;
                }

                mcq_update_fields();
            }

            let next_question = () => {
                if (isMcq) {
                    let mcqAnswer = -1;
                    mcq_fields.forEach((e, i) => {
                        if (e.checked) mcqAnswer = i;
                    });

                    if (mcqAnswer === -1) {
                        if (!confirm("You have not entered an answer, continue?")) return;
                    }

                    answers.push(mcqAnswer);
                } else {
                    if ($("#text-entry input").val() === "") {
                        if (!confirm("You have not entered an answer, continue?")) return;
                    }

                    answers.push($("#text-entry input").val());
                }

                if (current_question === questions.length - 1) {
                    let elem = $("#main");
                    elem.empty();

                    let num_correct = 0;

                    for (let i = 0; i < questions.length; i++) {
                        let correct = false;
                        let correctAnswer;
                        let yourAnswer;
                        if (questions[i].correctMCQAnswer === null) {
                            correct = answers[i] === questions[i].answerString;
                            correctAnswer = questions[i].answerString;
                            yourAnswer = answers[i];
                        }
                        else {
                            correct = answers[i] === questions[i].correctMCQAnswer;
                            
                            questions[i].mcqOptions.forEach((e, x) => {
                                if (x === questions[i].correctMCQAnswer) correctAnswer = e.content;
                                if (x === answers[i]) yourAnswer = e.content;
                            });
                        }

                        if (correct) num_correct++;

                        elem.append("<div class='content-card'><h2>Question " + (i + 1) + "</h2>" + (correct ? "<h3 style='color: #00FF00'>Correct</h3>" : "<h3 style='color: #FF0000'>Inorrect</h3><p>Correct answer: '" + correctAnswer + "'</p><p>Your answer: '" + yourAnswer + "'</p>") + "</div>");
                    }

                    elem.append("<div class='content-card'><h2>Final score</h2>$$\\frac{" + num_correct + "}{" + questions.length + "}\\textrm{, }" + ((num_correct / questions.length) * 100) + "\\textrm{%}$$</div>");
                    MathJax.typeset();

                    window.onbeforeunload = () => {};
                    studycat_submit_score(group_id, (num_correct / questions.length) * 100, () => console.log("Score submitted!"), alert);

                } else {
                    current_question++;
                    render_question();
                }
            };

            let render_question = () => {
                let q = questions[current_question];
                let content = q.content.split("$$");
                for (let i = 0; i < content.length; i += 2) {
                    content[i] = converter.makeHtml(content[i]);
                }


                $("#question-content").html(content.join("$$"));

                if (q.correctMCQAnswer !== null) {
                    isMcq = true;
                    $("#text-entry").css("display", "none");
                    $("#mcq-options").css("display", "flex");

                    mcq_fields = q["mcqOptions"].map((e) => {
                        let c = e.content.split("$$");
                        for (let i = 0; i < c.length; i += 2) {
                            c[i] = converter.makeHtml(c[i]);
                        }

                        return {
                            checked: false,
                            content: c.join("$$")
                        };
                    });

                    mcq_update_fields();
                } else {
                    isMcq = false;
                    $("#text-entry").css("display", "flex").val("");
                    $("#mcq-options").css("display", "none");
                }

                MathJax.typeset();
            };

            var ask_before_unload = function() {
                if ($("#question-edit-content").val() !== "") {
                    return "Any progress in this quiz will be lost, are you sure you want to continue?";
                }
            };

            window.onbeforeunload = ask_before_unload;

            studycat_get_active_questions(group_id, (q) => {
                if (q.length === 0) {
                    alert("There are no questions for this week!");
                    location = '/group/?id=' + group_id;
                } else {
                    questions = q;
                    render_question();
                }
            }, alert);
        </script>

        <?php include("../footer.php"); ?>
    </div>
</body>

</html>