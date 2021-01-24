<template>
  <div class="px-3 sm:px-0 py-6">
    <h2 class="text-xl font-semibold md:text-center text-gray-500">Login</h2>

    <div class="mt-2 md:flex justify-center" v-if="verification">
      <div class="py-12">
        <div class="flex items-center justify-center">
          <svg
            class="h-12 w-12 animate-spin text-gray-500"
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
        <p class="text-sm text-gray-700">
          We are verifying your email address...
        </p>
      </div>
    </div>
    <div class="mt-2 md:flex justify-center" v-else>
      <form class="md:max-w-sm w-full space-y-4" @submit.prevent="login">
        <p
          class="mt-2 pl-6 py-2 pr-2 border-l-4 border-red-800 bg-red-200 text-red-800"
          v-if="errors.email"
        >
          {{ errors.email[0] }}
        </p>
        <div
          v-if="emailNotVerified"
          class="mt-2 pl-6 py-2 pr-2 border-l-4 border-yellow-800 bg-yellow-200 text-yellow-800 space-y-4"
        >
          <p>
            Your email has not been verified. Please check the inbox at
            {{ email }} and follow the instrucitons to verify your email.
          </p>
          <p>Didin't recieve an email?</p>
          <button
            type="button"
            class="relative px-3 py-2 border border-yellow-800 rounded bg-yellow-300 font-medium hover:bg-opacity-50 transition ease-in duration-150 disabled:cursor-not-allowed"
            @click="resend"
            :disabled="sending"
          >
            <span :class="{ 'text-transparent': sending }"
              >Resend Verificaiton Email</span
            >
            <div
              v-if="sending"
              class="absolute inset-0 flex items-center justify-center"
            >
              <svg
                class="h-7 w-7 animate-spin"
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
        <p
          v-if="verificationLinkSent"
          class="mt-2 pl-6 py-2 pr-2 border-l-4 border-green-800 bg-green-200 text-green-800 space-y-4"
        >
          An verification email has been sent to {{ email }}. Please check your
          inbox and follow the instrucitons to verify your email.
        </p>
        <p
          v-if="successfullyVerified"
          class="mt-2 pl-6 py-2 pr-2 border-l-4 border-green-800 bg-green-200 text-green-800 space-y-4"
        >
          You have successfully verified your email address. Please login to
          continue.
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
  data: () => ({
    email: "",
    password: "",
    errors: {},
    working: false,
    emailNotVerified: false,
    sending: false,
    verificationLinkSent: false,
    successfullyVerified: false,
  }),
  computed: {
    verification() {
      return this.$route.query.verify_email === "1" && this.$route.query.path;
    },
  },
  created() {
    if (this.verification) {
      this.$http
        .get(this.verification)
        .then(() => {
          this.$success(
            "Email Verified",
            "Your email has been verified successfully."
          );
          this.$router.push({ query: {} });
          this.successfullyVerified = true;
        })
        .catch(err => console.log(err));
    }
  },
  methods: {
    login() {
      const { email, password } = this;

      this.working = true;
      this.emailNotVerified = false;
      this.verificationLinkSent = false;
      this.errors = [];
      this.successfullyVerified = false;

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
          if (err.response.status === 403) {
            this.$warn(
              "Email not Verified",
              "You need to verify your email before you access the services."
            );
            this.emailNotVerified = true;
          }
          if (err.response.status === 422) {
            this.$error("Login Error", "Failed to login!");
            this.errors = err.response.data.errors || [];
          }
        })
        .finally(() => (this.working = false));
    },
    resend() {
      this.sending = true;
      this.$http
        .post("/api/resend", { email: this.email })
        .then(() => {
          this.password = "";
          this.emailNotVerified = false;
          this.errors = [];
          this.verificationLinkSent = true;
          this.$success(
            "Verification Email Sent",
            "A verification email has been sent to your email address. Please check your inbox."
          );
        })
        .catch(err => {
          console.log(err);
          this.$error(
            "Server Error",
            "An error occured while trying to send you a verification email."
          );
        })
        .finally(() => (this.sending = false));
    },
  },
};
</script>
