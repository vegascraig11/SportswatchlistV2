<template>
  <div>
    <div class="bg-white shadow-md rounded overflow-hidden">
      <div class="overflow-x-auto">
        <div class="inline-block min-w-full overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-900 text-white text-xxs sm:text-xs uppercase">
              <tr class="whitespace-no-wrap">
                <th class="pl-4 py-2 text-left flex items-strech">
                  <span class="text-xs"
                    >{{ game.game_type }} |
                    <span v-if="!(postponed || canceled)">{{
                      gameTime
                    }}</span></span
                  >
                  <div
                    v-if="canAdd"
                    class="hidden sm:block ml-2 text-white font-semibold"
                  >
                    <button
                      @click="addToWatchlist"
                      type="button"
                      class="inset-0 flex items-center justify-center bg-mantis-500 hover:bg-mantis-600 px-2 rounded"
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
                      class="h-3 w-3"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                    <span class="hidden sm:inline ml-1">Live</span>
                  </div>
                  <div
                    v-if="postponed"
                    class="flex items-center bg-mantis-500 px-2 rounded ml-2"
                  >
                    Postponed
                  </div>
                  <div
                    v-if="canceled"
                    class="flex items-center bg-mantis-500 px-2 rounded ml-2"
                  >
                    Canceled
                  </div>
                </th>
                <th class="sm:px-4 text-right">
                  {{ statusLabel }}
                </th>
                <th class="sm:px-4">Puck Line</th>
                <th class="sm:px-4">{{ runLineLabel }}</th>
                <th class="sm:px-4">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div
                    :class="awayClasses"
                    class="sm:pl-4 w-full flex relative"
                  >
                    <span
                      v-if="awayWon"
                      class="hidden sm:absolute top-0 bottom-0 left-0 flex items-center -mx-2"
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
                    <div class="flex items-center w-full">
                      <p>{{ game.away_team.rotation_number }}</p>
                      <div
                        class="ml-2 flex flex-1 justify-between space-x-2 sm:space-x-0"
                      >
                        <p class="hidden sm:block whitespace-no-wrap">
                          {{ game.away_team.full_name }}
                        </p>
                        <p class="sm:hidden whitespace-no-wrap">
                          {{ game.away_team.name }}
                        </p>
                      </div>
                    </div>
                  </div>
                </td>
                <td :class="awayClasses" class="text-right border-r pr-4">
                  {{ game.away_team.score || "0" }}
                </td>
                <td class="text-center">
                  <p>{{ awayPointSpread }}</p>
                  <p>{{ awayTeamMoneyLine }}</p>
                </td>
                <td class="text-center">
                  {{ awayPointSpreadMoneyLine }}
                </td>
                <td class="text-center">
                  <p>{{ game.over_under ? "(o) " + game.over_under : "??" }}</p>
                </td>
              </tr>
              <tr>
                <td>
                  <div
                    :class="homeClasses"
                    class="sm:pl-4 w-full flex relative"
                  >
                    <span
                      v-if="homeWon"
                      class="hidden sm:absolute top-0 bottom-0 left-0 flex items-center -mx-2"
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
                      <div
                        class="ml-2 flex flex-1 justify-between space-x-2 sm:space-x-0"
                      >
                        <p class="hidden sm:block whitespace-no-wrap">
                          {{ game.home_team.full_name }}
                        </p>
                        <p class="sm:hidden whitespace-no-wrap">
                          {{ game.home_team.name }}
                        </p>
                      </div>
                    </div>
                  </div>
                </td>
                <td :class="homeClasses" class="text-right border-r pr-4">
                  {{ game.home_team.score || "0" }}
                </td>
                <td class="text-center">
                  <p>{{ homePointSpread }}</p>
                  <p>{{ homeTeamMoneyLine }}</p>
                </td>
                <td class="text-center">
                  {{ homePointSpreadMoneyLine }}
                </td>
                <td class="text-center">
                  <p>{{ game.over_under ? "(u) " + game.over_under : "??" }}</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex justify-between border-t">
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
        <div class="flex items-center">
          <button
            type="button"
            v-if="inGameInfoPanelOpen"
            class="flex items-center space-x-2 mr-4 px-4 py-1 bg-mantis-500 text-white font-medium rounded hover:bg-mantis-600"
            @click="refresh"
          >
            <span
              ><svg
                class="w-4 h-4"
                :class="{ 'animate-spin': refreshing }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                ></path></svg
            ></span>
            <span>Refresh</span>
          </button>
        </div>
      </div>
      <transition name="slide-down">
        <div v-if="inGameInfoPanelOpen" class="border-t">
          <div class="grid grid-cols-7 gap-4 py-6">
            <div class="col-span-2">
              <div class="flex justify-end items-center space-x-4">
                <div class="text-2xl">{{ game.away_team.name }}</div>
                <div>
                  <p class="text-4xl">{{ game.away_team.score || "0" }}</p>
                </div>
              </div>
            </div>
            <div class="col-span-3">
              <p class="text-center">{{ stringTime }}</p>
              <div class="mt-2 flex justify-center">
                <table class="border">
                  <thead class="bg-swl-black-dark text-white">
                    <tr>
                      <th class="px-1 sm:px-2">&nbsp;</th>
                      <th class="px-1 sm:px-2" v-for="period in game.periods">
                        {{ period.Name }}
                      </th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <tr>
                      <td class="px-2">{{ game.away_team.name }}</td>
                      <td
                        v-for="period in game.periods"
                        :key="`away-run-${period.PeriodID}`"
                      >
                        {{ period.AwayScore || "0" }}
                      </td>
                    </tr>
                    <tr>
                      <td class="px-2">{{ game.home_team.name }}</td>
                      <td
                        v-for="period in game.periods"
                        :key="`home-run-${period.PeriodID}`"
                      >
                        {{ period.HomeScore || "0" }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-span-2">
              <div class="flex items-center space-x-4">
                <div>
                  <p class="text-4xl">{{ game.home_team.score || "0" }}</p>
                </div>
                <div class="text-2xl">{{ game.home_team.name }}</div>
              </div>
            </div>
          </div>
          <div class="flex bg-gray-200 py-2">
            <div class="w-1/2 text-center">{{ period }}</div>
            <div class="w-1/2 text-center">
              Time Remaining: {{ time_remaining || "-" }}
            </div>
          </div>
        </div>
      </transition>
      <GameNotificationSettings
        ref="notificationSettings"
        :notification-settings="notificationSettings"
        v-if="watchlist && settingsOpen"
        @save-settings="saveSettings"
      />
      <div
        class="flex items-center justify-between text-white font-semibold border-t"
      >
        <button
          v-if="canAdd"
          @click="addToWatchlist"
          type="button"
          class="sm:hidden flex w-full items-center justify-center bg-mantis-500 hover:bg-mantis-600 py-2"
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
        <button
          @click="toggleInGameInfoPanel"
          type="button"
          class="w-full flex justify-center space-x-2 bg-gray-800 text-white py-2 hover:bg-gray-900 transition duration-300 ease-in-out"
        >
          <svg
            class="h-4 w-4 transform transition duration-300 ease-in-out"
            :class="{
              'rotate-0': !inGameInfoPanelOpen,
              'rotate-180': inGameInfoPanelOpen,
            }"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z"
              clip-rule="evenodd"
            />
          </svg>
          <span>Live InGame Info</span>
        </button>
        <button
          v-if="watchlist && isLoggedIn"
          @click="toggleGameNotificationsSetting"
          type="button"
          class="w-full flex justify-center space-x-2 bg-mantis-500 text-white py-2 hover:bg-mantis-600"
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
        <button
          v-if="watchlist"
          @click="removeFromWatchlist"
          type="button"
          class="w-full flex justify-center space-x-2 bg-red-600 text-white py-2 hover:bg-red-700"
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
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import momentTimezone from "moment-timezone";
import WatchlistMixin from "../mixins/Watchlist.js";
import NHLPeriods from "./NHLPeriods";
import GameNotificationSettings from "./GameNotificationSettings";

export default {
  mixins: [WatchlistMixin],
  components: {
    NHLPeriods,
    GameNotificationSettings,
  },
  props: {
    watchlist: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      added: false,
      settingsOpen: false,
      inGameInfoPanelOpen: false,
    };
  },
  created() {
    this.added = this.inWatchlist;
  },
  computed: {
    statusLabel() {
      switch (this.game.status) {
        case "F/OT":
          return "F/OT";
        case "Final":
          return "Final Score";
        default:
          return "Score";
      }
    },
    live() {
      return this.game.status === "InProgress";
    },
    postponed() {
      return this.game.status === "Postponed";
    },
    canceled() {
      return this.game.status === "Canceled";
    },
    winner() {
      if (!["Final", "F/OT"].includes(this.game.status)) return null;
      return this.game.home_team.score > this.game.away_team.score
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
    stringTime() {
      return (
        momentTimezone
          .tz(this.game.game_time, "America/New_York")
          .local()
          .format("LLL") +
        " " +
        this.zone
      );
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
    runLineLabel() {
      return "Point Spread";
    },
    homeTeamMoneyLine() {
      if (this.game.home_team.money_line)
        return this.game.home_team.money_line >= 0
          ? `+${this.game.home_team.money_line}`
          : this.game.home_team.money_line;
      return "??";
    },
    awayTeamMoneyLine() {
      if (this.game.away_team.money_line)
        return this.game.away_team.money_line >= 0
          ? `+${this.game.away_team.money_line}`
          : this.game.away_team.money_line;
      return "??";
    },
    homePointSpread() {
      if (!this.game.point_spread) return null;
      return this.game.point_spread >= 0
        ? `+${this.game.point_spread}`
        : this.game.point_spread;
    },
    awayPointSpread() {
      if (!this.game.point_spread) return null;
      const spread = -1 * this.game.point_spread;
      return spread >= 0 ? `+${spread}` : spread;
    },
    homePointSpreadMoneyLine() {
      if (!this.game.home_team.point_spread_money_line) return "??";
      return this.game.home_team.point_spread_money_line > 0
        ? "+" + this.game.home_team.point_spread_money_line
        : this.game.home_team.point_spread_money_line;
    },
    awayPointSpreadMoneyLine() {
      if (!this.game.away_team.point_spread_money_line) return "??";
      return this.game.away_team.point_spread_money_line > 0
        ? "+" + this.game.away_team.point_spread_money_line
        : this.game.away_team.point_spread_money_line;
    },
    period() {
      if (["F/OT", "Final"].includes(this.game.status)) return "Final";

      if (!this.game.period) return "-";

      let period = "";
      switch (this.game.period) {
        case 1:
          return "1st";
        case 2:
          return "2nd";
        case 3:
          return "3rd";
        default:
          return this.game.period + "th";
      }
    },
    time_remaining() {
      const out = [
        this.game.time_remaining_minutes,
        this.game.time_remaining_seconds,
      ]
        .filter(item => item)
        .join(":");

      return out || "-";
    },
  },
  methods: {
    toggleGameNotificationsSetting() {
      this.settingsOpen = !this.settingsOpen;
    },
    toggleInGameInfoPanel() {
      this.inGameInfoPanelOpen = !this.inGameInfoPanelOpen;
    },
  },
};
</script>

<style>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 500ms ease-in-out;
  max-height: 500px;
}
.slide-down-enter,
.slide-down-leave-to {
  max-height: 0px;
}
</style>
