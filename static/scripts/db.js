const DB_ROOT = "http://localhost/db-local/";  // Change this to data.nathcat.net on deployment

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
    fetch(DB_ROOT + "study/get-chat-info.php", {
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
        body: JSON.stringify({
            "groupId": groupId,
            "id": id
        })
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") on_success();
        else on_fail(r.message);
    });
}