const WatchlistMixin = {
  props: {
    initialGameData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      game: this.initialGameData,
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
    addToWatchlist() {
      const promise = this.loggedIn ? this.addToDB() : this.addToLocal();

      promise
        .then(response => {
          this.$store.dispatch("fetchWatchlist");
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
      return this.$http.delete("/api/watchlist/" + this.game.id);
    },
    removeFromLocal() {
      return this.$store.dispatch("removeFromWatchlist", this.game.game_id);
    },
  },
};

export default WatchlistMixin;
