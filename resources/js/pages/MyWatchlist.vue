<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">My Watchlist</h2>
    <div v-if="loading" class="flex justify-center py-6">
      <loading></loading>
    </div>
    <template v-else>
      <div v-if="watchlist.length" class="mt-6">
        <p>Your watchlist</p>
      </div>
      <div v-else class="mt-6">
        <p>Your watchlist is currently empty!</p>
      </div>
    </template>
  </div>
</template>

<script>
import Loading from './../components/Loading';

export default {
  components: {
    Loading
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