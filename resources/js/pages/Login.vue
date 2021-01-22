<template>
  <div class="px-3 sm:px-0 py-6">
    <h2 class="text-xl font-semibold md:text-center text-gray-500">Login</h2>

    <div class="mt-2 md:flex justify-center">
      <form class="md:max-w-sm w-full space-y-4" @submit.prevent="login">
        <p
          class="mt-2 pl-6 py-2 pr-2 border-l-4 border-red-800 bg-red-200 text-red-800"
          v-if="errors.email"
        >
          {{ errors.email[0] }}
        </p>
        <div>
          <label for="email" class="uppercase">Email</label>
          <div>
            <input
              class="w-full px-3 py-2 border rounded mt-1 focus:ring transition duration-150 ease-in"
              type="text"
              v-model="email"
              id="email"
              required
              placeholder="john@example.com"
            />
          </div>
        </div>
        <div>
          <label for="password" class="uppercase">Password</label>
          <div>
            <input
              class="w-full px-3 py-2 border rounded mt-1 focus:ring transition duration-150 ease-in"
              type="password"
              v-model="password"
              id="password"
              required
              placeholder="Password"
            />
          </div>
        </div>
        <div>
          <button
            class="relative w-full bg-mantis-500 text-white text-sm px-4 py-3 font-semibold rounded hover:bg-opacity-90 uppercase transition duration-150 ease-in disabled:bg-opacity-90 disabled:cursor-wait"
            :disabled="working"
          >
            <span :class="{ 'text-transparent': working }">Login</span>
            <div
              v-if="working"
              class="absolute inset-0 flex items-center justify-center"
            >
              <svg
                class="h-8 w-8 animate-spin"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
              >
                <path
                  d="M6.708 6a8 8 0 1010.583 0"
                  stroke="currentColor"
                  stroke-width="2"
                />
              </svg>
            </div>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      email: "",
      password: "",
      errors: {},
      working: false,
    };
  },
  methods: {
    login() {
      const { email, password } = this;

      this.working = true;

      this.$store
        .dispatch("login", {
          email,
          password,
        })
        .then(() => {
          if (this.$store.getters.isAdmin) {
            this.$router.push("/admin");
          } else if (this.$route.query.r) {
            this.$router.push(this.$route.query.r);
          } else {
            this.$router.push("/my-watchlist");
          }
          this.$success("Welcome Back!", "You are now logged in. ");
        })
        .catch(err => {
          this.$error("Login Error", "Failed to login!");
          this.errors = err.response.data.errors;
        })
        .finally(() => (this.working = false));
    },
  },
};
</script>
