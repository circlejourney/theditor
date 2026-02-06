let lastUpdate = $('html').data('last-update'), latestBuild = $('html').data('latest-build');

$(document).ready(function(){
    switchTo(localStorage.th_cj_mode);
    toggleTheme(localStorage.th_cj_theme);
});

window.addEventListener("message", (e) => {
    if(!e.data) return;
    const [updateFunction, payload, className] = e.data;
    window[updateFunction]( payload, className );
});