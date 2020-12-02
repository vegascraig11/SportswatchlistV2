<template>
  <div class="container mx-auto">
    <h2 class="text-xl">Welcome Admin</h2>
    <div class="mt-2 grid grid-cols-4 gap-6">
      <div class="col-span-1 space-y-2">
        <div>
          <router-link
            to="/admin/banners"
            class="admin-links block p-2 font-semibold text-gray-900 bg-gray-200 rounded hover:bg-gray-400 transition ease-in duration-150"
            >Banners</router-link
          >
        </div>
        <div>
          <router-link
            to="/admin/users"
            class="admin-links block p-2 font-semibold text-gray-900 bg-gray-200 rounded hover:bg-gray-400 transition ease-in duration-150"
            >Users</router-link
          >
        </div>
        <div>
          <router-link
            to="/admin/keys"
            class="admin-links block p-2 font-semibold text-gray-900 bg-gray-200 rounded hover:bg-gray-400 transition ease-in duration-150"
            >Keys</router-link
          >
        </div>
      </div>
      <div class="col-span-3 border-l pl-4">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>
