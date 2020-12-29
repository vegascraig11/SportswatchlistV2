<template>
  <div>
    <div class="bg-white shadow-md rounded overflow-hidden">
      <div class="overflow-x-auto">
        <div class="inline-block min-w-full overflow-hidden">
          <table class="min-w-full sm:table-fixed">
            <thead class="bg-gray-900 text-white text-xxs sm:text-xs uppercase">
              <tr class="whitespace-no-wrap">
                <th
                  class="sm:w-1/3 pl-4 sm:pr-32 py-2 text-left flex items-strech"
                >
                  <span class="text-xs"
                    >MLB |
                    <span v-if="!(postponed || canceled)">{{ gameTime }}</span>
                  </span>
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
                <th class="sm:px-4 text-right">{{ statusLabel }}</th>
                <th class="sm:px-4">Money Line</th>
                <th class="sm:px-4">Run Line</th>
                <th class="sm:px-4">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div
                    :class="awayClasses"
                    class="sm:pl-4 sm:w-full flex relative"
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
                        class="ml-2 flex-1 justify-between space-x-2 sm:space-x-0"
                      >
                        <p class="hidden sm:block whitespace-no-wrap">
                          {{ game.away_team.full_name }}
                        </p>
                        <p class="sm:hidden whitespace-no-wrap">
                          {{ game.away_team.name }}
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
                <td :class="awayClasses" class="text-right border-r pr-4">
                  {{ game.away_team.runs || "0" }}
                </td>
                <td class="text-center">
                  {{ awayTeamMoneyLine }}
                </td>
                <td class="text-center">
                  <p>{{ awayPointSpread }}</p>
                  <p>{{ awayPointSpreadMoneyLine }}</p>
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
                    <div class="flex items-center w-full">
                      <p>{{ game.home_team.rotation_number }}</p>
                      <div
                        class="ml-2 flex-1 justify-between space-x-2 sm:space-x-0"
                      >
                        <p class="hidden sm:block whitespace-no-wrap">
                          {{ game.home_team.full_name }}
                        </p>
                        <p class="sm:hidden whitespace-no-wrap">
                          {{ game.home_team.name }}
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
                  {{ game.home_team.runs || "0" }}
                </td>
                <td class="text-center">
                  {{ homeTeamMoneyLine }}
                </td>
                <td class="text-center">
                  <p>{{ homePointSpread }}</p>
                  <p>{{ homePointSpreadMoneyLine }}</p>
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
      <div
        v-if="canAdd"
        class="sm:hidden py-1 px-2 text-white font-semibold border-t"
      >
        <button
          @click="addToWatchlist"
          type="button"
          class="flex w-full items-center justify-center bg-mantis-500 hover:bg-mantis-600 py-2 rounded"
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
      <transition name="slide-down">
        <div v-if="inGameInfoPanelOpen" class="border-t">
          <div class="flex space-x-6 justify-center py-6 px-2 sm:p-6">
            <div class="">
              <div
                class="flex flex-col space-x-2 sm:flex-row justify-between items-center"
              >
                <div class="text-2xl">{{ game.away_team.name }}</div>
                <div>
                  <p class="text-2xl sm:text-4xl">
                    {{ game.away_team.runs || "0" }}
                  </p>
                </div>
              </div>
              <div>
                <div class="grid grid-cols-3 grid-rows-2">
                  <div
                    class="col-span-1 col-start-2 flex items-center justify-center"
                  >
                    <svg
                      class="inline-block w-6 h-6 border-2 rounded border-gray-700"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                  </div>
                  <div
                    class="col-span-1 col-start-1 row-start-2 flex items-center justify-center"
                  >
                    <svg
                      class="inline-block w-6 h-6 border-2 rounded border-gray-700"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                  </div>
                  <div
                    class="col-span-1 col-start-3 row-start-2 flex items-center justify-center"
                  >
                    <svg
                      class="inline-block w-6 h-6 border-2 rounded border-gray-700"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                  </div>
                </div>
                <div class="mt-2">
                  <div class="grid grid-cols-5 gap-1">
                    <span class="inline-block">B</span>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                  </div>
                  <div class="grid grid-cols-5 gap-1">
                    <span class="inline-block">S</span>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                  </div>
                  <div class="grid grid-cols-5 gap-1">
                    <span class="inline-block">O</span>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                    <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <p class="text-center">{{ stringTime }}</p>
              <div class="border mt-2">
                <table class="w-full">
                  <thead class="bg-swl-black-dark text-white">
                    <tr>
                      <th class="px-1 sm:px-2">&nbsp;</th>
                      <th class="px-1 sm:px-2" v-for="inning in innings">
                        {{ inning.InningNumber }}
                      </th>
                      <th class="px-1 sm:px-2">R</th>
                      <th class="px-1 sm:px-2">H</th>
                      <th class="px-1 sm:px-2">E</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    <tr>
                      <td class="px-2">{{ game.away_team.name }}</td>
                      <td
                        v-for="inning in innings"
                        :key="`away-run-${inning.InningID}`"
                      >
                        {{ inning.AwayTeamRuns || "0" }}
                      </td>
                      <td class="bg-gray-200">{{ game.away_team.runs }}</td>
                      <td class="bg-gray-200">{{ game.away_team.hits }}</td>
                      <td class="bg-gray-200">{{ game.away_team.errors }}</td>
                    </tr>
                    <tr>
                      <td class="px-2">{{ game.home_team.name }}</td>
                      <td
                        v-for="inning in game.innings"
                        :key="`home-run-${inning.InningID}`"
                      >
                        {{ inning.HomeTeamRuns || "0" }}
                      </td>
                      <td class="bg-gray-200">{{ game.home_team.runs }}</td>
                      <td class="bg-gray-200">{{ game.home_team.hits }}</td>
                      <td class="bg-gray-200">{{ game.home_team.errors }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p
                v-if="canceled"
                class="text-center px-6 mt-2 font-semibold text-gray-700"
              >
                Canceled
              </p>
            </div>
            <div class="">
              <div
                class="flex flex-col flex-col-reverse space-x-2 sm:flex-row justify-between items-center"
              >
                <div>
                  <p class="text-2xl sm:text-4xl">
                    {{ game.home_team.runs || "0" }}
                  </p>
                </div>
                <div class="text-2xl">{{ game.home_team.name }}</div>
              </div>
            </div>
          </div>
          <div
            class="bg-gray-200 grid grid-cols-2 text-center py-2 font-semibold"
          >
            <p>
              <span class="text-gray-600">Pitcher:</span>
              <span>{{ game.current_pitcher }}</span>
            </p>
            <p>
              <span class="text-gray-600">At Bat:</span>
              <span>{{ game.current_hitter }}</span>
            </p>
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
          v-if="watchlist"
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
import GameNotificationSettings from "./GameNotificationSettings";

export default {
  mixins: [WatchlistMixin],
  components: {
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
      game: this.initialGameData,
      added: false,
      settingsOpen: false,
      inGameInfoPanelOpen: false,
      emptyInnings: [],
    };
  },
  created() {
    this.added = this.inWatchlist;

    this.emptyInnings = Array.from(Array(9).keys()).map(key => {
      return {
        InningID: `empty-inning-${key}`,
        GameID: this.game.game_id,
        InningNumber: key + 1,
        AwayTeamRuns: 0,
        HomeTeamRuns: 0,
      };
    });
  },
  computed: {
    statusLabel() {
      return this.game.status === "Final" ? "Final Score" : "Score";
    },
    postponed() {
      return this.game.status === "Postponed";
    },
    canceled() {
      return this.game.status === "Canceled";
    },
    live() {
      return this.game.status === "InProgress";
    },
    winner() {
      if (this.game.status !== "Final") return null;
      return this.game.home_team.runs > this.game.away_team.runs
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
    innings() {
      return this.game.innings.length ? this.game.innings : this.emptyInnings;
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
        ? "+" + this.game.point_spread
        : this.game.point_spread;
    },
    awayPointSpread() {
      if (!this.game.point_spread) return null;
      const num = -1 * this.game.point_spread;
      return num >= 0 ? "+" + num : num;
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
