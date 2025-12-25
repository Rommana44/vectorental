function bookNow() {
    alert("Please login or sign up to continue booking.");
    window.location.href = "login.html";
}

function toggleMenu() {
    var menu = document.getElementById("menu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}
