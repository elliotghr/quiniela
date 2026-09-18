document.addEventListener("DOMContentLoaded", function () {
  initializeTheme();

  const darkOptionElements = document.querySelectorAll("[data-dark-option]");

  darkOptionElements.forEach((element) => {
    element.addEventListener("click", function (event) {
      event.preventDefault();
      const option = this.getAttribute("data-dark-option");
      toggleTheme(option);
    });
  });
});

function setThemeIcons(theme) {
  const darkIcon = document.querySelector("[data-dark='dark-icon']");
  const lightIcon = document.querySelector("[data-dark='light-icon']");

  if (!darkIcon || !lightIcon) {
    return;
  }

  if (theme === "dark") {
    darkIcon.style.display = "block";
    lightIcon.style.display = "none";
  } else {
    darkIcon.style.display = "none";
    lightIcon.style.display = "block";
  }
}

function setThemeOptionActive(theme) {
  const options = document.querySelectorAll("[data-dark-option]");

  options.forEach((option) => {
    const optionTheme = option.getAttribute("data-dark-option") === "dark-icon" ? "dark" : "light";
    option.classList.toggle("active", optionTheme === theme);
  });
}

function toggleTheme(option) {
  const theme = option === "dark-icon" ? "dark" : "light";
  document.documentElement.setAttribute("data-bs-theme", theme);
  document.documentElement.style.colorScheme = theme;
  localStorage.setItem("darkMode", theme);
  setThemeIcons(theme);
  setThemeOptionActive(theme);
}

function initializeTheme() {
  const darkMode = localStorage.getItem("darkMode");
  const theme = darkMode === "dark" ? "dark" : "light";

  document.documentElement.setAttribute("data-bs-theme", theme);
  document.documentElement.style.colorScheme = theme;
  setThemeIcons(theme);
  setThemeOptionActive(theme);
}
