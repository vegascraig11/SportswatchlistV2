<template>
    <div>
        <div @click="toggle" class="border border-gray-400 bg-gray-400 rounded-full">
            <div class="relative w-8 h-4 rounded-full" :class="checked ? 'bg-green-500' : 'bg-gray-400'">
                <div class="slide absolute pin-y h-4 w-4 bg-white rounded-full left-0" style="transform: translateX(0);" :style="checked ? 'transform: translateX(100%)' : ''"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            checked: false
        }
    },
    methods: {
        toggle() {
            this.checked = !this.checked
        }
    }
}
</script>

<style>
.slide {
    transition: all 100ms ease;
}
</style>