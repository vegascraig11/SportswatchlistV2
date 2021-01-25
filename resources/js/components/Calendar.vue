<template>
  <div class="text-gray-900 bg-white rounded w-72 shadow p-2 border text-sm">
    <div class="flex justify-between items-center">
      <button
        @click="prevMonth"
        type="button"
        class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-gray-200 focus:outline-none"
      >
        <svg
          class="h-4 w-4"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
        >
          <path
            d="M16 5.078v13.844c0 .959-1.15 1.438-1.822.76l-6.865-6.921a1.082 1.082 0 010-1.522l6.865-6.922C14.85 3.64 16 4.12 16 5.078z"
            fill="currentColor"
          />
        </svg>
      </button>
      <p class="font-medium">{{ monthInfo }}</p>
      <button
        @click="nextMonth"
        type="button"
        class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-gray-200 focus:outline-none"
      >
        <svg
          class="h-4 w-4"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
        >
          <path
            d="M7 18.922V5.078c0-.958 1.15-1.439 1.822-.76l6.865 6.921c.417.42.417 1.102 0 1.522l-6.865 6.922C8.15 20.36 7 19.88 7 18.922z"
            fill="currentColor"
          />
        </svg>
      </button>
    </div>
    <div class="mt-3 py-3 grid grid-cols-7 gap-1 border-t border-b">
      <div
        class="col-span-1 text-center text-gray-700 font-medium"
        v-for="day in days"
        :key="`day-label-${day}`"
      >
        <span>{{ day }}</span>
      </div>
    </div>
    <div class="mt-1 grid grid-cols-7 grid-rows-6 gap-1">
      <span
        class="col-span-1 text-center text-gray-700 font-medium p-2"
        v-for="day in startDay"
        :key="`start-day-filler-${day}`"
      >
        <span>&nbsp;</span>
      </span>
      <button
        type="button"
        class="col-span-1 text-center font-medium w-8 h-8 flex items-center justify-center rounded-full transition ease-in duraiton-150"
        v-for="day in daysInMonth"
        :key="`day-${day}`"
        :class="{
          'bg-mantis-500 text-white': day == selected,
          'border-2 border-mantis-500 bg-gray-200': day == today,
          'hover:bg-gray-200 cursor-pointer': day != selected,
          'cursor-wait': disabled,
        }"
        @click="selectDate(day)"
      >
        <span>{{ day }}</span>
      </button>
    </div>
  </div>
</template>

<script>
import { DateTime } from "luxon";

export default {
  props: {
    selectedDate: {
      type: Object,
      default: () => null,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  components: {},
  data: () => ({
    date: null,
    days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
  }),
  created() {
    this.date = DateTime.local();
  },
  computed: {
    monthInfo() {
      return this.date.toFormat("LLL yyyy");
    },
    daysInMonth() {
      return this.date.daysInMonth;
    },
    startDay() {
      const startDay = this.date.startOf("month").weekday;

      return startDay === 7 ? 0 : startDay;
    },
    today() {
      const today = DateTime.local();
      return today.toFormat("L") == this.date.toFormat("L")
        ? today.toFormat("d")
        : false;
    },
    selected() {
      if (this.selectedDate) {
        if (
          this.selectedDate.month == this.date.month &&
          this.selectedDate.year == this.date.year
        ) {
          return this.selectedDate.day;
        }
      }

      return null;
    },
  },
  methods: {
    prevMonth() {
      this.date = this.date.minus({ month: 1 });
    },
    nextMonth() {
      this.date = this.date.plus({ month: 1 });
    },
    selectDate(date) {
      if (this.disabled) return;
      this.$emit("date-selected", this.date.set({ day: date }));
    },
  },
};
</script>

<style></style>
