<template>
  <div class="px-4 py-2 space-y-4 border-t">
    <h2 class="text-sm">Game Notification Settings</h2>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.start" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications for alerts of your selected games start
          time.</span
        >
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.end" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications for alerts of your selected games end
          time.</span
        >
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.first_half_end" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications for end of first half of gameplay.</span
        >
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="settings.second_half_start" class="mr-2" />
        <span class="flex-1"
          >Recieve notifications for start of second half of gameplay.</span
        >
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="scoreGapRadio" class="mr-2" />
        <span class="flex-1"
          >Notify when a score gap is reached
          <input
            :disabled="!scoreGapRadio"
            v-model.number="settings.scoreGap"
            class="ml-2 border rounded py-1 px-2 focus:shadow-outline"
            type="text"
            placeholder="Score gap"
          />
        </span>
      </div>
    </div>
    <div>
      <div class="flex items-center">
        <Toggle v-model="totalScoreRadio" class="mr-2" />
        <span class="flex-1"
          >Notify when a total score is reached
          <input
            :disabled="!totalScoreRadio"
            v-model.number="settings.totalScore"
            class="ml-2 border rounded py-1 px-2 focus:shadow-outline"
            type="text"
            placeholder="Total score"
          />
        </span>
      </div>
    </div>
    <div>
      <button
        type="button"
        class="relative flex items-center space-x-2 px-4 py-2 bg-mantis-500 text-white rounded hover:bg-mantis-600 transition duration-150 ease-out overflow-hidden"
        @click="saveSettings"
      >
        <span
          v-if="saving"
          class="absolute inset-0 z-20 grid place-items-center"
        >
          <span
            class="block h-5 w-5 border-2 border-white rounded-full animate-spin"
            style="border-bottom-color: transparent"
          ></span>
        </span>
        <span :class="{ 'text-transparent': saving }">Save Settings</span>
      </button>
    </div>
  </div>
</template>

<script>
import Toggle from "./Toggle";

export default {
  props: ["notificationSettings"],
  components: {
    Toggle,
  },
  data() {
    return {
      saving: false,
      settings: {
        end: false,
        start: false,
        first_half_end: false,
        second_half_start: false,
        scoreGap: "",
        totalScore: "",
      },
      scoreGapRadio: false,
      totalScoreRadio: false,
    };
  },
  created() {
    this.settings = this.notificationSettings;
    if (this.settings.scoreGap) this.scoreGapRadio = true;
    if (this.settings.totalScore) this.totalScoreRadio = true;
  },
  computed: {
    scoreGapSet() {
      return !!this.scoreGap;
    },
    totalScoreSet() {
      return !!this.totalScore;
    },
  },
  methods: {
    saveSettings() {
      this.saving = true;
      this.$emit("save-settings", this.settings);
    },
    onSaved() {
      this.$success(
        "Saved",
        "Successfully saved your notification settings for the game."
      );
      this.saving = false;
    },
    onSaveError() {
      this.$error(
        "Error",
        "An error occured while trying to save your notification settings for the game. Please try again."
      );
      this.saving = false;
    },
  },
};
</script>
