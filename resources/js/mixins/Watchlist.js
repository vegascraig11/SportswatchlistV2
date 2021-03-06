const WatchlistMixin = {
  props: {
    initialGameData: {
      type: Object,
      required: true,
    },
    notificationSettings: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      game: this.initialGameData,
      added: false,
      refreshing: false,
    };
  },
  created() {
    window.Echo.channel(`game-updates-${this.game.game_id}`).listen(
      "GameStatusUpdated",
      e => (this.game = e.game)
    );
  },
  watch: {
    game(newValue, oldValue) {
      if (
        oldValue.status === "Scheduled" &&
        newValue.status === "InProgress" &&
        (this.inWatchlist || this.added)
      ) {
        this.$success(
          `${this.game.game_type.toUpperCase()} Game is Starting`,
          `The game between ${newValue.away_team.full_name} and ${newValue.home_team.full_name} is startng. Please check your game feed for detailed game information.`
        );
      }

      if (
        oldValue.status === "InProgress" &&
        ["Final", "F/OT"].includes(newValue.status) &&
        (this.inWatchlist || this.added)
      ) {
        this.$success(
          `${this.game.game_type.toUpperCase()} Game is Ending`,
          `The game between ${newValue.away_team.full_name} and ${newValue.home_team.full_name} is ending. Please check your game feed for detailed game information.`
        );
      }
    },
  },
  computed: {
    inWatchlist() {
      return this.$store.state.watchlist.includes(this.game.game_id);
    },
    canAdd() {
      return (
        !(this.inWatchlist && this.added) &&
        !["Final", "F/OT", "Canceled", "Postponed"].includes(this.game.status)
      );
    },
    isLoggedIn() {
      return this.$store.getters.isLoggedIn;
    },
  },
  methods: {
    ordinalSuffixOf(i) {
      var j = i % 10,
        k = i % 100;
      if (j == 1 && k != 11) {
        return i + "st";
      }
      if (j == 2 && k != 12) {
        return i + "nd";
      }
      if (j == 3 && k != 13) {
        return i + "rd";
      }
      return i + "th";
    },
    addToWatchlist() {
      const promise = this.loggedIn ? this.addToDB() : this.addToLocal();

      promise
        .then(async response => {
          await this.$store.dispatch("fetchWatchlist");
          this.$success(
            "Added to Watchlist",
            `[${this.game.game_type.toUpperCase()}] ${
              this.game.away_team.full_name
            } vs ${this.game.home_team.full_name} was added to your watchlist.`
          );
          this.added = true;
        })
        .catch(err => console.log(err));
    },
    addToDB() {
      return this.$http.post("/api/watchlist", {
        gameId: this.game.game_id,
      });
    },
    addToLocal() {
      return this.$store.dispatch("addToWatchlist", this.game.game_id);
    },
    removeFromWatchlist() {
      const promise = this.loggedIn
        ? this.removeFromDb()
        : this.removeFromLocal();

      promise
        .then(response => {
          this.$store.dispatch("fetchWatchlist");
          this.$success(
            "Removed from Watchlist",
            `[${this.game.game_type.toUpperCase()}] ${
              this.game.away_team.full_name
            } vs ${
              this.game.home_team.full_name
            } was removed from your watchlist.`
          );
          this.added = false;
          this.$emit("game-removed", response);
        })
        .catch(err => console.log(err));
    },
    removeFromDb() {
      const fromWatchlist = this.$store.state.allWatchlist.find(
        game => game.game_id.toString() === this.game.game_id.toString()
      );
      return new Promise((resolve, reject) => {
        this.$http
          .delete("/api/watchlist/" + fromWatchlist.id)
          .then(() => resolve(this.game.game_id.toString()))
          .catch(reject);
      });
    },
    removeFromLocal() {
      return this.$store.dispatch("removeFromWatchlist", this.game.game_id);
    },
    saveSettings(settings) {
      this.$http
        .patch("/api/user/watchlist/" + this.game.game_id, { settings })
        .then(response => this.$refs.notificationSettings.onSaved())
        .catch(err => this.$refs.notificationSettings.onSaveError());
    },
    refresh() {
      this.refreshing = true;
      this.$http
        .get("/api/games/" + this.game.game_id)
        .then(response => (this.game = response.data))
        .catch(err => console.log(err))
        .finally(() => (this.refreshing = false));
    },
  },
};

export default WatchlistMixin;
