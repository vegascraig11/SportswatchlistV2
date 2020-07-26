<template>
    <div>
        <label class="block border border-gray-400 bg-gray-400 rounded-full">
            <div class="relative w-8 h-4 rounded-full" :class="checked ? 'bg-green-500' : 'bg-gray-400'">
                <div class="slide absolute pin-y h-4 w-4 bg-white rounded-full left-0" style="transform: translateX(0);" :style="checked ? 'transform: translateX(100%)' : ''"></div>
            </div>
            <input class="hidden" type="checkbox" v-bind:checked="checked" v-on:change="$emit('change', $event.target.checked)">
        </label>
    </div>
</template>

<script>
export default {
    model: {
        prop: 'checked',
        event: 'change'
    },
    props: {
        checked: Boolean
    },
}
</script>

<style scoped="scoped">
.slide {
    transition: all 100ms ease;
}
</style>