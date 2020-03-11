<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">NFL Game List</h2>
    <div v-if="loading" class="flex justify-center py-6">
      <loading></loading>
    </div>
    <template v-else>
      <div v-if="games.length" class="mt-6">
        <!-- <nfl-game-list-item
          v-for="game in games"
          :game="game"
          :key="game.GameId"
          class="mt-6 first:mt-0 w-full"
        >
        </nfl-game-list-item> -->
      </div>
      <div v-else class="mt-6">
        <p>No games found for the day!</p>
      </div>
    </template>
  </div>
</template>

<script>
import moment from "moment";
import Loading from "./../components/Loading";
// import NFLGameListItem from "./../components/NFLGameListItem";

export default {
  props: ["date"],
  components: {
    Loading,
    // "nfl-game-list-item": NFLGameListItem
  },
  data() {
    return {
      loading: false,
      games: []
    };
  },
  watch: {
    date() {
      this.getGames();
    }
  },
  created() {
    this.getGames();
  },
  methods: {
    getGames() {
      this.loading = true;
      const formattedDate = this.getFormattedDate();

      this.$http
        .get(`nfl/gamesByDate/${formattedDate}`)
        .then(response => {
          this.games = Array.isArray(response.data) ? response.data : [];
        })
        .catch(() => {
          // error loading games
        })
        .finally(() => {
          if (this.getFormattedDate() === formattedDate) {
            this.loading = false;
          }
        });
    },
    getFormattedDate() {
      return moment(this.date).format("YYYY-MMM-D");
    }
  }
};
</script>
