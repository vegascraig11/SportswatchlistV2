<template>
  <div>
    <div class="bg-white shadow-md rounded overflow-hidden">
      <div class="overflow-x-auto">
        <div class="inline-block min-w-full overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-900 text-white text-xxs sm:text-xs uppercase">
              <tr class="whitespace-no-wrap">
                <th
                  class="sm:w-1/3 pl-4 sm:pr-32 py-2 text-left flex items-strech"
                >
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
                <!-- <th class="px-4">{{ overUnder || "" }}</th> -->
                <th class="sm:px-4 hidden sm:table-cell"></th>
                <th class="sm:px-4 text-right">{{ statusLabel }}</th>
                <th class="sm:px-4">Money Line</th>
                <th class="sm:px-4">{{ runLineLabel }}</th>
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
                      <img
                        class="ml-1 sm:ml-4 h-8 w-8 sm:h-12 sm:w-12"
                        v-if="game.away_team.logo"
                        :src="game.away_team.logo"
                        :alt="game.away_team.full_name"
                      />
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
                <td class="hidden sm:table-cell" rowspan="2">
                  <div v-if="quarters" class="border rounded overflow-hidden">
                    <table class="w-full text-center">
                      <thead class="bg-swl-black-dark text-white">
                        <tr>
                          <th
                            class="px-2"
                            v-for="quarter in game.quarters"
                            :key="`q-${quarter.QuarterID}`"
                          >
                            {{ quarter.Name }}
                          </th>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                        <tr>
                          <td
                            v-for="quarter in game.quarters"
                            :key="`away-${quarter.QuarterID}`"
                          >
                            {{ quarter.AwayScore || emptyScore }}
                          </td>
                        </tr>
                        <tr>
                          <td
                            v-for="quarter in game.quarters"
                            :key="`home-${quarter.QuarterID}`"
                          >
                            {{ quarter.HomeScore || emptyScore }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </td>
                <td :class="awayClasses" class="text-right border-r pr-4">
                  {{ game.away_team.score || "0" }}
                </td>
                <td class="text-center">
                  {{ awayTeamMoneyLine }}
                </td>
                <td class="text-center">
                  <p>{{ awayPointSpread }}</p>
                  <p>{{ game.away_team.point_spread_money_line || "??" }}</p>
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
                      <img
                        class="ml-1 sm:ml-4 h-8 w-8 sm:h-12 sm:w-12"
                        v-if="game.home_team.logo"
                        :src="game.home_team.logo"
                        :alt="game.home_team.full_name"
                      />
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
                  <p>{{ homePointSpread }}</p>
                  <p>{{ game.home_team.point_spread_money_line || "??" }}</p>
                </td>
                <td class="text-center">
                  <p>{{ game.over_under ? "(u) " + game.over_under : "??" }}</p>
                </td>
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
      <transition name="slide-down">
        <div v-if="inGameInfoPanelOpen" class="border-t">
          <div
            class="flex justify-between space-x-2 sm:space-x-0 sm:grid sm:grid-cols-7 p-2 sm:p-6"
          >
            <div class="sm:col-span-2 flex items-center">
              <div class="sm:w-full flex justify-end items-center">
                <img
                  class="h-10 w-10 sm:h-16 sm:w-16"
                  v-if="game.away_team.logo"
                  :src="game.away_team.logo"
                  :alt="game.away_team.full_name"
                />
                <div class="ml-2 sm:ml-4">
                  <p class="text-2xl sm:text-4xl">
                    {{ game.away_team.score || "0" }}
                  </p>
                </div>
              </div>
            </div>
            <div class="sm:col-span-3">
              <p class="text-center font-semibold text-gray-700">
                {{ stringTime }}
              </p>
              <div class="mt-2 flex items-center justify-center">
                <div v-if="quarters" class="border rounded overflow-hidden">
                  <table class="w-full text-center">
                    <thead class="bg-swl-black-dark text-white">
                      <tr>
                        <th>&nbsp;</th>
                        <th
                          class="px-2"
                          v-for="quarter in game.quarters"
                          :key="`q-${quarter.QuarterID}`"
                          :class="{
                            'bg-mantis-500': game.quarter === quarter.Name,
                          }"
                        >
                          {{ quarter.Name }}
                        </th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      <tr>
                        <td class="px-2">{{ game.away_team.name }}</td>
                        <td
                          v-for="quarter in game.quarters"
                          :key="`away-${quarter.QuarterID}`"
                        >
                          {{ quarter.AwayScore || emptyScore }}
                        </td>
                      </tr>
                      <tr>
                        <td class="px-2">{{ game.home_team.name }}</td>
                        <td
                          v-for="quarter in game.quarters"
                          :key="`home-${quarter.QuarterID}`"
                        >
                          {{ quarter.HomeScore || emptyScore }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <p class="text-center mt-2 font-semibold text-gray-700">
                {{
                  game.quarter_description
                    ? `${game.quarter_description} Quarter`
                    : ""
                }}
                {{
                  game.quarter_description &&
                  game.quarter_description !== "Final"
                    ? `- ${game.time_remaining} Remaining`
                    : ""
                }}
              </p>
            </div>
            <div class="sm:col-span-2 flex items-center">
              <div class="w-full flex items-center">
                <div>
                  <p class="text-2xl sm:text-4xl">
                    {{ game.home_team.score || "0" }}
                  </p>
                </div>
                <img
                  class="h-10 w-10 sm:h-16 sm:w-16 ml-2 sm:ml-4"
                  v-if="game.home_team.logo"
                  :src="game.home_team.logo"
                  :alt="game.home_team.full_name"
                />
              </div>
            </div>
          </div>
          <div class="relative h-4 bg-green-200">
            <!-- Start: 0.0% -->
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
              class="absolute top-0 left-0 -mt-1 w-full"
              :style="{ transform: `translateX(${ballLocation}%)` }"
            >
              <p class="absolute inset-0 -mt-4 ml-2">{{ game.yard_line }}</p>
            </div>
            <div
              class="absolute top-0 left-0 -mt-1 w-full"
              :style="{ transform: `translateX(${ballLocation}%)` }"
            >
              <svg
                class="w-8 opacity-75"
                style="transform: rotateZ(-30deg)"
                xmlns="http://www.w3.org/2000/svg"
                version="1.0"
                viewBox="0 0 510 400"
              >
                <path
                  d="M688.571 482.362a277.143 150 0 11-554.285 0 277.143 150 0 11554.285 0z"
                  transform="matrix(.86595 .49996 -.54818 .94948 163.673 -463.585)"
                  fill="maroon"
                  stroke="#ff0"
                />
                <path
                  fill="#fff"
                  d="M161.615 90.34l13.71 7.915-42.891 74.288-13.71-7.915zM191.092 107.77l13.71 7.916-42.891 74.288-13.71-7.915zM221.196 124.813l13.71 7.915-42.891 74.288-13.71-7.915zM250.673 142.243l13.71 7.915-42.89 74.288-13.71-7.915zM280.714 158.448l13.71 7.915-42.891 74.289-13.71-7.915zM340.296 192.92l13.709 7.915-42.89 74.289-13.71-7.915zM369.772 210.351l13.71 7.915-42.89 74.289-13.71-7.915zM309.974 175.526l13.709 7.915-42.89 74.288-13.71-7.915z"
                />
                <path
                  fill="#fff"
                  d="M152.658 124.184l203.837 117.685-9.324 16.15-203.837-117.686z"
                />
              </svg>
            </div>
          </div>
          <div
            class="mt-2 bg-gray-200 grid grid-cols-3 text-center py-2 font-semibold"
          >
            <p>
              <span class="text-gray-600 uppercase">Poss:</span>
              <span>{{ possession.fullName }}</span>
            </p>
            <p>
              <span class="text-gray-600">DOWN:</span>
              <span>{{ game.down_and_distance }}</span>
            </p>
            <p>
              <span class="text-gray-600">BALL ON:</span>
              <span>{{ ballOn }}</span>
            </p>
          </div>
        </div>
      </transition>
      <div
        class="flex items-center justify-between text-white font-semibold border-t"
      >
        <button
          v-if="canAdd"
          @click="addToWatchlist"
          type="button"
          class="sm:hidden flex w-full items-center justify-center bg-mantis-500 hover:bg-mantis-600 py-2 rounded-bl"
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
          v-if="watchlist"
          @click="removeFromWatchlist"
          type="button"
          class="w-full flex justify-center space-x-2 bg-red-600 rounded-br text-white py-2 hover:bg-red-700"
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
      inGameInfoPanelOpen: false,
    };
  },
  created() {
    this.added = this.inWatchlist;
  },
  computed: {
    ballLocation() {
      const dir =
        this.game.yard_line_territory === this.game.home_team.name ? "+" : "-";
      const amount = Number(dir + this.game.yard_line);
      if (amount < 0) {
        return -1 * amount;
      } else {
        return amount + 48;
      }
    },
    ballOn() {
      return `${this.game.yard_line_territory || ""} ${
        this.game.yard_line || ""
      }`;
    },
    emptyScore() {
      return this.game.has_started ? "0" : "-";
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
