<template>
  <div>
    <div class="bg-white shadow-md rounded overflow-hidden">
      <div class="overflow-x-auto">
        <div class="inline-block min-w-full overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-900 text-white text-xxs sm:text-xs uppercase">
              <tr class="whitespace-no-wrap">
                <th class="pl-4 py-2 text-left flex items-strech">
                  <span class="uppercase">
                    {{ game.game_type }} |
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
                <th class="sm:px-4">{{ runLineLabel }}</th>
                <th class="sm:px-4">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr class="py-4">
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
                      <div class="ml-2 flex flex-1 items-center space-x-2">
                        <p class="hidden sm:block whitespace-no-wrap">
                          {{ game.away_team.full_name }}
                        </p>
                        <p class="sm:hidden whitespace-no-wrap">
                          {{ game.away_team.name }}
                        </p>
                        <div
                          v-if="game.possession === game.away_team.name"
                          class="w-3 h-3 bg-green-500 rounded-full"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td :class="awayClasses" class="text-right border-r pr-4">
                  {{ game.away_team.score || "0" }}
                </td>
                <td class="text-center">
                  {{ awayTeamMoneyLine }}
                </td>
                <td class="text-center">
                  {{ game.away_team.point_spread_money_line || "??" }}
                </td>
                <td class="text-center">{{ game.over_under || "??" }}</td>
              </tr>
              <tr class="py-4">
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
                      <div class="ml-2 flex flex-1 items-center space-x-2">
                        <p class="hidden sm:block whitespace-no-wrap">
                          {{ game.home_team.full_name }}
                        </p>
                        <p class="sm:hidden whitespace-no-wrap">
                          {{ game.home_team.name }}
                        </p>
                        <div
                          v-if="game.possession === game.home_team.name"
                          class="w-3 h-3 bg-green-500 rounded-full"
                        ></div>
                      </div>
                    </div>
                  </div>
                </td>
                <td :class="homeClasses" class="text-right border-r pr-4">
                  {{ game.home_team.score || "0" }}
                </td>
                <td class="text-center">
                  {{ homeTeamMoneyLine }}
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
          <div class="flex justify-center sm:space-x-4 p-6">
            <div class="flex items-center">
              <div class="w-full flex justify-end items-center">
                <div class="text-2xl">{{ game.away_team.name }}</div>
                <div class="ml-2 sm:ml-4">
                  <p class="text-2xl sm:text-4xl">
                    {{ game.away_team.score || "0" }}
                  </p>
                </div>
              </div>
            </div>
            <div class="">
              <p class="text-center font-semibold text-gray-700">
                {{ stringTime }}
              </p>
              <div class="mt-2 flex items-center justify-center">
                <div v-if="game.periods" class="border rounded overflow-hidden">
                  <table class="w-full text-center">
                    <thead class="bg-swl-black-dark text-white">
                      <tr>
                        <th>&nbsp;</th>
                        <th
                          class="px-2"
                          v-for="period in periods"
                          :key="`q-${period.PeriodID}`"
                          :class="{
                            'bg-mantis-500': game.period === period.Name,
                          }"
                        >
                          {{ period.Name }}
                        </th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      <tr>
                        <td class="px-2">{{ game.away_team.name }}</td>
                        <td
                          v-for="period in periods"
                          :key="`away-${period.PeriodID}`"
                        >
                          {{ period.AwayScore || emptyScore }}
                        </td>
                      </tr>
                      <tr>
                        <td class="px-2">{{ game.home_team.name }}</td>
                        <td
                          v-for="period in periods"
                          :key="`home-${period.PeriodID}`"
                        >
                          {{ period.HomeScore || emptyScore }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <p
                v-if="!canceled"
                class="text-center mt-2 font-semibold text-gray-700"
              >
                {{
                  game.time_remaining != ":"
                    ? `${game.time_remaining} Remaining`
                    : ""
                }}
              </p>
              <p
                v-if="game.status === 'Final'"
                class="text-center mt-2 font-semibold text-gray-700"
              >
                Final
              </p>
              <p
                v-if="game.status === 'Canceled'"
                class="text-center mt-2 font-semibold text-gray-700"
              >
                Canceled
              </p>
            </div>
            <div class="flex items-center">
              <div class="flex items-center">
                <div>
                  <p class="text-2xl sm:text-4xl">
                    {{ game.home_team.score || "0" }}
                  </p>
                </div>
                <div class="pl-4 text-2xl">{{ game.home_team.name }}</div>
              </div>
            </div>
          </div>
          <div class="relative h-4 bg-green-200">
            <!-- Start:  0.0% -->
            <!--   Mid: 48% -->
            <!--   End: 96% -->
            <div
              class="absolute inset-0 pointer-events-none flex justify-between text-green-500 font-semibold px-2"
            >
              <div>0</div>
              <div>10</div>
              <div>20</div>
              <div>30</div>
              <div>40</div>
              <div>50</div>
              <div>40</div>
              <div>30</div>
              <div>20</div>
              <div>10</div>
              <div>0</div>
            </div>
            <div
              class="absolute top-0 left-0 -mt-1 w-full -mx-2 sm:mx-0"
              :style="{ transform: `translateX(${ballLocation}%)` }"
            >
              <p class="w-4 flex justify-center absolute inset-0 -mt-4">
                <span>{{ game.yard_line }}</span>
              </p>
            </div>
            <div
              class="w-full absolute inset-0"
              :style="{ transform: `translateX(${ballLocation}%)` }"
            >
              <div class="relative">
                <div
                  class="absolute top-0 -mx-2 sm:mx-0 bg-yellow-800 h-4 w-4 rounded-full"
                ></div>
              </div>
            </div>
          </div>
          <div
            class="bg-gray-200 grid grid-cols-3 text-center py-2 font-semibold"
          >
            <p>
              <span class="text-gray-600">POSS:</span>
              <span>{{ possession.fullName }}</span>
            </p>
            <p>
              <span class="text-gray-600">DOWN:</span>
              <span
                >{{ game.down && ordinalSuffixOf(game.down) }}
                {{ game.distance && "and" + game.distance + "yrds" }}</span
              >
            </p>
            <p>
              <span class="text-gray-600">BALL ON:</span>
              <span>{{ ballOn }}</span>
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
      added: false,
      settingsOpen: false,
      inGameInfoPanelOpen: false,
      emptyPeriods: [],
    };
  },
  created() {
    this.added = this.inWatchlist;

    this.emptyPeriods = Array.from(Array(4).keys()).map(key => {
      return {
        PeriodID: `empty-period-${key}`,
        GameID: this.game.game_id,
        Name: key + 1,
        AwayScore: 0,
        HomeScore: 0,
      };
    });
  },
  computed: {
    ballLocation() {
      const dir =
        this.game.yard_line_territory === this.game.home_team.name ? "+" : "-";
      const amount = Number(dir + this.game.yard_line);

      if (isNaN(amount)) {
        return 49;
      }

      if (amount < 0) {
        return -1 * amount;
      } else {
        return 49 + (49 - amount);
      }
    },
    ballOn() {
      return `${this.game.yard_line_territory || ""} ${
        this.game.yard_line || ""
      }`;
    },
    emptyScore() {
      return this.game.has_started ? "0" : "0";
    },
    statusLabel() {
      return this.game.status === "Final" ? "Final Score" : "Score";
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
      if (this.game.status !== "Final") return null;
      return this.game.home_team[this.scoreAccessor] >
        this.game.away_team[this.scoreAccessor]
        ? "home"
        : "away";
    },
    scoreAccessor() {
      const { game } = this;

      switch (game.game_type) {
        case "mlb":
          return "runs";
        case "nba":
        default:
          return "score";
      }
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
      if (!this.game.game_time) return "";

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
      let stadium = this.game.stadium || this.game.stadium_from_game;

      if (!stadium) return "USA";

      return [stadium.Name, stadium.City, stadium.State, stadium.Country]
        .filter(entry => entry)
        .join(", ");
    },
    runLineLabel() {
      if (this.game.game_type === "mlb") {
        return "Run Line";
      } else {
        return "Point Spread";
      }
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
    possession() {
      if (this.game.possession === this.game.home_team.name) {
        return {
          name: this.game.home_team.name,
          fullName: this.game.home_team.full_name,
        };
      }
      if (this.game.possession === this.game.away_team.name) {
        return {
          name: this.game.away_team.name,
          fullName: this.game.away_team.full_name,
        };
      }
      return {
        name: "",
        fullName: "",
      };
    },
    periods() {
      return this.game.periods.length ? this.game.periods : this.emptyPeriods;
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
