(() => { var e = document.querySelector(".give-sale-banners-container"), n = document.querySelectorAll(".give-sale-banner-dismiss"), t = document.querySelector(".page-title-action, .wp-heading-inline, #give-in-plugin-upsells h1"), i = function (n) { var t = n.target, i = new FormData; i.append("id", t.dataset.id), document.getElementById(t.getAttribute("aria-controls")).remove(), fetch("".concat(window.GiveSaleBanners.apiRoot, "/hide"), { method: "POST", headers: { "X-WP-Nonce": window.GiveSaleBanners.apiNonce }, body: i }), 0 === e.querySelectorAll(".give-sale-banner").length && e.remove() }; t && e && (t.parentNode.insertBefore(e, t.nextSibling), e.style.display = null), n.forEach((function (e) { e.addEventListener("click", i) })) })();