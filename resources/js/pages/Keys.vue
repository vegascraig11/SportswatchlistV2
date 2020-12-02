<template>
  <div>
    <h2 class="text-xl font-semibold">API Keys</h2>
    <div class="mt-4 max-w-lg space-y-4">
      <div>
        <label class="block" for="nba-api-key">NBA API Key</label>
        <input
          v-model="keys.nba"
          name="nba-api-key"
          id="nba-api-key"
          class="block mt-1 w-full px-2 py-2 bg-white rounded border focus:shadow-outline"
        />
      </div>
      <div>
        <label class="block" for="ncaab-api-key">NCAAB API Key</label>
        <input
          v-model="keys.ncaab"
          name="ncaab-api-key"
          id="ncaab-api-key"
          class="block mt-1 w-full px-2 py-2 bg-white rounded border focus:shadow-outline"
        />
      </div>
      <div>
        <label class="block" for="nfl-api-key">NFL API Key</label>
        <input
          v-model="keys.nfl"
          name="nfl-api-key"
          id="nfl-api-key"
          class="block mt-1 w-full px-2 py-2 bg-white rounded border focus:shadow-outline"
        />
      </div>
      <div>
        <label class="block" for="ncaaf-api-key">NCAAF API Key</label>
        <input
          v-model="keys.ncaaf"
          name="ncaaf-api-key"
          id="ncaaf-api-key"
          class="block mt-1 w-full px-2 py-2 bg-white rounded border focus:shadow-outline"
        />
      </div>
      <div>
        <label class="block" for="nhl-api-key">NHL API Key</label>
        <input
          v-model="keys.nhl"
          name="nhl-api-key"
          id="nhl-api-key"
          class="block mt-1 w-full px-2 py-2 bg-white rounded border focus:shadow-outline"
        />
      </div>
      <div>
        <label class="block" for="mlb-api-key">MLB API Key</label>
        <input
          v-model="keys.mlb"
          name="mlb-api-key"
          id="mlb-api-key"
          class="block mt-1 w-full px-2 py-2 bg-white rounded border focus:shadow-outline"
        />
      </div>
      <div>
        <button
          @click="update"
          type="button"
          class="px-6 py-2 text-white rounded shadow transition duration-150 ease-in"
          :class="{
            'bg-mantis-400 cursor-wait': updating,
            'bg-mantis-500 hover:bg-mantis-600 hover:shadow-lg': !updating,
          }"
        >
          Update
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  data: () => ({
    keys: {},
    updating: false,
  }),
  created() {
    this.$http
      .get("/api/keys")
      .then(({ data }) => (this.keys = data))
      .catch(err => console.log(err));
  },
  computed: {},
  methods: {
    update() {
      this.updating = true;

      this.$http
        .post("/api/keys", this.keys)
        .then(() => {
          flash({
            body: "API keys updated successfully!",
            type: "success",
          });
        })
        .catch(err => {
          flash({
            body: "Error updating API keys!",
            type: "error",
          });
        })
        .finally(() => (this.updating = false));
    },
  },
};
</script>

<style></style>
