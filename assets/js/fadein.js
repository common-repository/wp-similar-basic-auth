function fadeIn(node, element_id) {
    var start = performance.now();
    requestAnimationFrame(
        function tick(timestamp) {
        var easing = (timestamp - start) / 500; // Speed
        node.style.opacity = Math.min(easing, 1);
        if (easing < 1) {
            // Restart
            requestAnimationFrame(tick);
        } else {
            node.style.opacity = '';
        }
    }
    );
}

/*
Fire example:
---
window.onload = function() {
  element_id = "error";
  fadeIn(document.getElementById(element_id));
}
---

or

---
<body onload="fadeIn(document.getElementById('error'));">
---
*/
