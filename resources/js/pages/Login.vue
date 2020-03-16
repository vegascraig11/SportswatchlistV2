<template>
  <div class="px-2 sm:px-0 py-6">
    <h2 class="text-xl font-semibold">Login</h2>

    <form class="max-w-sm" @submit.prevent="login">
      <div class="mt-2">
        <label for="email">Email</label>
        <div><input class="w-full px-2 py-1 border rounded" type="text" v-model="email" id="email" required placeholder="john@example.com"></div>
      </div>
      <div class="mt-2">
        <label for="password">Last Name</label>
        <div><input class="w-full px-2 py-1 border rounded" type="password" v-model="password" id="password" required placeholder="Password"></div>
      </div>
      <div class="mt-4">
        <button class="bg-swl-green text-white px-4 py-2 font-semibold rounded hover:bg-green-500">Login</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: '',
      password: '',
      errors: {}
    }
  },
  methods: {
    login() {
      const { email, password } = this;

      this.$http.post('/login', { email, password })
        .then(response => console.log(response))
        .catch(err => console.log(err))
    },
  }
}
</script>