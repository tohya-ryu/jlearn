"use strict";
var framework = {
  version: "1.0.0",
  root_dir: "jlearn",
  util: {},
  form: {}
};

framework.form.submit = function(event) {
  /* check if request is already pending */
  if (event.target.getAttribute("disabled") === "") {
    return;
  } else {
    /* lock button */
    event.target.toggleAttribute("disabled")
  }
  /* get form */
  const form_node = framework.util.get_ancestor(event.target, "tag+class",
    {tag:"form", class:"framework-form"});
  if (form_node === null)
    return;

  /* prepare requirements */
  const form_obj = new FormData(form_node);
  const request_uri = event.target.dataset.uri
  const method = form_node.dataset.method;
  const notice_node = form_node.querySelector(".framework-validation-notice");
  const content_type = form_node.getAttribute("enctype");
  const promise = fetch(request_uri,
    { credentials: "include", method: method, body: form_obj });
  let inp_val_errors = null;
  let csrf_failed = false;
  //let error_cnt = 0;
  framework.util.rsubnodes(notice_node);
  let notice_list = document.createElement("ul");
  let list_node = null;
  notice_node.appendChild(notice_list);

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
        //error_cnt++;
        form_node.querySelector(`#${key}`).classList.add(
          "framework-validation-error");
        inp_val_errors = form_node.querySelector(
          `#validation-errors-${key}`);
        framework.util.rsubnodes(inp_val_errors);
        inp_val_errors.appendChild(list);
        for (const error of data.errors[key]) {
          list_node = document.createElement("li");
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
    /* set form notice */
    list_node = document.createElement("li");
    list_node.textContent = data.notice;
    notice_list.appendChild(list_node);
    if (csrf_failed) {
      list_node = document.createElement("li");
      list_node.textContent = data.errors["csrf-token"][0];
      notice_list.appendChild(list_node);
      form_node.querySelector("#csrf-token").value = data.csrf_update;
    }
    /* unlock button clickable state */
    if (event.target.getAttribute("disabled") !== null)
      event.target.toggleAttribute("disabled")
  }).catch((error) => {
    /* handle error in promise chain */
    console.log(error);
    /* unlock button */
    if (event.target.getAttribute("disabled") !== null)
      event.target.toggleAttribute("disabled")
  });
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

/* utility functions */

/* attempt to find a certain ancestor node of <node>
 * features various search types expecting varying data
 *
 * type: "tag+class", data: { "<tag-name>", "<class-name>"
 *
 */
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
    node.removeChild(node.firstChild);
  }
}

/* initiate form handling */
document.querySelectorAll(".framework-form-submit").forEach((btn) => {
  btn.addEventListener("click", framework.form.submit);
});
