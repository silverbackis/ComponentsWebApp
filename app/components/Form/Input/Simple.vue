<template>
  <div>
    <input v-if="inputType === 'hidden'"
           v-bind="commonProps"
           :type="'hidden'"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'password'"
           v-bind="commonProps"
           :type="'password'"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'submit'"
           v-bind="commonProps"
           :type="'submit'"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'button'"
           v-bind="commonProps"
           :type="'button'"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'date'"
           v-bind="commonProps"
           :type="'date'"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'time'"
           v-bind="commonProps"
           :type="'time'"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'datetime-local'"
           v-bind="commonProps"
           :type="'datetime-local'"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'email'"
           v-bind="commonProps"
           :type="'email'"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else-if="inputType === 'number'"
           v-bind="commonProps"
           :type="'number'"
           v-on="events"
           v-model.number="inputModel"
    />
    <input v-else-if="inputType === 'url'"
           v-bind="commonProps"
           :type="'url'"
           v-on="events"
           v-model="inputModel"
    />
    <input v-else
           v-bind="commonProps"
           :type="'text'"
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
