<template>
  <div>
    <input v-if="inputType === 'hidden'"
           type="hidden"
           v-bind="commonProps"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'password'"
           type="password"
           v-bind="commonProps"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'submit'"
           type="submit"
           v-bind="commonProps"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'button'"
           type="button"
           v-bind="commonProps"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'date'"
           type="date"
           v-bind="commonProps"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'time'"
           type="time"
           v-bind="commonProps"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'datetime-local'"
           type="datetime-local"
           v-bind="commonProps"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'number'"
           type="number"
           v-bind="commonProps"
           v-on="events"
           v-model.number="inputModel"
    />
    <input v-else-if="inputType === 'url'"
           type="url"
           v-bind="commonProps"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else
           type="text"
           v-bind="commonProps"
           v-on="events"
           v-model="inputModel"
    />
  </div>
</template>

<script>
  import InputCommonMixin from './_CommonMixin'
  import InputMixin from './Mixin'

  export default {
    mixins: [InputCommonMixin, InputMixin],
    data () {
      return {
        events: {
          blur: this.inputBlur,
          'keypress.enter': this.inputBlur
        }
      }
    }
  }
</script>
