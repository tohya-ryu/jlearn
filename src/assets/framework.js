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
    /* get form */
    const form_node = framework.util.get_ancestor(event.target, "tag+class",
      {tag:"form", class:"framework-form"});
    if (form_node === null)
      return;

    /* get requirements */
    const form_obj = new FormData(form_node);
    const request_uri = event.target.dataset.uri
    const method = form_node.dataset.method.toUpperCase();
    const notice_node = form_node.querySelector(".framework-validation-notice");
    const promise = fetch(request_uri, { method: method, body: form_obj });
    let inp_val_errors = null;
    let csrf_failed = false;
    let error_cnt = 0;

    promise
      .then((response) => {
      if (!response.ok)
        throw new Error(`HTTP error: ${response.status}`);
      return response.json();
    }).then((data) => {
      /* handle response data */
      switch(data.state) {
      case "invalid":
        /* reset form errors */
        framework.form.clear_errors(form_obj);
        for (const key in data.errors) {
          if (key != "csrf-token") {
            /* for each invalid input: append a list of error messages
             * to the field's error container */
            const list = document.createElement("ul");
            error_cnt++;
            form_node.querySelector(`#${key}`).classList.add(
              "framework-validation-error");
            inp_val_errors = form_node.querySelector(
              `#validation-errors-${key}`);
            framework.util.rsubnodes(inp_val_errors);
            inp_val_errors.appendChild(list);
            for (const error of data.errors[key]) {
              let list_node = document.createElement("li");
              list_node.textContent = error;
              list.appendChild(list_node);
            }
          } else {
            /* special case for failed csrf check */
            csrf_failed = true;
          }
        }
        break;
      case "valid-keep":
        /* reset form errors */
        framework.form.clear_errors(form_obj);
        break;
      case "valid-clear":
        /* reset form errors */
        framework.form.clear_errors(form_obj);
        break;
      }
    }).catch((error) => {
      /* handle error in promise chain */
      console.log(error);
    });

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

framework.util.rsubnodes = function(node) // remove sub nodes
{
  while (node.firstChild) {
    node.removdeChild(node.firstChild);
  }
}
