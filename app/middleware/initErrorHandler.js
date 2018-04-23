export default function ({ store: { state }, error }) {
  if (state.error) {
    error(state.error)
  }
}
