<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">Login</h2>

    <form class="max-w-sm" @submit.prevent="login">
      <p
        class="mt-2 pl-6 py-2 pr-2 border-l-4 border-red-800 bg-red-200 text-red-800"
        v-if="errors.email"
      >
        {{ errors.email[0] }}
      </p>
      <div class="mt-2">
        <label for="email">Email</label>
        <div>
          <input
            class="w-full px-2 py-1 border rounded"
            type="text"
            v-model="email"
            id="email"
            required
            placeholder="john@example.com"
          />
        </div>
      </div>
      <div class="mt-2">
        <label for="password">Last Name</label>
        <div>
          <input
            class="w-full px-2 py-1 border rounded"
            type="password"
            v-model="password"
            id="password"
            required
            placeholder="Password"
          />
        </div>
      </div>
      <div class="mt-4">
        <button
          class="bg-mantis-500 text-white px-4 py-2 font-semibold rounded hover:bg-green-500"
        >
          Login
        </button>
      </div>
    </form>
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
    };
  },
  methods: {
    login() {
      const { email, password } = this;

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
        });
    },
  },
};
</script>
