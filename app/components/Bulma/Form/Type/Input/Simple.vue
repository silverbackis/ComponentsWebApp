<template>
  <div>
    <input v-if="lastBlockPrefix === 'hidden'"
           type="hidden"
           v-model="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else-if="lastBlockPrefix === 'password'"
           type="password"
           v-model="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else-if="lastBlockPrefix === 'submit'"
           type="submit"
           v-model="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else-if="lastBlockPrefix === 'button'"
           type="button"
           v-model="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else-if="lastBlockPrefix === 'date'"
           type="date"
           v-model="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else-if="lastBlockPrefix === 'time'"
           type="time"
           v-model="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else-if="lastBlockPrefix === 'datetime-local'"
           type="datetime-local"
           v-model="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else-if="lastBlockPrefix === 'email'"
           type="email"
           v-model="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else-if="lastBlockPrefix === 'number'"
           type="number"
           v-model.number="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else-if="lastBlockPrefix === 'url'"
           type="url"
           v-bind="attr"
           v-model="modelValue"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
    <input v-else
           type="text"
           v-model="modelValue"
           v-bind="attr"
           @blur="inputBlur"
           @keypress.enter="inputBlur"
    />
  </div>
</template>

<script>
  import InputMixin from './_Mixin'

  export default {
    mixins: [InputMixin]
  }
</script>
