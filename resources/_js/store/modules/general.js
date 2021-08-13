// initial state
import {TOGGLE_MODAL, OPEN_MODAL, CLOSE_MODAL} from '../mutations'

const state = () => ({
   modal: {
      open: false,
   },
})

// getters
const getters = {}

// actions
const actions = {}

// mutations
const mutations = {
   [TOGGLE_MODAL]: (state) => {
      state.modal.open = !state.modal.open
   },
   [OPEN_MODAL]: (state) => {
      state.open = true
   },
   [CLOSE_MODAL]: (state) => {
      state.open = false
   },
}

export default {
   state,
   getters,
   actions,
   mutations,
}
