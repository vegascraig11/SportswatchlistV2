<template>
  <header>
    <div class="text-white bg-swl-black-light">
      <div class="container mx-auto px-4">
        <div class="h-32 flex justify-between items-center">
          <div>
            <router-link to="/">
              <img
                class="h-12"
                src="./../assets/images/logo.png"
                alt="Sports Watchlist"
              />
            </router-link>
          </div>
          <nav class="hidden lg:flex text-sm">
            <router-link to="/my-watchlist">My Watchlist</router-link>
            <div v-if="loggedIn" class="ml-4">
              <p class="inline-block font-semibold">{{ username }}</p>
              <p class="ml-2 inline-block cursor-pointer" @click="logout">
                Logout
              </p>
            </div>
            <div v-else class="ml-4 pl-4 border-l border-gray-700">
              <router-link to="/signup">Sign Up</router-link>
              <router-link
                to="/login"
                class="ml-4 px-4 py-2 bg-mantis-500 rounded-sm"
                >Login</router-link
              >
            </div>
          </nav>
          <div class="lg:hidden relative" ref="userDropdown">
            <button
              @click="mobileDropdown = !mobileDropdown"
              type="button"
              class="p-4 focus:outline-none focus:ring transition ease-in duration-150 rounded"
            >
              <svg
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
                class="fill-current h-6 w-6"
              >
                <title>Menu</title>
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
              </svg>
            </button>
            <transition
              enter-class="transform scale-90 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              enter-active-class="transition duration-100 ease-in"
              leave-active-class="transition duration-75 ease-in"
              leave-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-90 opacity-0"
            >
              <div
                v-if="mobileDropdown"
                class="text-sm absolute origin-top-right right-0 w-56 p-2 bg-gray-800 border border-gray-800 rounded z-50"
              >
                <router-link
                  class="block p-2 hover:bg-gray-900 transition ease-in duration-150 rounded"
                  to="/my-watchlist"
                  >My Watchlist</router-link
                >
                <router-link
                  class="block p-2 hover:bg-gray-900 transition ease-in duration-150 rounded"
                  to="/faq"
                  >FAQ's</router-link
                >
                <div v-if="loggedIn" class="border-t mt-2">
                  <p class="mb-2 px-2 block py-2 font-semibold">
                    {{ username }}
                  </p>
                  <button
                    type="button"
                    class="block w-full text-left p-2 cursor-pointer hover:bg-gray-900 transition ease-in duration-150 rounded focus:outline-none"
                    @click="logout"
                  >
                    Logout
                  </button>
                </div>
                <div v-else class="border-t mt-2 py-2">
                  <router-link
                    to="/signup"
                    class="block p-2 mb-2 hover:bg-gray-900 transition ease-in duration-150 rounded"
                    >Sign Up</router-link
                  >
                  <router-link
                    to="/login"
                    class="block p-2 bg-mantis-500 hover:bg-opacity-90 transition ease-in duration-150 rounded-sm"
                    >Login</router-link
                  >
                </div>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-swl-black-dark">
      <div class="flex flex-wrap items-stretch text-white">
        <div
          class="relative w-1/2 md:w-1/4 flex items-center justify-center bg-mantis-500 text-center cursor-pointer py-3 sm:py-0"
          @click="openSportDropdown"
        >
          <div
            v-if="sportDropdown"
            class="absolute top-0 left-0 right-0 mt-12 z-50"
          >
            <ul class="w-full bg-swl-black-light">
              <li
                v-for="league in leagues"
                @click="toggleLeague(league)"
                :key="`toggle-${league}-button`"
                class="relative py-2 hover:bg-gray-700"
              >
                <span class="uppercase">{{ league }}</span>
                <span
                  class="absolute right-0 mr-4"
                  :class="
                    selectedLeagues.includes(league)
                      ? 'text-mantis-500'
                      : 'text-gray-800'
                  "
                >
                  <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                      fill-rule="evenodd"
                    ></path>
                  </svg>
                </span>
              </li>
            </ul>
          </div>
          <span>Sports</span>
          <span class="ml-4">
            <svg
              class="h-4 w-4"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 320 512"
            >
              <path
                fill="currentColor"
                d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"
              />
            </svg>
          </span>
        </div>
        <div
          @click="toggleSortDropdown"
          class="relative flex items-center justify-center w-1/2 md:w-24 bg-swl-black-lighter text-center cursor-pointer px-4"
        >
          <div
            v-if="sortDropdownOpen"
            class="absolute top-0 left-0 right-0 mt-12 z-50"
          >
            <ul class="w-full bg-swl-black-light">
              <li
                @click="sort($event, 'rot')"
                class="relative py-2 hover:bg-gray-700"
              >
                # ROT
              </li>
              <li
                @click="sort($event, 'time')"
                class="relative py-2 hover:bg-gray-700"
              >
                Time
              </li>
            </ul>
          </div>
          <div class="flex items-center">
            <span class="flex flex-col">
              <svg
                class="h-4 w-4 -mb-1"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 320 512"
              >
                <path
                  fill="currentColor"
                  d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"
                />
              </svg>
              <svg
                class="h-4 w-4 -mt-1"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 320 512"
              >
                <path
                  fill="currentColor"
                  d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"
                />
              </svg>
            </span>
            <span class="ml-1">Sort</span>
          </div>
        </div>
        <div class="flex">
          <date-picker :date="date"></date-picker>
          <div class="flex items-center py-2">
            <button
              @click="previousDay"
              type="button"
              class="px-2"
              :disabled="loadingLeagues"
              :class="{ 'cursor-wait': loadingLeagues }"
            >
              <svg
                class="h-5 w-5"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 192 512"
              >
                <path
                  fill="currentColor"
                  d="M192 127.338v257.324c0 17.818-21.543 26.741-34.142 14.142L29.196 270.142c-7.81-7.81-7.81-20.474 0-28.284l128.662-128.662c12.599-12.6 34.142-3.676 34.142 14.142z"
                />
              </svg>
            </button>
            <table class="table-fixed">
              <tbody>
                <tr>
                  <td class="flex text-gray-600">
                    <button
                      v-for="(day, index) in week"
                      :key="`row-${day.date}`"
                      class="flex flex-col items-center mx-1 leading-tight font-semibold"
                      :class="{
                        'text-white': index === 3,
                        'cursor-wait': loadingLeagues,
                      }"
                      type="button"
                      @click="setDate(index)"
                      :disabled="loadingLeagues"
                    >
                      <span class="text-xs uppercase">{{ day.day }}</span>
                      <span>{{ day.date }}</span>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            <button
              @click="nextDay"
              type="button"
              class="px-2"
              :disabled="loadingLeagues"
              :class="{ 'cursor-wait': loadingLeagues }"
            >
              <svg
                class="h-5 w-5"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 192 512"
              >
                <path
                  fill="currentColor"
                  d="M0 384.662V127.338c0-17.818 21.543-26.741 34.142-14.142l128.662 128.662c7.81 7.81 7.81 20.474 0 28.284L34.142 398.804C21.543 411.404 0 402.48 0 384.662z"
                />
              </svg>
            </button>
          </div>
        </div>
        <div
          class="w-full md:w-auto px-6 md:px-0 py-1 relative flex items-center"
        >
          <div
            class="absolute top-0 bottom-0 left-0 flex items-center pl-8 md:pl-2"
          >
            <svg
              class="w-4 h-4 text-gray-600"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512 512"
            >
              <path
                fill="currentColor"
                d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"
              />
            </svg>
          </div>
          <input
            @keyup="search"
            type="text"
            class="w-full pl-8 pr-4 py-2 bg-swl-black-light rounded placeholder-gray-400"
            placeholder="Search"
          />
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import _ from "lodash";
import { DateTime } from "luxon";

import DatePicker from "./../components/DatePicker";

export default {
  components: {
    DatePicker,
  },
  data() {
    return {
      mobileDropdown: false,
      sortDropdownOpen: false,
      week: [],
      sportDropdown: false,
      date: {},
    };
  },
  created() {
    this.date = this.stateDate || DateTime.local();
    this.buildWeekRow();
  },
  computed: {
    stateDate() {
      return this.$store.state.date;
    },
    loggedIn() {
      return this.$store.getters.isLoggedIn;
    },
    leagues() {
      return this.$store.state.leagues;
    },
    loadingLeagues() {
      return !!this.$store.state.loading.length;
    },
  },
  watch: {
    mobileDropdown(val) {
      if (val) {
        document.body.addEventListener("click", this.clickAwayListener);
      } else {
        document.body.removeEventListener("click", this.clickAwayListener);
      }
    },
    date() {
      this.buildWeekRow();
    },
    $route() {
      this.mobileDropdown = false;
    },
  },
  methods: {
    buildWeekRow(start) {
      const week = [];

      for (var i = -3; i < 4; i++) {
        const day = this.date.plus({ days: i });
        week.push({
          day: day.toFormat("ccc"),
          date: day.toFormat("d"),
          dateTime: day,
        });
      }

      this.week = week;
    },
    previousDay() {
      if (this.loadingLeagues) return;
      this.date = this.date.minus({ days: 1 });
      this.$store.commit("setDate", this.date);
    },
    nextDay() {
      if (this.loadingLeagues) return;
      this.date = this.date.plus({ days: 1 });
      this.$store.commit("setDate", this.date);
    },
    clickAwayListener(e) {
      if (this.$refs.userDropdown.contains(e.target)) {
        return;
      }

      this.mobileDropdown = false;
    },
    search: _.debounce(function (event) {
      let query = event.target.value.trim();

      if (query) {
        window.events.$emit("search", query.toLowerCase());
      } else {
        window.events.$emit("clear-search");
      }
    }, 500),
    openSportDropdown(e) {
      e.stopPropagation();

      this.sportDropdown = true;

      document
        .querySelector("body")
        .addEventListener("click", this.bodyClickListener);
    },
    closeSportDropdown(e) {
      e.stopPropagation();

      this.sportDropdown = false;

      document
        .querySelector("body")
        .removeEventListener("click", this.bodyClickListener);
    },
    bodyClickListener(e) {
      this.closeSportDropdown(e);
    },
    toggleSortDropdown(e) {
      e.stopPropagation();

      this.sortDropdownOpen = !this.sortDropdownOpen;
    },
    logout() {
      this.$store
        .dispatch("logout")
        .then(() => {
          this.$success("Success", "You were logged out successfully.");
          this.$router.push("/");
        })
        .catch(err => {
          console.log("Error logging out", err);
        });
    },
    toggleLeague(league) {
      if (this.$route.name !== "home") {
        this.$router.push("/");
      }

      this.$store.commit("toggleLeague", league);
    },
    setLeague(league) {
      if (this.$route.name !== "home") {
        this.$router.push("/");
      }

      this.$store.commit("setLeague", league);
    },
    sort(e, method) {
      e.stopPropagation();
      this.sortDropdownOpen = false;

      window.events.$emit("sort", method);
    },
    setDate(index) {
      if (this.loadingLeagues) return;
      this.date = this.week[index].dateTime;
      this.$store.commit("setDate", this.date);
    },
  },
};
</script>

<style></style>
