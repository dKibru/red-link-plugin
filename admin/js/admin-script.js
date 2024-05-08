window.onload = function () {
  const redLinks = document.querySelectorAll("red-link");
  console.log({ redLinks });

  redLinks.forEach(async (link) => {
    const href = link.getAttribute("href");

    const exists = await fetch(
      `/wp-json/red-link/v1/check-page-exists?red_link_id=${href}`,
    ).then((data) => data.json());

    // console.log({ href, exists: exists.exists });
    if (exists.exists) {
      link.classList.add("exists");
    }
  });
};
