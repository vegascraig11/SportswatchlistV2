<template>
  <div>
    <h1 class="text-2xl">Users</h1>
    <div class="mt-2">
      <div class="flex items-center space-x-4">
        <button
          type="button"
          @click="exportToCSV"
          class="px-4 py-2 text-white rounded shadow transition duration-150 ease-in"
          :disabled="exporting"
          :class="{
            'bg-mantis-300 cursor-wait shadow-none': exporting,
            'bg-mantis-500 hover:bg-mantis-600 hover:shadow-lg': !exporting,
          }"
        >
          Export to CSV
        </button>
        <span
          v-if="exporting"
          title="Exporting..."
          class="block h-4 w-4 border-2 border-gray-900 rounded-full spin"
          style="border-bottom-color: transparent"
        ></span>
        <a
          v-if="showDownloadButton"
          href="/export/download"
          target="_blank"
          class="text-gray-600 hover:text-gray-900 hover:underline transition ease-in duration-150"
          >Download</a
        >
      </div>
    </div>
    <div class="divide-y">
      <div class="mt-4 grid grid-cols-3 gap-4 py-2">
        <div class="col-span-1 font-semibold uppercase text-gray-700">Name</div>
        <div class="col-span-1 font-semibold uppercase text-gray-700">
          Email
        </div>
        <div class="col-span-1 font-semibold uppercase text-gray-700">
          Joined on
        </div>
      </div>
      <template v-if="hasUsers">
        <div
          v-for="user in users"
          :key="user.id"
          class="grid grid-cols-3 gap-4 py-2"
        >
          <div class="col-span-1">{{ user.name }}</div>
          <div class="col-span-1">{{ user.email }}</div>
          <div class="col-span-1">{{ getFormattedDate(user.created_at) }}</div>
        </div>
      </template>
      <div v-else>
        <p class="my-12 text-center text-base text-gray-700">
          There are no users registered on the system.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";

export default {
  components: {},
  data: () => ({
    paginator: {
      data: [],
    },
    loading: false,
    exporting: false,
    showDownloadButton: false,
  }),
  created() {
    this.getUsers();

    window.Echo.channel("export").listen("ExportedToCSV", () => {
      this.exporting = false;
      this.showDownloadButton = true;
    });
  },
  computed: {
    users() {
      return this.paginator.data;
    },
    hasUsers() {
      return !!this.users.length;
    },
    hasPages() {
      return this.paginator.last_page > 1;
    },
  },
  methods: {
    getUsers(page = 1) {
      this.loading = true;

      this.$http
        .get("/api/users")
        .then(response => (this.paginator = response.data))
        .catch(err => console.log(err))
        .finally(() => (this.loading = false));
    },
    getFormattedDate(date) {
      return moment(date).format("LLL");
    },
    exportToCSV() {
      this.exporting = true;

      this.$http.post("/api/export").catch(err => console.log(err));
    },
  },
};
</script>

<style></style>
