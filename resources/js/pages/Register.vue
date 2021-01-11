<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">Register</h2>

    <form class="max-w-sm" @submit.prevent="register">
      <div class="mt-2">
        <label for="firstname">First Name</label>
        <div>
          <input
            class="w-full px-2 py-1 border rounded"
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
        <label for="lastname">Last Name</label>
        <div>
          <input
            class="w-full px-2 py-1 border rounded"
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
        <label for="email">Email</label>
        <div>
          <input
            class="w-full px-2 py-1 border rounded"
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
        <label for="password">Password</label>
        <div>
          <input
            class="w-full px-2 py-1 border rounded"
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
        <label for="confirmPassword">Confirm Password</label>
        <div>
          <input
            class="w-full px-2 py-1 border rounded"
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
          class="bg-mantis-500 text-white px-4 py-2 font-semibold rounded hover:bg-green-500"
        >
          Sign Up
        </button>
      </div>
    </form>
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

      axios
        .post("/register", {
          name: `${firstname} ${lastname}`,
          email,
          password,
          password_confirmation: confirmPassword,
        })
        .then(response => {
          this.$success(
            "Success",
            "Your account has been created successfully. You can now login."
          );
          this.$router.push("/login");
        })
        .catch(err => {
          this.$error("Register Error", err.response.data.message);
          this.errors = err.response.data.errors;
        });
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
