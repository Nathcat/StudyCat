const DB_ROOT = "http://localhost/db-local/";  // Change this to data.nathcat.net on deployment

function studycat_create_group(name, on_success, on_fail) {
    fetch(DB_ROOT + "create-group.php", {
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
    fetch(DB_ROOT + "get-chat-info.php", {
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