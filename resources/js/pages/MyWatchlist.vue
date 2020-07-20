<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">My Watchlist</h2>
    <div v-if="loading" class="flex justify-center py-6">
      <loading></loading>
    </div>
    <template v-else>
      <div v-if="watchlist.length" class="mt-6">
        <div v-for="({ game }, index) in watchlist">
          <game-list-item :watchlist="true" :game="game" class="mt-6 first:mt-0" />
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
import GameListItem from './../components/GameListItem';

export default {
  components: {
    Loading,
    'game-list-item': GameListItem,
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