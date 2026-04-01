document.addEventListener("DOMContentLoaded", function () {
  // --- 1. Navbar Shadow Effect ---
  const nav = document.getElementById("mainNav");
  if (nav) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) nav.classList.add("shadow");
      else nav.classList.remove("shadow");
    });
  }

  // --- 2. Reveal Elements on Scroll ---
  const reveals = document.querySelectorAll(".reveal-on-scroll");
  const revealObserver = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 },
  );

  reveals.forEach((reveal) => revealObserver.observe(reveal));

  // --- 3. Animasi Hamburger Menu ---
  const navbarNav = document.getElementById("navbarNav");
  const animatedIcon = document.querySelector(".animated-icon");

  if (navbarNav && animatedIcon) {
    // Jalankan animasi 'X' saat menu mulai dibuka
    navbarNav.addEventListener("show.bs.collapse", function () {
      animatedIcon.classList.add("open");
    });

    // Kembalikan ke 3 garis saat menu mulai ditutup
    navbarNav.addEventListener("hide.bs.collapse", function () {
      animatedIcon.classList.remove("open");
    });
  }
});
