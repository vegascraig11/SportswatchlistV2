<template>
  <div class="flex">
    <div class="w-full px-2 sm:px-0">
      <game-container
        v-for="league in selectedLeagues"
        :key="`${league}-container`"
        :league="league"
      ></game-container>
    </div>
    <aside class="hidden lg:block w-full max-w-xs flex-shrink-0 pl-4 py-6">
      <div class="flex flex-col items-center">
        <div class="flex items-center">
          <svg
            class="h-6 w-6 fill-current text-gray-600"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
          >
            <path
              d="M10 0.4c-5.3 0-9.6 4.3-9.6 9.6 0 5.3 4.3 9.6 9.6 9.6 5.3 0 9.6-4.3 9.6-9.6C19.6 4.7 15.3 0.4 10 0.4zM10 17.6c-4.2 0-7.6-3.4-7.6-7.6 0-4.2 3.4-7.6 7.6-7.6 4.2 0 7.6 3.4 7.6 7.6C17.6 14.2 14.2 17.6 10 17.6zM11 9.3V4H9v6.2l-3.5 2 1 1.7 4.1-2.4C10.8 11.5 11 11.2 11 10.9v-0.2l4.2-4.2c-0.2-0.3-0.4-0.5-0.6-0.8L11 9.3z"
            />
          </svg>
          <span class="ml-2">{{ time }}</span>
        </div>
        <v-calendar
          class="mt-4"
          is-dark
          :attributes="attributes"
          @dayclick="dayClicked"
        >
        </v-calendar>

        <div class="mt-4 px-6">
          <div
            v-for="banner in banners"
            :key="banner.id"
            class="w-full mt-4 first:mt-0 rounded-lg overflow-hidden"
          >
            <a class="block" target="_blank" :href="banner.url">
              <img class="w-full" :src="`/${banner.path}`" :alt="banner.url" />
            </a>
          </div>
        </div>
      </div>
    </aside>
  </div>
</template>

<script>
import moment from "moment";
import Game from "./../containers/Game";

export default {
  components: {
    "game-container": Game,
  },
  data() {
    return {
      time: "",
      attributes: [],
      banners: [],
    };
  },
  created() {
    this.time = new Date();

    this.attributes.push({
      key: "today",
      highlight: true,
      dates: new Date(),
    });

    this.updateTime();
    this.getBanners();
  },
  computed: {
    selectedLeagues() {
      const leagues = this.$store.state.selectedLeagues;
      if (leagues.length === 0) return this.leagues;
      return leagues;
    },
    leagues() {
      return this.$store.state.leagues;
    },
  },
  methods: {
    updateTime() {
      setInterval(() => {
        this.time = moment(new Date()).toDate();
      }, 1000);
    },
    dayClicked(e) {
      this.$store.commit("setDate", moment(e.date).toString());
    },
    getBanners() {
      this.$http
        .get("/api/banners")
        .then(response => (this.banners = response.data))
        .catch(err => console.log(err));
    },
  },
};
</script>
