export default function ({ store, error }) {
  if (store.state.error) {
    error(store.state.error)
  }
}
