setTimeout(onUserInactivity, 120 * 1000)
// 20 minutes = 1200000 milliseconds
function onUserInactivity() {
  window.location.href = "../logout.php"
}
