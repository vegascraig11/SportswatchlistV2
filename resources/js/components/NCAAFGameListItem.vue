<template>
  <div>
    <div class="bg-white shadow-md rounded overflow-hidden">
      <div class="overflow-x-auto">
        <div class="inline-block min-w-full overflow-hidden">
          <table class="min-w-full">
            <thead class="bg-swl-black-dark text-white text-xs uppercase">
              <tr class="whitespace-no-wrap">
                <th class="w-1/3 px-4 py-2 text-left">NCAAF | {{ gameTime }}</th>
                <th class="px-4">&nbsp;</th>
                <th class="px-4 text-right">
                  {{ game.status === "F/OT" ? "F/OT" : "Final Score" }}
                </th>
                <th class="px-4">Money Line</th>
                <th class="px-4">Point Spread</th>
                <th class="px-4">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div :class="homeClasses" class="pl-4 w-full flex relative">
                    <span
                      v-if="homeWon"
                      class="absolute top-0 bottom-0 left-0 flex items-center -mx-2"
                    >
                      <svg
                        class="h-5 w-5 text-green-500"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 192 512"
                      >
                        <path
                          fill="currentColor"
                          d="M0 384.662V127.338c0-17.818 21.543-26.741 34.142-14.142l128.662 128.662c7.81 7.81 7.81 20.474 0 28.284L34.142 398.804C21.543 411.404 0 402.48 0 384.662z"
                        />
                      </svg>
                    </span>
                    <div class="flex items-center">
                      <p>{{ game.home_team.rotation_number }}</p>
                      <img 
                        class="ml-4 h-12 w-12"
                        v-if="game.home_team.logo"
                        :src="game.home_team.logo"
                        :alt="game.home_team.full_name">
                      <p class="ml-2 whitespace-no-wrap">{{ game.home_team.full_name }}</p>
                      <!-- <p class="invisible">(1-0-0)</p> -->
                    </div>
                  </div>
                </td>
                <td rowspan="2">
                  <div v-if="periods" class="border">
                    <table class="w-full">
                      <thead class="bg-swl-black-dark text-white">
                        <tr>
                          <th
                            v-for="period in game.periods"
                            :key="`p-${period.PeriodID}`"
                          >
                            {{ period.Name }}
                          </th>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                        <tr>
                          <td
                            v-for="period in game.periods"
                            :key="`home-${period.PeriodID}`"
                          >
                            {{ period.HomeScore }}
                          </td>
                        </tr>
                        <tr>
                          <td
                            v-for="period in game.periods"
                            :key="`away-${period.PeriodID}`"
                          >
                            {{ period.AwayScore }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </td>
                <td :class="homeClasses" class="text-right border-r pr-4">
                  {{ game.home_team.score || "??" }}
                </td>
                <td class="text-center">
                  {{ game.home_team.money_line || "??" }}
                </td>
                <td class="text-center">-110</td>
                <td class="text-center">7.5 o-116</td>
              </tr>
              <tr>
                <td>
                  <div :class="awayClasses" class="pl-4 w-full flex relative">
                    <span
                      v-if="awayWon"
                      class="absolute top-0 bottom-0 left-0 flex items-center -mx-2"
                    >
                      <svg
                        class="h-5 w-5 text-green-500"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 192 512"
                      >
                        <path
                          fill="currentColor"
                          d="M0 384.662V127.338c0-17.818 21.543-26.741 34.142-14.142l128.662 128.662c7.81 7.81 7.81 20.474 0 28.284L34.142 398.804C21.543 411.404 0 402.48 0 384.662z"
                        />
                      </svg>
                    </span>
                    <div class="flex items-center">
                      <p>{{ game.away_team.rotation_number }}</p>
                      <img 
                        class="ml-4 h-12 w-12"
                        v-if="game.away_team.logo"
                        :src="game.away_team.logo"
                        :alt="game.away_team.full_name">
                      <p class="ml-2 whitespace-no-wrap">{{ game.away_team.full_name }}</p>
                      <!-- <p class="invisible">(1-0-0)</p> -->
                    </div>
                  </div>
                </td>
                <td :class="awayClasses" class="text-right border-r pr-4">
                  {{ game.away_team.score || "??" }}
                </td>
                <td class="text-center">
                  {{ game.away_team.money_line || "??" }}
                </td>
                <td class="text-center">-110</td>
                <td class="text-center">7.5 u-101</td>
              </tr>
              <tr class="border-t">
                <td colspan="6">
                  <div
                    class="w-full px-2 py-2 flex items-center text-xs text-gray-700 tracking-wide"
                  >
                    <svg
                      class="h-4 w-4 text-gray-600 fill-current"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M10 2c-2.8 0-5 2.2-5 5 0 4.8 5 11 5 11s5-6.2 5-11C15 4.2 12.8 2 10 2zM10 9.8c-1.5 0-2.7-1.2-2.7-2.7s1.2-2.7 2.7-2.7c1.5 0 2.7 1.2 2.7 2.7S11.5 9.8 10 9.8z"
                      />
                    </svg>
                    <p class="pl-2">{{ venue }}</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div>
        <button
          @click="addToWatchlist"
          type="button"
          class="w-full flex items-center justify-center bg-swl-green text-white py-2"
        >
          <span>Add to Watchlist</span>
          <svg
            class="ml-2 inline-block h-5 w-5"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 512 512"
          >
            <path
              fill="currentColor"
              d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm144 276c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92h-92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z"
            />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import momentTimezone from "moment-timezone";

export default {
  props: ["game"],
  computed: {
    gameTime() {
      return `${moment(this.game.game_time).format("hh:mm A")} ${this.zone}`;
    },
    zone() {
      return momentTimezone.tz(this.game.game_time, moment.tz.guess()).zoneAbbr();
    },
    venue() {
      const { stadium } = this.game;

      if (!stadium) return "USA";

      return `${stadium.Name}, ${stadium.Address}, ${stadium.City}, ${stadium.State}, ${stadium.Country}`;
    },
    winner() {
      const { status, home_team, away_team } = this.game;

      if (status !== "Final" && status !== "F/OT") {
        return null;
      }

      return home_team.score > away_team.score ? "home" : "away";
    },
    homeWon() {
      return this.winner === "home";
    },
    awayWon() {
      return this.winner === "away";
    },
    periods() {
      return this.game.periods.length > 0;
    },
    homeClasses() {
      let out = this.game.home_team.logo ? 'py-2' : 'py-4';
      out += this.homeWon ? " font-semibold text-green-500" : "";
      return out;
    },
    awayClasses() {
      let out = this.game.away_team.logo ? 'py-2' : 'py-4';
      out += this.awayWon ? " font-semibold text-green-500" : "";
      return out;
    }
  },
  methods: {
    addToWatchlist() {
      if (! this.loggedIn) {
        this.$router.push(`/login?r=/my-watchlist&add=ncaaf,${this.game.game_id}`)
      }

      this.$http.post('/api/watchlist', {
        gameId: this.game.game_id,
        gameType: 'ncaaf',
        gameTime: this.game.game_time
      })
        .then(response => {
          console.log(response)
        })
        .catch(err => console.log(err))
    }
  }
};
</script>
