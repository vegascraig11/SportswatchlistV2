<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">My Watchlist</h2>
    <div v-if="loading" class="flex justify-center py-6">
      <loading></loading>
    </div>
    <template v-else>
      <div v-if="watchlist.length" class="mt-6">
        <div v-for="(game, index) in watchlist">
          <!-- <nba-game-list-item :game="game.details.Game"></nba-game-list-item> -->
          <p class="mb-4">Game {{ index + 1 }} - {{ game.game_type }} || {{ game.game_id }} || <span class="rounded uppercase border-l-4 border-red-900 px-2 py-1 bg-red-200 text-red-900">details - Work in Progress</span></p>
        </div>
      </div>
      <div v-else class="mt-6">
        <p>Your watchlist is currently empty!</p>
      </div>
    </template>
  </div>
</template>

<script>
import Loading from './../components/Loading';
import NBAGameListItem from './../components/NBAGameListItem';

export default {
  components: {
    Loading,
    'nba-game-list-item': NBAGameListItem
  },
  data() {
    return {
      loading: false,
      watchlist: []
    }
  },
  created() {
    this.loading = true;
    this.$http.get('/api/watchlist')
      .then(response => this.watchlist = response.data)
      .catch(err => console.log(err))
      .finally(() => this.loading = false);
  }
}
</script>