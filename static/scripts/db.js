const DB_ROOT = "https://data.nathcat.net/";  // Change this to data.nathcat.net on deployment

function studycat_create_group(name, on_success, on_fail) {
    fetch(DB_ROOT + "study/create-group.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({"name": name})
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success(r.id);
        else on_fail(r.message);
    });
}

function studycat_get_chat_info(id, on_success, on_fail) {
    fetch(DB_ROOT + "study/get-group-info.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({"id": id})
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success(r.group, r.members);
        else on_fail(r.message);
    });
}

function studycat_check_if_admin(groupId, on_success, on_fail) {
    fetch(DB_ROOT + "study/check-if-admin.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({"groupId": groupId})
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success(r.isAdmin);
        else on_fail(r.message);
    });
}

function studycat_search_users(username, on_success, on_fail) {
    fetch(DB_ROOT + "sso/user-search.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        credentials: "include",
        body: JSON.stringify({
            "username": username
        })
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success(r.results);
        else on_fail(r.message);
    });
}

function studycat_add_to_group(groupId, id, on_success, on_fail) {
    fetch(DB_ROOT + "study/add-to-group.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        credentials: "include",
        body: JSON.stringify({
            "groupId": groupId,
            "id": id
        })
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success();
        else on_fail(r.message);
    });
}

function studycat_toggle_admin(groupId, id, on_success, on_fail) {
    fetch(DB_ROOT + "study/toggle-admin.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        credentials: "include",
        body: JSON.stringify({
            "groupId": groupId,
            "id": id
        })
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success();
        else on_fail(r.message);
    })
}

function studycat_get_all_groups(on_success, on_fail) {
    fetch(DB_ROOT + "study/get-all-groups.php", {
        method: "GET",
        credentials: "include"
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success(r.groups, r.ownedGroups);
        else on_fail(r.message);
    });
}

function studycat_delete_group(id, on_success, on_fail) {
    if (!confirm("Are you sure you want to delete this group?")) return;

    fetch(DB_ROOT + "study/delete-group.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({"groupId": id})
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success();
        else on_fail(r.message);
    });
}

function studycat_create_question(groupId, content, mcqOrString, answer, mcqOptions, on_success, on_fail) {
    let r = {
        "groupId": groupId,
        "content": content,
        "mcqOrString": mcqOrString,
        "answer": answer
    }

    if (content === "" || answer === "") { alert("Cannot leave question or answer blank!"); return; }

    if (mcqOrString === "mcq") r.mcqOptions = mcqOptions;

    fetch(DB_ROOT + "study/create-question.php", {
        method: "POST",
        credentials: "include",
        body: JSON.stringify(r)
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success();
        else on_fail(r.message);
    });
}

function studycat_get_active_questions(id, on_success, on_fail) {
    fetch(DB_ROOT + "study/get-active-questions.php", {
        method: "POST",
        credentials: "include",
        body: JSON.stringify({"id": id})
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success(r.questions);
        else on_fail(r.message);
    });
}

function studycat_submit_score(id, score, on_success, on_fail) {
    fetch(DB_ROOT + "study/submit-score.php", {
        method: "POST",
        credentials: "include",
        body: JSON.stringify({
            "groupId": id,
            "score": score
        })
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success();
        else on_fail(r.message);
    })
}