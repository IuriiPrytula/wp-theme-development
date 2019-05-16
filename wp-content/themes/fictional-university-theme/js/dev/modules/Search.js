(function ($) {
  class Search {
    constructor() {
      this.resultsDiv = $("#search-overlay__results");
      this.openButton = $(".js-search-trigger");
      this.closeButton = $(".search-overlay__close");
      this.searchOverlay = $(".search-overlay");
      this.searchField = $("#search-term");
      this.events();
      this.isOverlayOpen = false;
      this.isSpinnerVisible = false;
      this.previousValue;
      this.typingTimer;
    }

    events() {
      this.openButton.on("click", this.openOverlay.bind(this));
      this.closeButton.on("click", this.closeOverlay.bind(this));
      $(document).on("keydown", this.keyPressDispatcher.bind(this));
      this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    typingLogic() {
      if (this.searchField.val() != this.previousValue) {
        clearTimeout(this.typingTimer);

        if (this.searchField.val()) {
          if (!this.isSpinnerVisible) {
            this.resultsDiv.html('<div class="spinner-loader"></div>');
            this.isSpinnerVisible = true;
          }
          this.typingTimer = setTimeout(this.getResults.bind(this), 750);
        } else {
          this.resultsDiv.html('');
          this.isSpinnerVisible = false;
        }
      }

      this.previousValue = this.searchField.val();
    }

    getResults() {
      $.getJSON(window.location.origin + '/wp-json/university/v1/search?term=' + this.searchField.val(), (result) => {
        let generalInfo = [];
        generalInfo = generalInfo.concat(result.posts, result.pages);
        this.resultsDiv.html(`
          <div class="row">
          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${generalInfo.length ? '<ul class="link-list min-list">' : '<p>No information matched the search</p>'}
            ${generalInfo.map(item => `<li><a href="${item.url}">${item.title}</a></li>`).join('')}
            ${generalInfo.length ? '</ul>' : ''}
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Programs</h2>
            ${result.programs.length ? '<ul class="link-list min-list">' : '<p>No information matched the search</p>'}
            ${result.programs.map(item => `<li><a href="${item.url}">${item.title}</a></li>`).join('')}
            ${result.programs.length ? '</ul>' : ''}
            <h2 class="search-overlay__section-title">Professors</h2>
            ${result.professors.length ? '<ul class="professor-cards">' : '<p>No information matched the search</p>'}
            ${result.professors.map(item => `
            <li class="professor-card__list-item">
              <a class="professor-card" href="${item.url}">
                <img class="professor-card__image" src="${item.thumbnail}">
                <span class="professor-card__name">${item.title}</span>
              </a>
            </li>
            `).join('')}
            ${result.professors.length ? '</ul>' : ''}
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Campuses</h2>
            ${result.campuses.length ? '<ul class="link-list min-list">' : '<p>No information matched the search</p>'}
            ${result.campuses.map(item => `<li><a href="${item.url}">${item.title}</a></li>`).join('')}
            ${result.campuses.length ? '</ul>' : ''}
            <h2 class="search-overlay__section-title">Events</h2>
            ${result.events.map(item => `
              <div class="event-summary">
              <a class="event-summary__date t-center" href="${item.link}">
                <span class="event-summary__month">${item.date.month}</span>
                <span class="event-summary__day">${item.date.day}</span>
              </a>
              <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="${item.link}">Poetry Day</a></h5>
                <p>${item.excerpt}</p>
              </div>
              </div>
            `).join('')}
          </div>
        </div>
        `);
        this.isSpinnerVisible = false;
      });

    }

    keyPressDispatcher(e) {
      if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
        this.openOverlay();
      }

      if (e.keyCode == 27 && this.isOverlayOpen) {
        this.closeOverlay();
      }

    }

    openOverlay() {
      this.searchOverlay.addClass("search-overlay--active");
      $("body").addClass("body-no-scroll");
      this.searchField.val('');
      setTimeout(() => this.searchField.focus(), 301);
      this.isOverlayOpen = true;
      return false;
    }

    closeOverlay() {
      this.searchOverlay.removeClass("search-overlay--active");
      $("body").removeClass("body-no-scroll");
      this.isOverlayOpen = false;
    }
  }

  var search = new Search();
})(jQuery);