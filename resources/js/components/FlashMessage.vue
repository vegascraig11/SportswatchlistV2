<template>
  <transition name="slide-in">
    <div class="fixed top-0 right-0 bg-white rounded-sm mr-10 mt-10 shadow-lg overflow-hidden" v-if="visible">
      <div class="relative">
        <div class="absolute left-0 top-0 bottom-0 w-2" :class="bgClass"></div>
        <div class="border border-gray-100 pl-8 pr-6 py-2">
          <span>{{ body }}</span>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
const types = {
  INFO: 'info',
  SUCCESS: 'success',
  ERROR: 'error',
  WARN: 'warn'
}

export default {
  props: {
    messsage: {
      type: Object,
      default: () => {
        return {}
      }
    }
  },
  data() {
    return {
      body: '',
      type: '',
      visible: false,
    }
  },
  created() {
    if (this.message) {
      this.flash(this.message)
    }

    window.events.$on('flash', message => this.flash(message))
  },
  computed: {
    bgClass() {
      switch (this.type) {
        case types.WARN:
          return 'bg-yellow-500'
        case types.ERROR:
          return 'bg-red-500'
        case types.SUCCESS:
          return 'bg-green-500'
        default:
          return 'bg-blue-500'
      }
    }
  },
  methods: {
    flash(message) {
      this.body = message.body
      this.type = message.type
      this.visible = true

      this.hide()
    },
    hide() {
      setTimeout(() => this.visible = false, 3000)
    }
  }
}
</script>

<style>
  .slide-in-enter-active {
  transition: all .3s ease;
  }
  .slide-in-leave-active {
    transition: all .3s ease;
  }
  .slide-in-enter, .slide-in-leave-to
  /* .slide-in-leave-active below version 2.1.8 */ {
    transform: translateX(100px);
    opacity: 0;
  }
</style>