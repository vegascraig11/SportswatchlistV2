<template>
  <table class="w-full text-center">
    <thead class="bg-swl-black-dark text-white">
      <tr>
        <th
          v-for="quarter in quarters"
          :key="`q-${quarter.QuarterID}`"
          class="px-2"
          :class="currentQuarter == quarter.Name ? 'bg-mantis-500' : ''"
        >
          {{ quarter.Name }}
        </th>
      </tr>
    </thead>
    <tbody class="text-center">
      <tr>
        <td v-for="quarter in quarters" :key="`home-${quarter.QuarterID}`">
          {{ quarter.AwayScore || "-" }}
        </td>
      </tr>
      <tr>
        <td v-for="quarter in quarters" :key="`away-${quarter.QuarterID}`">
          {{ quarter.HomeScore || "-" }}
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["quarters", "currentQuarter"],
};
</script>
