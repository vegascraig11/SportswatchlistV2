<template>
  <div
    class="min-h-screen flex flex-col text-gray-900 bg-gray-100 font-inter text-xs antialiased"
  >
    <div class="bg-mantis-500 text-white">
      <div class="container mx-auto py-1 md:py-2 px-4 text-center">
        <span class="font-semibold">New to Sports Watchlist?</span>
        <span
          >Click here to see why we are your #1 source to get live updates on
          your favorite sports games!</span
        >
      </div>
    </div>
    <app-header></app-header>
    <section class="flex-1 py-6">
      <main class="w-full h-full max-w-6xl mx-auto sm:px-4">
        <router-view></router-view>
      </main>
    </section>
    <footer>
      <div class="py-4 sm:py-8 bg-swl-black-light text-white">
        <nav
          class="container mx-auto px-4 flex justify-end text-base sm:text-xl font-semibold tracking-wide"
        >
          <button
            type="button"
            v-for="league in leagues"
            :key="`link-to-${league}`"
            class="ml-2 sm:ml-4 uppercase"
            @click="setLeague(league)"
          >
            {{ league }}
          </button>
        </nav>
      </div>
      <div class="bg-swl-black-lighter text-white py-8">
        <div class="container mx-auto px-4 text-center">
          <div class="inline-block uppercase tracking-wide font-semibold">
            <div class="flex">
              <a href="#">Advertise with Us</a>
              <span class="px-4">|</span>
              <a href="#">Privacy Policy</a>
            </div>
          </div>
        </div>
        <div class="container mx-auto px-4 text-center text-gray-500 mt-8">
          <p>
            The acitivities offered by advertising links to other sites, may be
            deemed an illegal activity in certain juristrictions and are void
            when prohibited. The viewer is specifically warned that they should
            make their own inquiry into the legality of participating in any of
            these games and/or activities. The owner of the web sites assumes no
            responsibility or endorsement of any these games and/or activities
            if they are in the juristriction of the reader of client of this
            site.
          </p>
          <p class="mt-4 font-semibold">
            Sports Watchlist {{ theYear }} &copy; All Rights Reserved
          </p>
        </div>
      </div>
    </footer>
    <notifications group="notifications" classes="notify" />
    <div class="fixed inset-0 pointer-events-none px-4">
      <transition
        enter-class="transform -translate-y-10 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        enter-acitve-class="transition duration-150 ease-out"
        leave-active-class="transition duration-150 ease-out"
        leave-to-class="transform -translate-y-10 opacity-0"
        leave-class="transform translate-y-0 opacity-100"
      >
        <div
          v-if="showDialog"
          class="relative w-full p-4 max-w-xl mx-auto bg-white text-gray-800 text-sm shadow-lg rounded-lg mt-4 pointer-events-auto"
        >
          <button
            @click="hideDialog"
            type="button"
            class="absolute top-0 right-0 rounded-full bg-gray-200 p-2 mt-1 mr-1 hover:bg-gray-400 transition ease-out duration-150"
          >
            <svg
              class="w-4 h-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
          <div class="border-2 p-4 border-mantis-500 rounded-lg text-center">
            <p>
              Become an early adopter of <strong>Sports Watch List</strong> -
              let us know your email and we'll send you instructions on how to
              create a custom scoreboard for your favorite teams!
            </p>
            <div class="mt-4">
              <label for="email" class="sr-only">Email</label>
              <input
                v-model="email"
                type="email"
                class="w-full rounded border border-gray-500 placeholder-gray-600 px-3 py-2"
                placeholder="johndoe@domain.com"
              />
            </div>
            <div class="mt-4">
              <button
                type="button"
                class="w-full relative flex items-center justify-center px-4 py-2 bg-mantis-500 text-white rounded hover:bg-mantis-600 transition duration-150 ease-out overflow-hidden"
                @click="submitEmail"
                :disabled="submitting"
              >
                <span
                  v-if="submitting"
                  class="absolute inset-0 z-20 grid place-items-center"
                >
                  <span
                    class="block h-5 w-5 border-2 border-white rounded-full animate-spin"
                    style="border-bottom-color: transparent"
                  ></span>
                </span>
                <span :class="{ 'text-transparent': submitting }">Submit</span>
              </button>
            </div>
            <p class="mt-4 text-gray-600" @click="hideDialog">
              If you already have a Sports Watch List account, log in
              <router-link to="login" class="font-semibold hover:underline"
                >here</router-link
              >.
            </p>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import moment from "moment";

import Header from "../components/Header";

export default {
  components: {
    "app-header": Header,
  },
  data() {
    return {
      showDialog: false,
      dialogShown: false,
      submitting: false,
      email: "",
    };
  },
  watch: {
    loggedIn(val) {
      if (val && this.interval) {
        clearInterval(this.interval);
      }
    },
  },
  created() {},
  mounted() {
    if (this.loggedIn) return;

    if (!this.dialogShown) {
      setTimeout(() => this.showDialog = true, 5000);
    }
  },
  beforeDestroy() {
    document.body.removeEventListener("click", this.clickAwayListener);
  },
  computed: {
    date() {
      return this.$store.state.date;
    },
    loggedIn() {
      return this.$store.getters.isLoggedIn;
    },
    prettyDate() {
      return moment(new Date(this.date)).format("MMM D, YYYY");
    },
    theYear() {
      return new Date().getFullYear();
    },
    leagues() {
      return this.$store.state.leagues;
    },
    loadingLeagues() {
      return !!this.$store.state.loading.length;
    },
  },
  methods: {
    submitEmail() {
      this.submitting = true;
      this.$http
        .post("/api/subscriptions", { email: this.email })
        .then(() => {
          this.$success(
            "Thank You!",
            "Your instructions on how to create a custom scoreboard of your favorite teams will be sent to you shortly."
          );
          this.showDialog = false;
          this.dialogShown = true;
        })
        .catch(() =>
          this.$error(
            "Whoops...",
            "Unfortunately that didn't work. Please try again!"
          )
        )
        .finally(() => (this.submitting = false));
    },
    hideDialog() {
      this.dialogShown = true;
      this.showDialog = false;
    },
    setLeague(league) {
      if (this.$route.name !== "home") {
        this.$router.push("/");
      }

      this.$store.commit("setLeague", league);
    },
  },
};
</script>
