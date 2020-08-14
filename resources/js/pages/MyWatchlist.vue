<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">My Watchlist</h2>
    <div v-if="loading" class="flex justify-center py-6">
      <loading></loading>
    </div>
    <template v-else>
      <div v-if="watchlist.length" class="mt-6">
        <div v-for="(game, index) in watchlist">
          <NBAGameListItem
            @game-removed="removeGame"
            v-if="game.game_type === 'nba'"
            :watchlist="true"
            :game="game"
            class="mt-6 first:mt-0"
          />
          <MLBGameListItem
            @game-removed="removeGame"
            v-if="game.game_type === 'mlb'"
            :watchlist="true"
            :game="game"
            class="mt-6 first:mt-0"
          />
          <NFLGameListItem
            @game-removed="removeGame"
            v-if="game.game_type === 'nfl'"
            :watchlist="true"
            :game="game"
            class="mt-6 first:mt-0"
          />
        </div>
      </div>
      <div v-else class="mt-6">
        <p>Your watchlist is currently empty!</p>
      </div>
    </template>
  </div>
</template>

<script>
import Loading from "./../components/Loading";
import NBAGameListItem from "./../components/NBAGameListItem";
import MLBGameListItem from "./../components/MLBGameListItem";
import NFLGameListItem from "./../components/NFLGameListItem";

export default {
  components: {
    Loading,
    NBAGameListItem,
    NFLGameListItem,
    MLBGameListItem,
  },
  data() {
    return {
      loading: false,
      watchlist: [],
    };
  },
  created() {
    this.loading = true;
    this.$http
      .get(`/api/games?ids=${JSON.stringify(this.$store.state.watchlist)}`)
      .then(response => (this.watchlist = response.data))
      .catch(err => console.log(err))
      .finally(() => (this.loading = false));
  },
  methods: {
    removeGame(index) {
      this.watchlist.splice(index, 1);
    },
  },
};
</script>
