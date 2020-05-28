<template>
  <div>
    <game-container v-for="league in leagues" :key="`${league}-container`" :league="league"></game-container>
  </div>
</template>

<script>
import Game from './../containers/Game';

export default {
  components: {
    'game-container': Game
  },
  data() {
    return {
      leagues: ['nfl', 'ncaaf', 'nba', 'ncaab', 'mlb', 'nhl']
    }
  }
}
</script>