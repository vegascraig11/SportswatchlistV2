<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">{{ header }}</h2>
    <div v-if="loading" class="flex justify-center py-6">
      <loading></loading>
    </div>
    <template v-else>
      <div v-if="games.length" class="mt-6">
        <game-list-item
          v-for="game in games"
          :game="game"
          :key="game.GameId"
          class="mt-6 first:mt-0 w-full"
        >
        </game-list-item>
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
import GameListItem from "./../components/GameListItem";

export default {
  props: ['league'],
  components: {
    Loading,
    "game-list-item": GameListItem
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

    window.events.$on('sort', method => {
      this.sortGames(method)
    })
  },
  computed: {
    date() {
      return this.$store.state.date;
    },
    header() {
      return this.league.toUpperCase() + " Game List";
    }
  },
  methods: {
    getGames() {
      this.loading = true;
      const formattedDate = this.getFormattedDate();

      this.$http
        .get(`/api/${this.league}/gamesByDate/${formattedDate}`)
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
      return moment(new Date(this.date)).format("YYYY-MMM-DD");
    },
    sortGames(method) {
      if (method === 'rot') {
        this.games = this.games.sort((game1, game2) => {
          if (game1.away_team.rotation_number === null) return 1
          if (game2.away_team.rotation_number === null) return -1

          return game1.away_team.rotation_number - game2.away_team.rotation_number
        })
      } else if (method === 'time') {
        this.games = this.games.sort((game1, game2) => {
          if (game1.game_time === null) return 1
          if (game2.game_time === null) return -1

          return moment(game1.game_time).isBefore(moment(game2.game_time)) ? -1 : 1
        })
      }
    }
  }
};
</script>
