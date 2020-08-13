<template>
  <div>
    <div class="bg-white shadow-md rounded overflow-hidden">
      <div class="overflow-x-auto">
        <div class="inline-block min-w-full overflow-hidden">
          <table class="min-w-full table-fixed">
            <thead class="bg-gray-900 text-white text-xs uppercase">
              <tr class="whitespace-no-wrap">
                <th class="w-1/3 pl-4 pr-32 py-2 text-left flex items-strech">
                  <span class="uppercase">MLB | {{ gameTime }} </span>
                  <div
                    v-if="canAdd"
                    class="ml-2 text-white font-semibold relative"
                  >
                    <button
                      @click="addToWatchlist"
                      type="button"
                      class="absolute inset-0 flex items-center justify-center bg-mantis-500 hover:bg-mantis-600 py-1 px-2 rounded"
                    >
                      <svg
                        class="mr-1 inline-block h-3 w-3"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path
                          d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                          clip-rule="evenodd"
                          fill-rule="evenodd"
                        ></path>
                      </svg>
                      <span class="text-xs">Add to Watchlist</span>
                    </button>
                  </div>
                  <div
                    v-if="live"
                    class="flex items-center bg-mantis-500 px-2 rounded ml-2"
                  >
                    <svg
                      class="h-3 w-3 mr-1"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                    Live
                  </div>
                </th>
                <!-- <th class="px-4">{{ overUnder || "" }}</th> -->
                <th class="px-4"></th>
                <th class="px-4 text-right">{{ statusLabel }}</th>
                <th class="px-4">Money Line</th>
                <th class="px-4">Run Line</th>
                <th class="px-4">Total</th>
              </tr>
            </thead>
            <tbody>
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
                        :alt="game.away_team.full_name"
                      />
                      <div class="ml-2">
                        <p class="whitespace-no-wrap">
                          {{ game.away_team.full_name }}
                        </p>
                        <p class="text-gray-600 font-normal">
                          {{
                            awayWon
                              ? this.game.winningPitcher
                              : this.game.losingPitcher
                          }}
                        </p>
                      </div>
                    </div>
                  </div>
                </td>
                <td rowspan="2">
                  <div v-if="game.innings.length" class="border">
                    <table class="w-full">
                      <thead class="bg-swl-black-dark text-white">
                        <tr>
                          <th class="px-2">Team</th>
                          <th class="px-2">A</th>
                          <th class="px-2">H</th>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                        <tr>
                          <td>Inning</td>
                          <td>0</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Score</td>
                          <td>0</td>
                          <td>0</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </td>
                <td :class="awayClasses" class="text-right border-r pr-4">
                  {{ game.away_team.runs || "??" }}
                </td>
                <td class="text-center">
                  {{ game.away_team.money_line || "??" }}
                </td>
                <td class="text-center">
                  {{ game.away_team.point_spread_money_line || "??" }}
                </td>
                <td class="text-center">{{ game.over_under || "??" }}</td>
              </tr>
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
                        :alt="game.home_team.full_name"
                      />
                      <div class="ml-2">
                        <p class="whitespace-no-wrap">
                          {{ game.home_team.full_name }}
                        </p>
                        <p class="text-gray-600 font-normal">
                          {{
                            homeWon
                              ? this.game.winningPitcher
                              : this.game.losingPitcher
                          }}
                        </p>
                      </div>
                    </div>
                  </div>
                </td>
                <td :class="homeClasses" class="text-right border-r pr-4">
                  {{ game.home_team.runs || "??" }}
                </td>
                <td class="text-center">
                  {{ game.home_team.money_line || "??" }}
                </td>
                <td class="text-center">
                  {{ game.home_team.point_spread_money_line || "??" }}
                </td>
                <td class="text-center">{{ game.over_under || "??" }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div
        class="w-full px-2 py-2 flex items-center text-xs text-gray-700 tracking-wide border-t"
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
      <div v-if="watchlist" class="px-4 py-2 flex space-x-4 border-t">
        <button
          @click="removeFromWatchlist"
          type="button"
          class="w-full flex justify-center space-x-2 bg-red-600 rounded text-white py-2 hover:bg-red-700"
        >
          <span>Remove from Watchlist</span>
          <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </button>
        <button
          @click="toggleGameNotificationsSetting"
          type="button"
          class="w-full flex justify-center space-x-2 bg-mantis-500 rounded text-white py-2 hover:bg-mantis-600"
        >
          <span>Game Notifications</span>
          <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path
              fill-rule="evenodd"
              d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </button>
      </div>
      <GameNotificationSettings
        v-if="watchlist && settingsOpen"
        v-model="notificationSettings"
      />
    </div>
  </div>
</template>

<script>
import moment from "moment";
import momentTimezone from "moment-timezone";
import WatchlistMixin from "../mixins/Watchlist.js";
import GameNotificationSettings from "./GameNotificationSettings";

export default {
  mixins: [WatchlistMixin],
  components: {
    GameNotificationSettings,
  },
  props: {
    game: {
      type: Object,
      required: true,
    },
    watchlist: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      added: false,
      settingsOpen: false,
      notificationSettings: {},
    };
  },
  created() {
    this.added = this.inWatchlist;
  },
  computed: {
    statusLabel() {
      return this.game.status === "Final" ? "Final Score" : "Score";
    },
    live() {
      return this.game.status === "InProgress";
    },
    winner() {
      if (this.game.status !== "Final") return null;
      return this.game.home_team[this.scoreAccessor] >
        this.game.away_team[this.scoreAccessor]
        ? "home"
        : "away";
    },
    loggedIn() {
      return this.$store.getters.isLoggedIn;
    },
    gameTime() {
      return (
        momentTimezone
          .tz(this.game.game_time, "America/New_York")
          .local()
          .format("hh:mm A") +
        " " +
        this.zone
      );
    },
    originalGameTime() {
      return moment(this.game.game_time).format("hh:mm A");
    },
    zone() {
      return momentTimezone
        .tz(this.game.game_time, moment.tz.guess())
        .zoneAbbr();
    },
    overUnder() {
      if (!this.winner) return "";
      const { home_team, away_team, over_under } = this.game;
      let ou =
        home_team.score + away_team.score > over_under ? "Over" : "Under";
      return `${over_under} (${ou})`;
    },
    homeWon() {
      return this.winner === "home";
    },
    awayWon() {
      return this.winner === "away";
    },
    quarters() {
      return this.game.quarters && this.game.quarters.length > 0;
    },
    homeClasses() {
      let out = this.game.home_team.logo ? "py-2" : "py-4";
      out += this.homeWon ? " font-semibold text-green-500" : "";
      return out;
    },
    awayClasses() {
      let out = this.game.away_team.logo ? "py-2" : "py-4";
      out += this.awayWon ? " font-semibold text-green-500" : "";
      return out;
    },
    venue() {
      const { stadium } = this.game;

      if (!stadium) return "USA";

      return `${stadium.Name}, ${stadium.City}, ${
        stadium.State ? stadium.State + "," : ""
      } ${stadium.Country}`;
    },
    canAdd() {
      return (
        !(this.inWatchlist && this.added) &&
        momentTimezone
          .tz(this.game.game_time, "America/New_York")
          .local()
          .isAfter(moment())
      );
    },
  },
  methods: {
    toggleGameNotificationsSetting() {
      this.settingsOpen = !this.settingsOpen;
    },
  },
};
</script>