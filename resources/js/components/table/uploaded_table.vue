<template>
    <div class="relative overflow-x-auto">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-blue-100 dark:bg-blue-700 dark:text-blue-400">
          <tr>
            <th scope="col" class="px-6 py-3 rounded-s-lg">File Name</th>
            <th scope="col" class="px-6 py-3">RSBSASYSTEMGENERATEDNUMBER</th>
            <th scope="col" class="px-6 py-3 rounded-e-lg">Below 2 characters</th>
            <th scope="col" class="px-6 py-3 rounded-e-lg">Unwanted Characters</th>
            <th scope="col" class="px-6 py-3 rounded-e-lg">Invalid Date Format</th>
            <th scope="col" class="px-6 py-3 rounded-e-lg">String with Special Characters</th>
          </tr>
        </thead>
        <tbody>
          <!-- Iterate through the invalid_data prop to display data -->
          <tr v-for="item in invalid_data" :key="item.id" class="bg-white dark:bg-gray-800">
            <td class="px-6 py-4">{{ item.filename }}</td>
            <td class="px-6 py-4">{{ item.null_values }}</td>
            <td class="px-6 py-4">{{ item.below_2letters }}</td>
            <td class="px-6 py-4">{{ item.unwanted_char }}</td>
            <td class="px-6 py-4">{{ item.date_format }}</td>
            <td class="px-6 py-4">{{ item.specialchar }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { defineComponent } from 'vue';
  
  export default defineComponent({
    name: 'OnbintInvalidTable',
    props: {
      invalid_data: {
        type: Array,
        required: true,
      },
    },
    setup(props) {
      return {
        invalid_data: props.invalid_data,
      };
    },
  });
  </script>
  