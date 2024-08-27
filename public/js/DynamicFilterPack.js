class DynamicFilterPack {
  constructor(element) {
    if (element === null) {
      return;
    }
    this.pagination = element.querySelector(".js-filter-pagination-pack");
    this.content = element.querySelector(".js-filter-content-pack");
    this.sorting = element.querySelector(".js-filter-sorting-pack");
    this.form = element.querySelector(".js-filter-form-pack");
    this.bindEvents();
  }

  bindEvents() {
    this.sorting.addEventListener("click", e => {
      if (e.target.tagName === "A") {
        e.preventDefault();
        this.loadUrl(e.target.getAttribute("href"));
      }
    });

    this.form.querySelectorAll("input").forEach(i => {
      i.addEventListener("change", this.laodForm.bind(this));
    });

    this.pagination.addEventListener("click", e => {
      if (e.target.tagName === "A") {
        e.preventDefault();
        this.loadUrl(e.target.getAttribute("href"));
      }
    });
  }
  async laodForm() {
    const data = new FormData(this.form);
    const url = new URL(
      this.form.getAttribute("action") || window.location.href
    );
    const params = new URLSearchParams();
    data.forEach((value, key) => {
      params.append(key, value);
    });
    return this.loadUrl(url.pathname + "?" + params.toString());
  }

  /*************** */

  async loadUrl(url) {
    const params = new URLSearchParams(url.split("?")[1] || "");
    params.set("ajax", 1);

    const res = await fetch(url.split("?")[0] + "?" + params.toString(), {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    });
    if (res.status >= 200 && res.status < 300) {
      const data = await res.json();
      this.content.innerHTML = data.content;
      this.sorting.innerHTML = data.sorting;
      this.pagination.innerHTML = data.pagination;
      params.delete("ajax");
      history.replaceState({}, "", url.split("?")[0] + "?" + params.toString());
    } else {
      console.log(res);
    }
  }
}

new DynamicFilterPack(document.querySelector(".js-filter-pack"));
