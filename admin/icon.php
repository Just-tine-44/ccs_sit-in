<script>
document.addEventListener('DOMContentLoaded', function() {
  // Force favicon refresh
  const favicon = document.querySelector('link[rel="icon"]');
  if (favicon) {
    const newFavicon = favicon.cloneNode(true);
    newFavicon.href = '../images/wbccs.png?' + new Date().getTime();
    favicon.remove();
    document.head.appendChild(newFavicon);
  } else {
    const newFavicon = document.createElement('link');
    newFavicon.rel = 'icon';
    newFavicon.href = '../images/wbccs.png?' + new Date().getTime();
    newFavicon.type = 'image/png';
    document.head.appendChild(newFavicon);
  }
});
</script>