"use strict";
var framework = {
    version: "1.0.0",
    root_dir: "jlearn",
    util: {},
    form: {}
};

// initiate form handling
document.querySelectorAll(".framework-form-submit").forEach((btn) => {

    btn.addEventListener("click", (event) => {
        // get form node
        let form_node = framework.util.get_ancestor(event.target, "tag+class",
            {tag:"form", class:"framework-form"});
        if (form_node !== null) {
        }
    });
});

framework.form.send = function(uri, form_id)
{
    const xhttp = new XMLHttpRequest();
    const form = document.getElementById(form_id);
    let form_obj = new FormData(form);
    let element = null;
    xhttp.onload = function()
    {
        console.log(this.responseText);
        let response = JSON.parse(this.responseText);
        console.log(response);
        let msg_cont; // error message container for each input
        let notice_cont; // message container for form
        let str = "";
        let str_notice = "<ul>";
        let csrf_failed = false;
        let error_cnt = 0;
        notice_cont = document.getElementById("validation-notice-"+
            form_id);
        switch (response.state) {
        case "invalid":
            framework.form.clear_errors(form_obj);
            if (response.state == "invalid") {
                // for every invalid input
                for (const key in response.errors) {
                    if (key != "csrf-token") {
                        error_cnt++;
                        document.getElementById(key).classList.add(
                            "framework-validation-error");
                        msg_cont = document.getElementById(
                            "validation-errors-"+key);
                        msg_cont.innerHTML = "";
                        str = "<ul>";
                        // for every error
                        for (const msg of response.errors[key]) {
                            str += "<li>"+msg+"</li>";
                        }
                        str += "</ul>";
                        msg_cont.innerHTML = str;
                    } else {
                        csrf_failed = true;
                    }
                }
            }
            break;
        case "valid-keep":
            framework.form.clear_errors(form_obj);
            break;
        case "valid-clear":
            framework.form.clear_errors(form_obj);
            break;
        }
        if (error_cnt > 0) {
            str_notice += "<li>"+response.notice+"</li>";
        }
        if (csrf_failed) {
            str_notice += "<li>"+response.errors["csrf-token"][0]+"</li>";
            document.getElementById("csrf-token").value = response.csrf_update;
        }
        str_notice += "</ul>"
        notice_cont.innerHTML = str_notice;
    }
    xhttp.open("POST", uri, true);
    xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    let data = "";
    for (let [key, value] of form_obj) {
        data += key + "=" + value + "&";
    }
    data = data.slice(0, -1);  

    
    xhttp.send(data);

}

framework.form.clear_errors = function (form_obj)
{
    let element = null;
    for (let [key, value] of form_obj) {
        element = document.getElementById("validation-errors-"+key);
        if (element) {
            element.innerHTML = "";
        }
        element = document.getElementById(key);
        if (element) {
            element.classList.remove("framework-validation-error");
        }
    }
}

// utility functions
framework.util.get_ancestor = function(node, type, data)
{
  if (!["tag+class"].includes(type)) {
      console.log("Error in 'framework.util.get_ancestor': invalid type name '"
          + type + "'.");
      return null;
  }
  while (node.parentNode !== null) {
    node = node.parentNode;
    switch (type) {
    case "tag+class":
      if (node.tagName === data.tag.toUpperCase() &&
          node.classList.contains(data.class))
      {
        return node;
      }
    }
  }
  return null
}
