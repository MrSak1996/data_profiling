<template>

  </template>
  
  <script>
  
  export default {
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
  };
  </script>
  