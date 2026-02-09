let lastUpdate = $('html').data('last-update'), latestBuild = $('html').data('latest-build');

// Handlers
window.addEventListener("message", (e) => {
    if(!e.data || !Array.isArray(e.data)) return;
    const [updateFunction, payload, className] = e.data;
    window[updateFunction]( payload, className );
});

window.addEventListener("beforeunload", toggleParentLayout);

function toggleParentLayout() {
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