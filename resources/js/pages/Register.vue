<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold md:text-center text-gray-500">Register</h2>

    <div class="mt-2 md:flex justify-center">
      <form class="max-w-sm" @submit.prevent="register">
        <div class="mt-2">
          <label for="firstname" class="uppercase">First Name</label>
          <div>
            <input
              class="w-full px-3 py-2 border rounded mt-1 focus:outline-none focus:ring transition duration-150 ease-in"
              :class="errors.firstname ? 'border-red-500' : ''"
              type="text"
              v-model="firstname"
              id="firstname"
              required
              placeholder="John"
            />
          </div>
          <p v-if="errors.firstname" class="text-red-700">
            {{ errors.firstname[0] }}
          </p>
        </div>
        <div class="mt-2">
          <label for="lastname" class="uppercase">Last Name</label>
          <div>
            <input
              class="w-full px-3 py-2 border rounded mt-1 focus:outline-none focus:ring transition duration-150 ease-in"
              :class="errors.lastname ? 'border-red-500' : ''"
              type="text"
              v-model="lastname"
              id="lastname"
              required
              placeholder="Doe"
            />
          </div>
          <p v-if="errors.lastname" class="text-red-700">
            {{ errors.lastname[0] }}
          </p>
        </div>
        <div class="mt-2">
          <label for="email" class="uppercase">Email</label>
          <div>
            <input
              class="w-full px-3 py-2 border rounded mt-1 focus:outline-none focus:ring transition duration-150 ease-in"
              :class="errors.email ? 'border-red-500' : ''"
              type="email"
              v-model="email"
              id="email"
              required
              placeholder="john@example.com"
            />
          </div>
          <p v-if="errors.email" class="text-red-700">{{ errors.email[0] }}</p>
        </div>
        <div class="mt-2">
          <label for="password" class="uppercase">Password</label>
          <div>
            <input
              class="w-full px-3 py-2 border rounded mt-1 focus:outline-none focus:ring transition duration-150 ease-in"
              :class="errors.password ? 'border-red-500' : ''"
              type="password"
              v-model="password"
              id="password"
              required
              placeholder="Password"
            />
          </div>
          <p v-if="errors.password" class="text-red-700">
            {{ errors.password[0] }}
          </p>
        </div>
        <div class="mt-2">
          <label for="confirmPassword" class="uppercase"
            >Confirm Password</label
          >
          <div>
            <input
              class="w-full px-3 py-2 border rounded mt-1 focus:outline-none focus:ring transition duration-150 ease-in"
              :class="errors.confirmPassword ? 'border-red-500' : ''"
              type="password"
              v-model="confirmPassword"
              id="confirmPassword"
              required
              placeholder="Confirm Password"
            />
          </div>
          <p v-if="errors.confirmPassword" class="text-red-700">
            {{ errors.confirmPassword[0] }}
          </p>
        </div>
        <div class="mt-4">
          <label for="agreement" class="flex align-center">
            <input v-model="agreement" type="checkbox" id="agreement" />
            <span class="ml-2" :class="errors.agreement ? 'text-red-500' : ''"
              >I agree to the
              <a class="underline text-gray-700 cursor-pointer" href="#"
                >terms and conditions</a
              >
              of Sports Watchlist.</span
            >
          </label>
        </div>
        <div class="mt-4">
          <button
            class="relative w-full bg-mantis-500 text-white text-sm px-4 py-3 font-semibold rounded hover:bg-opacity-90 uppercase transition duration-150 ease-in disabled:bg-opacity-90 disabled:cursor-wait"
            :disabled="working"
          >
            <span :class="{ 'text-transparent': working }">Sign Up</span>
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
export default {
  data() {
    return {
      firstname: "",
      lastname: "",
      email: "",
      password: "",
      confirmPassword: "",
      agreement: "",
      errors: {},
      working: false,
    };
  },
  methods: {
    register() {
      const {
        firstname,
        lastname,
        email,
        password,
        confirmPassword,
        agreement,
      } = this;

      if (!this.validateFields()) return;

      this.working = true;

      axios
        .post("/register", {
          name: `${firstname} ${lastname}`,
          email,
          password,
          password_confirmation: confirmPassword,
        })
        .then(response => {
          this.$success(
            "Verify Your Email",
            "Your account has been created successfully. Please verify your email address by following the instrucitons sent to your email address."
          );
          this.$router.push("/login");
        })
        .catch(err => {
          this.$error("Register Error", err.response.data.message);
          this.errors = err.response.data.errors;
          this.working = false;
        })
        .finally(() => (this.working = false));
    },
    validateFields() {
      const {
        firstname,
        lastname,
        email,
        password,
        confirmPassword,
        agreement,
      } = this;
      const errors = {};

      if (firstname.trim() === "")
        errors["firstname"] = ["First name is required."];

      if (lastname.trim() === "")
        errors["lastname"] = ["Last name is required."];

      if (email.trim() === "") errors["email"] = ["Email is required."];

      if (password === "") errors["password"] = ["Password is required."];

      if (password.length < 8)
        errors["password"] = ["Password needs to be at least 8 characters."];

      if (password !== confirmPassword)
        errors["confirmPassword"] = ["The passwords do not match."];

      if (!agreement) {
        errors["agreement"] = [
          "You have to agree to the terms and conditions.",
        ];
      }

      this.errors = errors;

      return Object.keys(this.errors).length === 0;
    },
  },
};
</script>
