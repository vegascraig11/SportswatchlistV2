<template>
  <div
    class="min-h-screen flex flex-col text-gray-900 bg-gray-100 font-inter text-xs antialiased"
  >
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
              <a href="#" class="ml-4">FAQ's</a>
              <div v-if="loggedIn" class="ml-4">
                <p class="inline-block font-semibold">{{ username }}</p>
                <p class="ml-2 inline-block cursor-pointer" @click="logout">Logout</p>
              </div>
              <div v-else class="ml-4 pl-4 border-l border-gray-700">
                <router-link to="/signup">Sign Up</router-link>
                <router-link to="/login" class="ml-4 px-4 py-2 bg-mantis-500 rounded-sm">Login</router-link>
              </div>
            </nav>
            <button class="lg:hidden group relative p-4">
              <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="fill-current h-6 w-6"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path></svg>
              <div class="mt-1 text-sm invisible absolute right-0 w-48 py-1 bg-gray-800 border border-gray-800 rounded overflow-hidden group-hover:visible z-50">
                <router-link class="block py-2 hover:bg-gray-900" to="/my-watchlist">My Watchlist</router-link>
                <router-link class="block py-2 hover:bg-gray-900" to="/faq">FAQ's</router-link>
                <div v-if="loggedIn" class="border-t mt-2">
                  <p class="mb-2 block py-2 font-semibold">{{ username }}</p>
                  <p class="block py-2 cursor-pointer hover:bg-gray-900" @click="logout">Logout</p>
                </div>
                <div v-else class="px-2 border-t mt-2">
                  <router-link to="/signup" class="block py-2 mb-2 hover:bg-gray-900">Sign Up</router-link>
                  <router-link to="/login" class="block py-2 px-4 py-2 bg-mantis-500 hover:bg-green-500 rounded-sm">Login</router-link>
                </div>
              </div>
            </button>
          </div>
        </div>
      </div>
      <div class="bg-swl-black-dark">
        <div class="flex flex-wrap items-stretch text-white">
          <div
            class="relative w-1/2 md:w-1/4 flex items-center justify-center bg-mantis-500 text-center cursor-pointer"
            @click="openSportDropdown"
          >
            <div v-if="sportDropdown" class="absolute top-0 left-0 right-0 mt-12 z-50">
              <ul class="w-full bg-swl-black-light">
                <li v-for="league in leagues" @click="toggleLeague(league)" :key="`toggle-${league}-button`" class="relative py-2 hover:bg-gray-700">
                  <span class="uppercase">{{ league }}</span>
                  <span class="absolute right-0 mr-4" :class="selectedLeagues.includes(league) ? 'text-mantis-500' : 'text-gray-800'">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                  </span>
                </li>
              </ul>
            </div>
            <span class="py-4">Sports</span>
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
          <div class="flex items-center justify-center w-1/2 md:w-24 bg-swl-black-lighter text-center cursor-pointer px-4">
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
          <div class="flex overflow-x-auto">
            <div class="flex items-stretch justify-center flex-shrink-0 text-center">
              <v-date-picker
                :attributes="attributes"
                is-dark
                :is-required="true"
                @input="updateDate"
                :value="date"
                :popover="{ placement: 'bottom', visibility: 'click' }"
              >
                <div class="h-full flex items-center px-4 cursor-pointer">
                  <span class="block h-full flex items-center">
                    <svg
                      class="h-5 w-5 text-white"
                      aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 448 512"
                    >
                      <path
                        fill="currentColor"
                        d="M148 288h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm108-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 96v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm192 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-260v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"
                      />
                    </svg>
                  </span>
                  <span class="pl-2">{{ prettyDate }}</span>
                </div>
              </v-date-picker>
            </div>
            <div class="flex items-center py-2">
              <button @click="previousDay" type="button" class="px-2">
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
                        class="flex flex-col mx-1 leading-tight font-semibold"
                        :class="index === 3 ? 'text-white' : ''"
                        type="button"
                        @click="setDate(index)"
                      >
                        <span class="text-xs uppercase">{{ day.day }}</span>
                        <span>{{ day.date }}</span>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <button @click="nextDay" type="button" class="px-2">
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
          <div class="w-full md:w-auto px-6 md:px-0 py-1 relative flex items-center">
            <div class="absolute top-0 bottom-0 left-0 flex items-center pl-8 md:pl-2">
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
              type="text"
              class="w-full pl-8 pr-4 py-2 bg-swl-black-light rounded placeholder-gray-400"
              placeholder="Search"
            />
          </div>
        </div>
      </div>
    </header>
    <section class="flex-1">
      <main class="w-full max-w-6xl mx-auto sm:px-4">
        <router-view></router-view>
      </main>
    </section>
    <footer>
      <div class="py-8 bg-swl-black-light text-white">
        <nav
          class="container mx-auto px-4 flex justify-end text-xl font-semibold tracking-wide"
        >
          <router-link class="ml-4" to="/games/nfl">NFL</router-link>
          <router-link class="ml-4" to="/games/ncaaf">NCAAF</router-link>
          <router-link class="ml-4" to="/games/nba">NBA</router-link>
          <router-link class="ml-4" to="/games/ncaab">NCAAB</router-link>
          <router-link class="ml-4" to="/games/mlb">MLB</router-link>
          <router-link class="ml-4" to="/games/nhl">NHL</router-link>
        </nav>
      </div>
      <div class="bg-swl-black-lighter text-white py-8">
        <div class="container mx-auto px-4 text-center">
          <div class="inline-block uppercase tracking-wide font-semibold">
            <div class="flex">
              <a href="#">Advertise with Us</a>
              <span class="px-4">|</span>
              <a href="#">Privacy Policy</a>
              <span class="px-4">|</span>
              <a href="#">Sports Betting</a>
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
    <flash-message />
  </div>
</template>

<script>
import moment from "moment";
import FlashMessage from "./../components/FlashMessage";

export default {
  components: {
    FlashMessage
  },
  data() {
    return {
      sportDropdown: false,
      attributes: [],
      time: new Date()
    }
  },
  watch: {
    date(newValue) {
      this.buildWeekRow(newValue);
    }
  },
  created() {
    this.buildWeekRow();

    this.attributes.push({
      key: 'today',
      highlight: true,
      dates: new Date()
    });

    this.updateTime();
  },
  computed: {
    date() {
      return this.$store.state.date
    },
    username() {
      return this.$store.state.user.name;
    },
    loggedIn() {
      return this.$store.getters.isLoggedIn;
    },
    prettyDate() {
      return moment(this.date).format("MMM D, YYYY");
    },
    theYear() {
      return new Date().getFullYear();
    },
    leagues() {
      return this.$store.state.leagues;
    },
    selectedLeagues() {
      return this.$store.state.selectedLeagues;
    }
  },
  methods: {
    updateDate(e) {
      this.$store.commit('setDate', moment(e).toString())
    },
    buildWeekRow(start) {
      const week = [];

      const date = start ? start : new Date();

      for (let i = -3; i < 4; i++) {
        const day = moment(date).add(i, "days");

        week.push({
          dateTime: day,
          date: day.format("D"),
          day: day.format("ddd")
        });
      }

      this.week = week;
    },
    setDate(index) {
      this.$store.commit('setDate', this.week[index].dateTime);
    },
    previousDay() {
      this.$store.commit('setDate', moment(this.date).subtract(1, "days").toString());
    },
    nextDay() {
      this.$store.commit('setDate', moment(this.date).add(1, "days").toString());
    },
    updateTime() {
      setInterval(() => {
        this.time = moment(new Date()).toDate();
      }, 1000);
    },
    dayClicked(e) {
      this.updateDate(e.date);
    },
    openSportDropdown(e) {
      e.stopPropagation();

      this.sportDropdown = true;

      document.querySelector('body').addEventListener('click', this.bodyClickListener);
    },
    closeSportDropdown(e) {
      e.stopPropagation();

      this.sportDropdown = false;

      document.querySelector('body').removeEventListener('click', this.bodyClickListener);
    },
    bodyClickListener(e) {
      this.closeSportDropdown(e);
    },
    logout() {
      this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/')
        })
        .catch(err => {
          console.log('Error logging out', err)
        })
    },
    toggleLeague(league) {
      this.$store.commit('toggleLeague', league);
    }
  }
}
</script>