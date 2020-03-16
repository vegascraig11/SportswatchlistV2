<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">NCAAF Game List</h2>
    <div v-if="loading" class="flex justify-center py-6">
      <loading></loading>
    </div>
    <template v-else>
      <div v-if="games.length" class="mt-6">
        <ncaaf-game-list-item
          v-for="game in games"
          :game="game"
          :key="game.GameId"
          class="mt-6 first:mt-0 w-full"
        >
        </ncaaf-game-list-item>
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
import NCAAFGameListItem from "./../components/NCAAFGameListItem";

export default {
  // props: ["date"],
  components: {
    Loading,
    "ncaaf-game-list-item": NCAAFGameListItem
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
  computed: {
    date() {
      return this.$store.state.date;
    }
  },
  methods: {
    getGames() {
      this.loading = true;
      const formattedDate = this.getFormattedDate();

      this.$http
        .get(`api/ncaaf/gamesByDate/${formattedDate}`)
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
