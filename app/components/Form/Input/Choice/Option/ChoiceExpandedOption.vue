<template>
  <label v-if="input"
         :class="labelClass"
         :for="child.vars.id"
  >
    <input v-if="!input.vars.multiple"
           v-model="inputModel"
           v-bind="localProps"
           :type="'radio'"
           :id="child.vars.id"
           :value="child.vars.value"
    />
    <input v-else
           v-model="inputModel"
           v-bind="localProps"
           :type="'checkbox'"
           :id="child.vars.id"
           :value="child.vars.value"
    />
    <div class="indicator" v-if="isCustom"></div>
    <span class="input-label" v-html="child.vars.label"></span>
  </label>
</template>

<script>
  import InputCommonMixin from '../../_CommonMixin'
  import InputMixin from '../../Mixin/index'

  export default {
    mixins: [InputCommonMixin, InputMixin],
    props: {
      index: {
        type: Number,
        required: true
      }
    },
    computed: {
      child () {
        return this.input.children[this.index]
      },
      isCustom () {
        return this.child.vars.attr.class && this.child.vars.attr.class.indexOf('custom') !== -1
      },
      labelClass () {
        return {
          radio: !this.input.vars.multiple,
          checkbox: this.input.vars.multiple,
          custom: this.isCustom
        }
      },
      localProps () {
        let localProps = Object.assign({}, this.commonProps)
        localProps.class.push(this.child.vars.attr.class)
        console.log(localProps.class)
        return localProps
      }
    }
  }
</script>

<style lang="sass" src="~/assets/css/components/bulma_checkbox_radio.sass" />
