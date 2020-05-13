<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">My Watchlist</h2>
    <div v-if="loading" class="flex justify-center py-6">
      <loading></loading>
    </div>
    <template v-else>
      <div v-if="watchlist.length" class="mt-6">
        <div v-for="(game, index) in watchlist">
          <nba-game-list-item v-if="game.game_type === 'nba'" :game="game.details" class="mt-6 first:mt-0"></nba-game-list-item>
          <ncaab-game-list-item v-if="game.game_type === 'ncaab'" :game="game.details" class="mt-6 first:mt-0"></ncaab-game-list-item>
          <ncaaf-game-list-item v-if="game.game_type === 'ncaaf'" :game="game.details" class="mt-6 first:mt-0"></ncaaf-game-list-item>
          <mlb-game-list-item v-if="game.game_type === 'mlb'" :game="game.details" class="mt-6 first:mt-0"></mlb-game-list-item>
          <nhl-game-list-item v-if="game.game_type === 'nhl'" :game="game.details" class="mt-6 first:mt-0"></nhl-game-list-item>
          <!-- <nfl-game-list-item v-if="game.game_type === 'nfl'" :game="game.details" class="mt-6 first:mt-0"></nfl-game-list-item> -->
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
import NCAABGameListItem from './../components/NCAABGameListItem';
import NCAAFGameListItem from './../components/NCAAFGameListItem';
import MLBGameListItem from './../components/MLBGameListItem';
// import NFLGameListItem from './../components/NFLGameListItem';
import NHLGameListItem from './../components/NHLGameListItem';

export default {
  components: {
    Loading,
    'nba-game-list-item': NBAGameListItem,
    'ncaab-game-list-item': NCAABGameListItem,
    'ncaaf-game-list-item': NCAAFGameListItem,
    'mlb-game-list-item': MLBGameListItem,
    // 'nfl-game-list-item': NFLGameListItem,
    'nhl-game-list-item': NHLGameListItem,
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