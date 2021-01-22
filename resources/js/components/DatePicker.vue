<template>
  <div>
    <app-dropdown
      dropdown-classes="absolute origin-top top-full w-56 top-full bg-gray-700 rounded shadow z-50"
    >
      <template #button>
        <svg
          class="h-4 w-4 text-white"
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 448 512"
        >
          <path
            fill="currentColor"
            d="M148 288h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm108-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 96v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm192 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-260v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"
          />
        </svg>
        <span class="font-medium">{{ prettyDate }}</span>
      </template>
      <template #dropdown>
        <app-calendar
          :selected-date="stateDate"
          @date-selected="dateSelected"
          :disabled="loadingLeagues"
        ></app-calendar>
      </template>
    </app-dropdown>
  </div>
</template>

<script>
import { DateTime } from "luxon";

import AppDropdown from "./Dropdown";
import AppCalendar from "./Calendar";

export default {
  components: {
    AppCalendar,
    AppDropdown,
  },
  data() {
    return {
      date: null,
    };
  },
  created() {
    this.date = this.stateDate || DateTime.local();
  },
  watch: {
    stateDate(val) {
      this.date = val;
    },
  },
  computed: {
    prettyDate() {
      return this.date.toFormat("LLL d, yyyy");
    },
    stateDate() {
      return this.$store.state.date;
    },
    loadingLeagues() {
      return !!this.$store.state.loading.length;
    },
  },
  methods: {
    dateSelected(date) {
      this.date = date;
      this.$store.commit("setDate", date);
    },
  },
};
</script>
