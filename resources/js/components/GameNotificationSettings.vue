<template>
  <div class="px-4 py-2 space-y-4 border-t">
    <h2 class="text-sm">Game Notification Settings</h2>
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
        <Toggle v-model="settings.lastQuarterNotification" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications for end of first half of gameplay.</span
        >
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.lastQuarterNotification" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications for start of second half of gameplay.</span
        >
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.scoreGapRadio" class="mr-2" />
        <span class="flex-1"
          >Notify when a score gap is reached
          <input
            :disabled="!settings.scoreGapRadio"
            v-model="settings.scoreGap"
            class="ml-2 border rounded py-1 px-2 focus:shadow-outline"
            type="text"
            placeholder="Score gap"
          />
        </span>
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.totalScoreRadio" class="mr-2" />
        <span class="flex-1"
          >Notify when a total score is reached
          <input
            :disabled="!settings.totalScoreRadio"
            v-model="settings.totalScore"
            class="ml-2 border rounded py-1 px-2 focus:shadow-outline"
            type="text"
            placeholder="Total score"
          />
        </span>
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
        scoreGapRadio: false,
        totalScoreRadio: false,
        scoreGap: "",
        totalScore: "",
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
  computed: {
    scoreGapSet() {
      return !!this.scoreGap;
    },
    totalScoreSet() {
      return !!this.totalScore;
    },
  },
};
</script>
