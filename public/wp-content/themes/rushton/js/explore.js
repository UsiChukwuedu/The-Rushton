const init = () => {
  const btns = Array.from(document.getElementsByClassName("learn-more"));
  const filterSelect = document.getElementsByClassName("mobile-filter-nav")[0];
  btns.forEach((btn) => btn.addEventListener("click", handleBtnClick));
  filterSelect.addEventListener("change", handleSelectChange);
  const filters = Array.from(document.getElementsByClassName("filter-button"));
  filters.forEach((filter) =>
    filter.addEventListener("click", handleFilterClick)
  );
};

const handleBtnClick = (e) => {
  console.log();
  e.composedPath().forEach((path) => {
    const classArr = path.classList && Array.from(path.classList);
    if (classArr && classArr.includes("business")) {
      path.classList.toggle("active");
    }
  });
};

const handleSelectChange = (e) => {
  const slug = e.target.value;
  const prevActive = document.getElementsByClassName("nav-item active")[0];
  prevActive && prevActive.classList.remove("active");
  Array.from(document.getElementsByClassName("button")).map((el) => {
    if (el.getAttribute("data-name") === slug) {
      console.log(el.classList);
      el.parentElement.classList.add("active");
    }
  });
  updateBusinesses(slug);
};

const handleFilterClick = (e) => {
  const prevActive = document.getElementsByClassName("nav-item active")[0];
  const catName = e.currentTarget.getAttribute("data-name");
  const dropdown = document.getElementsByClassName("mobile-filter-nav")[0];
  dropdown.value = catName;
  prevActive && prevActive.classList.remove("active");
  e.target.parentElement.classList.add("active");
  updateBusinesses(catName);
};

const updateBusinesses = (catName) => {
  const section = document.getElementsByClassName("businesses")[0];
  const businesses = Array.from(section.getElementsByClassName("business"));
  const newCatBusinesses = [];
  businesses.forEach((business) => {
    if (catName === "all") {
      business.style.display = "block";
      newCatBusinesses.push(business);
    } else {
      if (business.getAttribute("data-cat") != catName) {
        business.style.display = "none";
      } else {
        business.style.display = "block";
        newCatBusinesses.push(business);
      }
    }
  });
  getLayout(newCatBusinesses);
};

const getLayout = (businesses = []) => {
  const patternRepeat = 6;
  businesses.forEach((business, i) => {
    let className = "";
    business.classList.contains("large-4") &&
      business.classList.remove("large-4");
    business.classList.contains("large-8") &&
      business.classList.remove("large-8");
    const remainder = i % patternRepeat;
    if (remainder === 1 || remainder === 2) {
      className = "large-4";
    } else if (remainder === 0 || remainder === 3) {
      className = "large-8";
    }
    className && business.classList.add(className);
  });
};

init();
