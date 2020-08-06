<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">{{ header }}</h2>
    <div v-if="loading" class="flex justify-center py-6">
      <loading></loading>
    </div>
    <template v-else>
      <div v-if="games.length" class="mt-6">
        <div
          v-for="game in games"
          :key="game.GameId"
          class="mt-6 first:mt-0 w-full"
        >
          <nba-game-list-item
            v-if="game.game_type === 'nba'"
            :game="game"
            :key="game.GameId"
          />
          <game-list-item v-else :game="game" />
        </div>
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
import NBAGameListItem from "./../components/NBAGameListItem";

export default {
  props: ["league"],
  components: {
    Loading,
    "game-list-item": GameListItem,
    "nba-game-list-item": NBAGameListItem,
  },
  data() {
    return {
      loading: false,
      games: [],
    };
  },
  watch: {
    date() {
      this.getGames();
    },
  },
  created() {
    this.getGames();

    window.events.$on("sort", method => {
      this.sortGames(method);
    });

    window.Echo.channel("game-updates").listen("GameStatusUpdated", e => {
      const games = e.games.filter(game => {
        return game.game_type == this.league;
      });

      if (!games.length) return;

      const updatedGames = this.games.map(game => {
        const found = games.find(g => g.game_id === "game.game_id");
        return found || game;
      });

      this.games = updatedGames;
    });
  },
  computed: {
    date() {
      return this.$store.state.date;
    },
    header() {
      return this.league.toUpperCase() + " Game List";
    },
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
      if (method === "rot") {
        this.games = this.games.sort((game1, game2) => {
          if (game1.away_team.rotation_number === null) return 1;
          if (game2.away_team.rotation_number === null) return -1;

          return (
            game1.away_team.rotation_number - game2.away_team.rotation_number
          );
        });
      } else if (method === "time") {
        this.games = this.games.sort((game1, game2) => {
          if (game1.game_time === null) return 1;
          if (game2.game_time === null) return -1;

          return moment(game1.game_time).isBefore(moment(game2.game_time))
            ? -1
            : 1;
        });
      }
    },
  },
};
</script>
