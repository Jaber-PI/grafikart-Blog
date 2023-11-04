// Page Scroller
const scroller = document.querySelector(".scroller");

window.addEventListener("scroll", () => {
  let scrollerWidth =
    (document.documentElement.scrollTop /
      (document.documentElement.scrollHeight -
        document.documentElement.clientHeight)) *
    100;
  scroller.style.width = scrollerWidth + "%";
});

// Scroll To Top
const upButton = document.querySelector(".scrollToTop");
upButton.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});
window.onscroll = function () {
  this.scrollY >= 1000
    ? (upButton.style.right = "20px")
    : (upButton.style.right = "-30px");
};
