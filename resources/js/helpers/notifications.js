import Vue from "vue";
import Notifications from "vue-notification";

Vue.use(Notifications);

const DEFAULT_SUCCESS_TITLE = "Success";
const DEFAULT_SUCCESS_TEXT = "Operation successful.";
const DEFAULT_WARNING_TITLE = "Warning";
const DEFAULT_WARNING_TEXT = "Something unexpected has happened.";
const DEFAULT_ERROR_TITLE = "Error";
const DEFAULT_ERROR_TEXT = "An error has occured.";

const DEFAULT_DURATION = 7500;

Vue.prototype.$success = (title, text) => {
  Vue.notify({
    group: "notifications",
    title: title || DEFAULT_SUCCESS_TITLE,
    text: text || DEFAULT_SUCCESS_TEXT,
    duration: DEFAULT_DURATION,
    type: "success",
  });
};

Vue.prototype.$warn = (title, text) => {
  Vue.notify({
    group: "notifications",
    title: title || DEFAULT_WARNING_TITLE,
    text: text || DEFAULT_WARNING_TEXT,
    duration: DEFAULT_DURATION,
    type: "warn",
  });
};

Vue.prototype.$error = (title, text) => {
  Vue.notify({
    group: "notifications",
    title: title || DEFAULT_ERROR_TITLE,
    text: text || DEFAULT_ERROR_TEXT,
    duration: DEFAULT_DURATION,
    type: "error",
  });
};
