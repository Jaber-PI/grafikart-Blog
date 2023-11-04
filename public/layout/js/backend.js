// helper function to add multiple event listener
function addMultiEventListener(element, events, handler) {
  events.forEach((e) => element.addEventListener(e, handler));
}

$(function () {
  "use strict";

  // show hide password
  $(".passwordInput i").on("mouseover mouseout", function (e) {
    const $this = $(this);
    const password = $this.prev();
    const type = password.attr("type") === "password" ? "text" : "password";
    password.attr("type", type);
    $this.toggleClass("fa-eye");
  });
  // End show hide password

  // open and close boxe toggle
  $(".toggle-info").click(function () {
    $(this)
      .toggleClass("selected")
      .html(
        $(this).hasClass("selected")
          ? '<i class="fa fa-lg fa-caret-left"></i>'
          : '<i class="fa fa-lg fa-caret-down"></i>'
      )
      .parent()
      .next(".panel-body")
      .fadeToggle(100);
  });
  // End open and close boxe toggle

  // sign up form label effect/animation
  $("form.signup input, form.signup textarea").each(function () {
    if ($(this).val() === "") {
      $(this).prev("label").removeClass("active highlight");
    } else {
      $(this).prev("label").addClass("active highlight");
    }
  });

  $(".form.signup")
    .find("input, textarea")
    .on("keyup blur focus load", function (e) {
      var $this = $(this),
        label = $this.prev("label");

      if (e.type === "keyup") {
        if ($this.val() === "") {
          label.removeClass("active highlight");
        } else {
          label.addClass("active highlight");
        }
        $(this).siblings(".error").hide();
      } else if (e.type === "blur") {
        if ($this.val() === "") {
          label.removeClass("active highlight");
        } else {
          label.removeClass("highlight");
        }
      } else if (e.type === "focus") {
        if ($this.val() === "") {
          label.removeClass("highlight");
        } else if ($this.val() !== "") {
          label.addClass("highlight");
        }
      }
    });
  // End  sign up form label effect

  // signup/login tabs switch
  $(".login-page .tab a, .login-page .go-login").on("click", function (e) {
    e.preventDefault();

    $(this).parent().addClass("active");
    $(this).parent().siblings().removeClass("active");

    const target = $(this).attr("href");

    $(".tab-content > div").not(target).hide();

    $(target).fadeIn(600);
  });
  $(".login-page .go-login").on("click", function (e) {
    e.preventDefault();
    $(".login-page .signup-tab").removeClass("active");
    $(".login-page .login-tab").addClass("active");

    const target = $(this).attr("href");

    $(".tab-content > div").not(target).hide();

    $(target).fadeIn(600);
  });
  // End signup/login tabs switch

  // add-item live illustration
  $(".live-data  .live").each(function () {
    if ($(this).val()) {
      $(".live-data .live-show ." + $(this).data("live")).text($(this).val());
    }
  });

  $(".live-data  .live").keyup(function (e) {
    $(".live-data .live-show ." + $(this).data("live")).text($(this).val());
  });
  // add-item live illustration

  // item page switch to edit mode
  const itemCard = $(".item-page .item-card");
  const itemEdit = $(".item-page .item-edit");

  $(".item-page .item-card .go-edit").on("click", function () {
    itemCard.hide();
    itemEdit.fadeIn(600);
  });
});

const sliderWrapper = document.querySelectorAll(".wrapper");

sliderWrapper.forEach((wrapper) => {
  const slider = wrapper.querySelector(".slider");

  const firstCardWidth = slider.querySelector(".slider .card").offsetWidth;

  const arrowBtns = Array.prototype.filter.call(wrapper.children, (child) => child.matches('i'));
  console.log(arrowBtns);

  const sliderCards = [...slider.children];

  let isDragging = false,
    isAutoPlay = true,
    startX,
    startScrollLeft,
    timeoutId;

  // Get the number of cards that can fit in the carousel at once
  let cardPerView = Math.round(slider.offsetWidth / firstCardWidth);
  console.log(cardPerView);

  // Insert copies of the last few cards to beginning of carousel for infinite scrolling
  sliderCards
    .slice(-cardPerView)
    .reverse()
    .forEach((card) => {
      slider.insertAdjacentHTML("afterbegin", card.outerHTML);
    });
  // Insert copies of the first few cards to end of carousel for infinite scrolling
  sliderCards.slice(0, cardPerView).forEach((card) => {
    slider.insertAdjacentHTML("beforeend", card.outerHTML);
  });

  slider.scrollLeft = slider.offsetWidth;

  arrowBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      slider.scrollLeft +=
        btn.dataset.slide == "right" ? firstCardWidth : -firstCardWidth;
    });
  });

  const dragStart = (e) => {
    isDragging = true;
    slider.classList.add("dragging");
    // Records the initial cursor and scroll position of the carousel
    startX = e.pageX;
    startScrollLeft = slider.scrollLeft;
  };
  const dragging = (e) => {
    if (!isDragging) return; // if isDragging is false return from here
    // Updates the scroll position of the slider based on the cursor movement
    slider.scrollLeft = startScrollLeft - (e.pageX - startX);
  };
  const dragStop = () => {
    isDragging = false;
    slider.classList.remove("dragging");
  };

  const infiniteScroll = () => {
    // If the slider is at the beginning, scroll to the end
    if (slider.scrollLeft === 0) {
      slider.classList.add("no-transition");
      slider.scrollLeft = slider.scrollWidth - 2 * slider.offsetWidth;
      slider.classList.remove("no-transition");
    }
    // If the slider is at the end, scroll to the beginning
    else if (
      Math.ceil(slider.scrollLeft) ===
      slider.scrollWidth - slider.offsetWidth
    ) {
      slider.classList.add("no-transition");
      slider.scrollLeft = slider.offsetWidth;
      slider.classList.remove("no-transition");
    }
    // Clear existing timeout & start autoplay if mouse is not hovering over slider
    clearTimeout(timeoutId);
    if (!wrapper.matches(":hover")) autoPlay();
  };

  const autoPlay = () => {
    if (window.innerWidth < 800 || !isAutoPlay) return; // Return if window is smaller than 800 or isAutoPlay is false
    // Autoplay the slider after every 2500 ms
    timeoutId = setTimeout(() => (slider.scrollLeft += firstCardWidth), 2500);
  };
  autoPlay();

  slider.addEventListener("mousedown", dragStart);
  slider.addEventListener("mousemove", dragging);
  document.addEventListener("mouseup", dragStop);
  slider.addEventListener("scroll", infiniteScroll);
  wrapper.addEventListener("mouseenter", () => clearTimeout(timeoutId));
  wrapper.addEventListener("mouseleave", autoPlay);
});
