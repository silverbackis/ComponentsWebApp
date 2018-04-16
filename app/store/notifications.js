export const state = () => ({
  notifications: []
})

export const mutations = {
  addNotification (state, msg) {
    let noti = {
      id: new Date().getTime() + ':' + state.notifications.length,
      message: msg
    }
    state.notifications.push(noti)
  },
  removeNotification (state, index) {
    state.notifications.splice(index, 1)
  },
  clearNotifications (state) {
    state.notifications = {}
  }
}

export const getters = {
  getNotifications: (state) => () => {
    return state.notifications
  }
}
