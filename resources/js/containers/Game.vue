<template>
  <div>
    <template class="px-2 sm:px-0 py-6">
      <h2 class="text-xl font-semibold mt-6">{{ header }}</h2>
      <div v-if="loading" class="flex justify-center py-6">
        <loading></loading>
      </div>
      <template v-else>
        <div v-if="games.length" class="mt-6">
          <div
            v-for="game in results"
            :key="'game' + game.game_id"
            class="mt-6 first:mt-0 w-full"
          >
            <NBAGameListItem
              v-if="game.game_type === 'nba'"
              :initialGameData="game"
              :key="game.game_id"
            />
            <MLBGameListItem
              v-if="game.game_type === 'mlb'"
              :initialGameData="game"
              :key="game.game_id"
            />
            <NFLGameListItem
              v-if="game.game_type === 'nfl'"
              :initialGameData="game"
              :key="game.game_id"
            />
            <NHLGameListItem
              v-if="game.game_type === 'nhl'"
              :initialGameData="game"
              :key="game.game_id"
            />
            <NCAABGameListItem
              v-if="game.game_type === 'ncaab'"
              :initialGameData="game"
              :key="game.game_id"
            />
            <NCAAFGameListItem
              v-if="game.game_type === 'ncaaf'"
              :initialGameData="game"
              :key="game.game_id"
            />
          </div>
        </div>
        <div v-else class="mt-6">
          <p>No games found for the day!</p>
        </div>
      </template>
    </template>
  </div>
</template>

<script>
import moment from "moment";
import Loading from "./../components/Loading";
import NBAGameListItem from "./../components/NBAGameListItem";
import MLBGameListItem from "./../components/MLBGameListItem";
import NFLGameListItem from "./../components/NFLGameListItem";
import NHLGameListItem from "./../components/NHLGameListItem";
import NCAABGameListItem from "./../components/NCAABGameListItem";
import NCAAFGameListItem from "./../components/NCAAFGameListItem";

export default {
  props: ["league"],
  components: {
    Loading,
    NBAGameListItem,
    MLBGameListItem,
    NFLGameListItem,
    NHLGameListItem,
    NCAABGameListItem,
    NCAAFGameListItem,
  },
  data() {
    return {
      loading: false,
      games: [],
      results: [],
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

    window.events.$on("search", e => {
      this.results = this.games.filter(game => {
        return (
          game.away_team.full_name.toLowerCase().includes(e) ||
          game.away_team.name.toLowerCase().includes(e) ||
          game.home_team.full_name.toLowerCase().includes(e) ||
          game.home_team.name.toLowerCase().includes(e)
        );
      });
    });

    window.events.$on("clear-search", () => (this.results = this.games));
  },
  computed: {
    date() {
      return this.$store.state.date;
    },
    header() {
      return this.league.toUpperCase() + " Games";
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
          this.results = this.games;
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
        this.results = this.results.sort((game1, game2) => {
          if (game1.away_team.rotation_number === null) return 1;
          if (game2.away_team.rotation_number === null) return -1;

          return (
            game1.away_team.rotation_number - game2.away_team.rotation_number
          );
        });
      } else if (method === "time") {
        this.results = this.results.sort((game1, game2) => {
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
