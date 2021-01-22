<template>
  <div :ref="ref" class="relative" :class="containerClasses">
    <button
      @click="open = !open"
      type="button"
      class="h-full w-full px-4 py-2 focus:outline-none flex items-center space-x-2 text-sm"
    >
      <slot name="button"></slot>
    </button>
    <transition
      enter-class="transform scale-90 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      enter-active-class="transition duraiton-100 ease-out"
      leave-active-class="transition duraiton-50 ease-out"
      leave-to-class="transform scale-90 opacity-0"
      leave-class="transform scale-100 opacity-100"
    >
      <div v-if="open" class="z-50" :class="dropdownClasses">
        <slot name="dropdown"></slot>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: {
    dropdownClasses: {
      type: String,
      default:
        "absolute origin-top-right w-56 right-0 top-full bg-gray-700 rounded shadow",
    },
    containerClasses: {
      type: String,
      default: "h-full text-white flex items-center",
    },
  },
  data: () => ({
    open: false,
    ref: "",
  }),
  create() {
    this.ref = Math.random().toString(36).substr(2, 5);
  },
  watch: {
    open(val) {
      if (val) {
        document.body.addEventListener("keyup", this.escapeListener);
        document.body.addEventListener("click", this.clickAwayListener);
      } else {
        document.body.removeEventListener("keyup", this.escapeListener);
        document.body.removeEventListener("click", this.clickAwayListener);
      }
    },
  },
  beforeDestroy() {
    document.body.removeEventListener("keyup", this.escapeListener);
    document.body.removeEventListener("click", this.clickAwayListener);
  },
  methods: {
    escapeListener(e) {
      if (e.keyCode === 27) {
        this.open = false;
      }
    },
    clickAwayListener(e) {
      if (this.$refs[this.ref].contains(e.target)) {
        return;
      }

      this.open = false;
    },
  },
};
</script>
