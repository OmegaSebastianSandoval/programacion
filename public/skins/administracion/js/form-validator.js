(function () {
  "use strict";

  var REMOTE_STATE_KEY = "__remoteValidationState";

  function getErrorContainer(field) {
    var group = field.closest(".form-group");
    if (!group) {
      return null;
    }
    return group.querySelector(".help-block.with-errors");
  }

  function setFieldError(field, message) {
    var group = field.closest(".form-group");
    var container = getErrorContainer(field);

    field.classList.add("is-invalid");
    field.classList.remove("is-valid");
    field.setCustomValidity(message || "Campo invalido");

    if (group) {
      group.classList.add("has-error");
    }
    if (container) {
      container.textContent = message || "Campo invalido";
    }
  }

  function clearFieldError(field) {
    var group = field.closest(".form-group");
    var container = getErrorContainer(field);

    field.classList.remove("is-invalid");
    field.setCustomValidity("");

    if (group) {
      group.classList.remove("has-error");
    }
    if (container) {
      container.textContent = "";
    }
  }

  function setNativeError(field) {
    if (field.checkValidity()) {
      clearFieldError(field);
      return true;
    }

    setFieldError(field, field.validationMessage || "Campo invalido");
    return false;
  }

  function validateMatchField(field) {
    var selector = field.getAttribute("data-match");
    if (!selector) {
      return true;
    }

    var form = field.form;
    if (!form) {
      return true;
    }

    var target = form.querySelector(selector);
    if (!target) {
      return true;
    }

    if (field.value !== target.value) {
      setFieldError(
        field,
        field.getAttribute("data-match-error") || "Los campos no coinciden",
      );
      return false;
    }

    clearFieldError(field);
    return true;
  }

  function setRemoteState(field, state) {
    field[REMOTE_STATE_KEY] = state;
  }

  function getRemoteState(field) {
    return (
      field[REMOTE_STATE_KEY] || { value: null, valid: null, pending: null }
    );
  }

  function buildRemoteUrl(field) {
    var remoteUrl = field.getAttribute("data-remote");
    if (!remoteUrl) {
      return null;
    }

    var separator = remoteUrl.indexOf("?") === -1 ? "?" : "&";
    var paramName = encodeURIComponent(field.name || field.id || "value");
    var paramValue = encodeURIComponent(field.value || "");

    return remoteUrl + separator + paramName + "=" + paramValue;
  }

  function getRemoteErrorMessage(xhr, field) {
    if (xhr.statusText && xhr.statusText !== "Bad Request") {
      return xhr.statusText;
    }

    if (xhr.responseText) {
      return xhr.responseText;
    }

    return (
      field.getAttribute("data-remote-error") ||
      "El valor ingresado no es valido"
    );
  }

  function runRemoteValidation(field) {
    var url = buildRemoteUrl(field);
    if (!url || !field.value) {
      clearFieldError(field);
      setRemoteState(field, { value: field.value, valid: true, pending: null });
      return Promise.resolve(true);
    }

    var currentState = getRemoteState(field);
    if (currentState.pending && currentState.value === field.value) {
      return currentState.pending;
    }

    if (currentState.value === field.value && currentState.valid !== null) {
      if (currentState.valid) {
        clearFieldError(field);
        return Promise.resolve(true);
      }
      setFieldError(
        field,
        currentState.message || "El valor ingresado no es valido",
      );
      return Promise.resolve(false);
    }

    var promise = new Promise(function (resolve) {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", url, true);
      xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

      xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
          return;
        }

        if (xhr.status >= 200 && xhr.status < 300) {
          clearFieldError(field);
          setRemoteState(field, {
            value: field.value,
            valid: true,
            pending: null,
            message: "",
          });
          resolve(true);
          return;
        }

        var message = getRemoteErrorMessage(xhr, field);
        setFieldError(field, message);
        setRemoteState(field, {
          value: field.value,
          valid: false,
          pending: null,
          message: message,
        });
        resolve(false);
      };

      xhr.onerror = function () {
        var message = "No fue posible validar este campo en este momento";
        setFieldError(field, message);
        setRemoteState(field, {
          value: field.value,
          valid: false,
          pending: null,
          message: message,
        });
        resolve(false);
      };

      xhr.send();
    });

    setRemoteState(field, {
      value: field.value,
      valid: null,
      pending: promise,
      message: "",
    });

    return promise;
  }

  function validateField(field) {
    if (!field || field.disabled) {
      return Promise.resolve(true);
    }

    clearFieldError(field);

    if (!setNativeError(field)) {
      return Promise.resolve(false);
    }

    if (!validateMatchField(field)) {
      return Promise.resolve(false);
    }

    if (field.hasAttribute("data-remote")) {
      return runRemoteValidation(field);
    }

    return Promise.resolve(true);
  }

  function attachFieldListeners(form, field) {
    field.addEventListener("input", function () {
      if (field.hasAttribute("data-remote")) {
        setRemoteState(field, {
          value: null,
          valid: null,
          pending: null,
          message: "",
        });
      }
      clearFieldError(field);
    });

    field.addEventListener("blur", function () {
      validateField(field);
    });

    if (field.hasAttribute("data-match")) {
      var target = form.querySelector(field.getAttribute("data-match"));
      if (target) {
        target.addEventListener("input", function () {
          if (field.value) {
            validateField(field);
          }
        });
      }
    }
  }

  function validateForm(form) {
    var fields = Array.prototype.slice.call(
      form.querySelectorAll("input, select, textarea"),
    );
    var validations = fields.map(function (field) {
      return validateField(field);
    });

    return Promise.all(validations).then(function (results) {
      var allValid = results.every(function (item) {
        return item === true;
      });

      if (!allValid) {
        form.classList.add("was-validated");
      }

      return allValid;
    });
  }

  function bindValidator(form) {
    if (form.dataset.validatorBound === "1") {
      return;
    }

    form.dataset.validatorBound = "1";

    var fields = form.querySelectorAll("input, select, textarea");
    Array.prototype.slice.call(fields).forEach(function (field) {
      attachFieldListeners(form, field);
    });

    form.addEventListener("submit", function (event) {
      event.preventDefault();
      event.stopPropagation();

      validateForm(form).then(function (isValid) {
        if (isValid) {
          HTMLFormElement.prototype.submit.call(form);
        }
      });
    });
  }

  function init() {
    var forms = document.querySelectorAll('form[data-bs-toggle="validator"]');
    Array.prototype.slice.call(forms).forEach(bindValidator);
  }

  document.addEventListener("DOMContentLoaded", init);
})();
