//Reset Input form

const ll_state = document.querySelector(".state")
const ll_acres = document.querySelector(".acres")
const ll_min = document.querySelector(".min")
const ll_max = document.querySelector(".max")
const ll_reset = document.querySelector(".reset")

ll_reset.addEventListener("click", function (e) {
  ll_state.selectedIndex = "Browse All"
  ll_acres.value = ""
  ll_min.value = ""
  ll_max.value = ""
})

console.log(ll_state, ll_acres, ll_min, ll_max, ll_reset)
