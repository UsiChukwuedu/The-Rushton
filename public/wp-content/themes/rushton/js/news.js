let isAtLoadEnd = false;
let blogCount = 0;
const initalBlogLoad = 10;
let currentBlogCount = initalBlogLoad;
const loadMoreCount = 3;
const btn = document.getElementsByClassName("load-more")[0];

const init = () => {
  const blogs = document.getElementsByClassName("blog");
  blogCount = blogs.length;
  console.log(blogCount);
  if (blogCount > initalBlogLoad) {
    btn.addEventListener("click", () => onButtonClick(Array.from(blogs)));
  } else {
    btn.disabled = true;
  }
};

const onButtonClick = (blogs = []) => {
  for (let i = 0; i < loadMoreCount; i++) {
    const el = blogs[currentBlogCount + i];
    if (el) {
      const img = el.getElementsByClassName("img")[0];
      if (img) {
        img.src = img.getAttribute("data-src");
      }
      el.classList.remove("hide");
    }
  }
  currentBlogCount += loadMoreCount;
  if (currentBlogCount >= blogCount) {
    btn.disabled = true;
  }
};

init();
