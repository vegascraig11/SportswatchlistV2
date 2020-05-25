<template>
  <div>
    <nfl-container></nfl-container>
    <!-- <ncaaf-container></ncaaf-container>
    <nba-container></nba-container>
    <ncaab-container></ncaab-container>
    <mlb-container></mlb-container>
    <nhl-container></nhl-container> -->
  </div>
</template>

<script>
import NBA from './../containers/NBA';
import NFL from './../containers/NFL';
import NHL from './../containers/NHL';
import MLB from './../containers/MLB';
import NCAAB from './../containers/NCAAB';
import NCAAF from './../containers/NCAAF';

export default {
  components: {
    'nba-container': NBA,
    'nfl-container': NFL,
    'nhl-container': NHL,
    'mlb-container': MLB,
    'ncaaf-container': NCAAF,
    'ncaab-container': NCAAB,
  }
}
</script>