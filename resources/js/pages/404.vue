<template>
    <div class="h-full">
        <div class="py-12 leading-tight">
            <h2 class="text-gray-800 text-5xl font-bold">404</h2>
            <p class="text-xl text-gray-600">
                {{ message }}... Nothing to see here!
            </p>
            <p class="mt-4">
                Go
                <router-link to="/" class="font-semibold hover:underline"
                    >Home</router-link
                >
            </p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            messages: ["Dang", "Whoops", "Bloop", "Blep", "Oh snap"],
        };
    },
    computed: {
        message() {
            return this.messages[
                Math.floor(Math.random() * this.messages.length)
            ];
        },
    },
};
</script>
