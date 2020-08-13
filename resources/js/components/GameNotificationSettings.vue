<template>
  <div class="px-4 py-2 space-y-2">
    <h2 class="text-sm">Game Notification Settings</h2>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.lastQuarterNotification" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications for last quarter of gameplay.</span
        >
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.alertStartTimeNotification" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications for alerts of your selected games start
          time.</span
        >
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.alertEndTimeNotification" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications for alerts of your selected games end
          time.</span
        >
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.keyPlayerChangeNotification" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications when the key player of your selected game
          changes.</span
        >
      </div>
    </div>
  </div>
</template>

<script>
import Toggle from "./Toggle";

export default {
  components: {
    Toggle,
  },
  data() {
    return {
      settings: {
        lastQuarterNotification: false,
        alertStartTimeNotification: false,
        alertEndTimeNotification: false,
        keyPlayerChangeNotification: false,
      },
    };
  },
  watch: {
    settings: {
      deep: true,
      handler(newValue) {
        this.$emit("input", newValue);
      },
    },
  },
};
</script>
