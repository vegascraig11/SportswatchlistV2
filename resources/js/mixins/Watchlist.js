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
    };
  },
  created() {
    window.Echo.channel(`game-updates-${this.game.game_id}`).listen(
      "GameStatusUpdated",
      e => {
        this.game = e.game;
        console.log(
          `game ${e.game.game_id} updated -- status ${e.game.status}`
        );
      }
    );
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
          flash({
            body: "Added to watchlist successfully!",
            type: "success",
          });
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
          flash({
            body: "Removed from watchlist successfully!",
            type: "success",
          });
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
  },
};

export default WatchlistMixin;
