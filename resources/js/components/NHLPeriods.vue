<template>
  <table class="w-full text-center">
    <thead class="bg-swl-black-dark text-white">
      <tr>
        <th
          v-for="period in periods"
          :key="`q-${period.PeriodID}`"
          class="px-2"
          :class="currentPeriod == period.Name ? 'bg-mantis-500' : ''"
        >
          {{ period.Name }}
        </th>
      </tr>
    </thead>
    <tbody class="text-center">
      <tr>
        <td v-for="period in periods" :key="`home-${period.PeriodID}`">
          {{ period.AwayScore || "0" }}
        </td>
      </tr>
      <tr>
        <td v-for="period in periods" :key="`away-${period.PeriodID}`">
          {{ period.HomeScore || "0" }}
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["periods", "currentPeriod"],
};
</script>
