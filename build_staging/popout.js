let lastUpdate = $('html').data('last-update'), latestBuild = $('html').data('latest-build');

$(document).ready(function(){
    switchTo(localStorage.th_cj_mode);
    toggleTheme(localStorage.th_cj_theme);
    if(localStorage.th_cj_mobile == "true") toggleMobilePreview(true);
});

window.addEventListener("message", (e) => {
    if(!e.data) return;
    const [updateFunction, payload, className] = e.data;
    window[updateFunction]( payload, className );
});

window.addEventListener("beforeunload", toggleParentLayout);

function toggleParentLayout() {
    console.log(localStorage.th_cj_vertical);
    window.opener.toggleLayout( false, localStorage.th_cj_vertical );
    window.opener.refreshDisplay();
}

function closeWithoutLayoutChange() {
    window.removeEventListener("beforeunload", toggleParentLayout);
    window.close();
}

function toggleMobilePreview( toMobile ) {
    if(toMobile) window.resizeTo(414, window.screen.availHeight);
    else {
        window.resizeTo(window.screen.availWidth, window.screen.availHeight);
        window.moveTo(0, 0);
    }
}